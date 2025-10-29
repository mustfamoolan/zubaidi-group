<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Company;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\URL;
use TCPDF;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Company $company)
    {
        $invoices = $company->invoices()->with(['bank', 'shipments', 'beneficiary'])->orderBy('invoice_date', 'desc')->get();
        $banks = $company->banks;
        $beneficiaries = $company->beneficiaries()->orderBy('name')->get();
        $shipments = $company->shipments()->orderBy('container_number')->get();
        return view('invoices.index', compact('company', 'invoices', 'banks', 'beneficiaries', 'shipments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Company $company)
    {
        $banks = $company->banks;
        $shipments = $company->shipments;
        $beneficiaries = $company->beneficiaries()->orderBy('name')->get();
        return view('invoices.create', compact('company', 'banks', 'shipments', 'beneficiaries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Company $company)
    {
        $request->validate([
            'invoice_number' => 'required|string|unique:invoices,invoice_number',
            'amount_usd' => 'required|numeric|min:0',
            'exchange_rate' => 'required|numeric|min:0',
            'bank_commission' => 'nullable|numeric|min:0',
            'bank_id' => 'nullable|exists:banks,id',
            'invoice_date' => 'required|date',
            'beneficiary_id' => 'required|exists:beneficiaries,id',
            'beneficiary_company' => 'nullable|string|max:255',
            'shipping_status' => 'required|in:shipped,not_shipped',
            'shipments' => 'required_if:shipping_status,shipped|array|min:1',
            'shipments.*' => 'exists:shipments,id',
        ]);

        // حساب المبلغ الإجمالي بالدينار العراقي
        $totalAmountIqd = ($request->amount_usd * $request->exchange_rate) + ($request->bank_commission ?? 0);

        // الحصول على اسم المستفيد
        $beneficiary = \App\Models\Beneficiary::find($request->beneficiary_id);
        $beneficiaryName = $beneficiary ? $beneficiary->name : $request->beneficiary_company;

        $invoice = $company->invoices()->create([
            'invoice_number' => $request->invoice_number,
            'amount_usd' => $request->amount_usd,
            'exchange_rate' => $request->exchange_rate,
            'bank_commission' => $request->bank_commission ?? 0,
            'total_amount_iqd' => $totalAmountIqd,
            'amount' => $totalAmountIqd, // الاحتفاظ بالحقل القديم للتوافق
            'bank_id' => $request->bank_id,
            'invoice_date' => $request->invoice_date,
            'beneficiary_id' => $request->beneficiary_id,
            'beneficiary_company' => $beneficiaryName,
            'status' => 'paid', // دائماً مدفوعة
            'shipping_status' => $request->shipping_status,
        ]);

        // ربط الفاتورة بالشحنات فقط إذا كانت الحالة "مشحون"
        if ($request->shipping_status === 'shipped' && $request->has('shipments')) {
            $invoice->shipments()->attach($request->shipments);
        }

        // خصم المبلغ من المصرف تلقائياً إذا تم تحديد مصرف
        if ($request->bank_id) {
            $bank = \App\Models\Bank::find($request->bank_id);
            if ($bank) {
                $bank->deductInvoice($invoice);
            }
        }

        // إنشاء إشعار فوري للفواتير غير المشحونة
        if ($request->shipping_status === 'not_shipped') {
            $this->createUnshippedInvoiceNotification($invoice);
        }

        return redirect()->route('companies.invoices.index', $company)
            ->with('success', 'تم إنشاء الفاتورة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company, Invoice $invoice)
    {
        $invoice->load(['bank', 'shipments']);
        return view('invoices.show', compact('company', 'invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company, Invoice $invoice)
    {
        $banks = $company->banks;
        $shipments = $company->shipments;
        $beneficiaries = $company->beneficiaries()->orderBy('name')->get();
        return view('invoices.edit', compact('company', 'invoice', 'banks', 'shipments', 'beneficiaries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company, Invoice $invoice)
    {
        $request->validate([
            'invoice_number' => 'required|string|unique:invoices,invoice_number,' . $invoice->id,
            'amount_usd' => 'required|numeric|min:0',
            'exchange_rate' => 'required|numeric|min:0',
            'bank_commission' => 'nullable|numeric|min:0',
            'bank_id' => 'nullable|exists:banks,id',
            'invoice_date' => 'required|date',
            'beneficiary_id' => 'required|exists:beneficiaries,id',
            'beneficiary_company' => 'nullable|string|max:255',
            'shipping_status' => 'required|in:shipped,not_shipped',
            'shipments' => 'required_if:shipping_status,shipped|array|min:1',
            'shipments.*' => 'exists:shipments,id',
        ]);

        $oldBankId = $invoice->bank_id;
        $oldAmount = $invoice->getAmountForDeduction();
        $oldStatus = $invoice->status;

        // حساب المبلغ الإجمالي الجديد بالدينار العراقي
        $totalAmountIqd = ($request->amount_usd * $request->exchange_rate) + ($request->bank_commission ?? 0);

        // الحصول على اسم المستفيد
        $beneficiary = \App\Models\Beneficiary::find($request->beneficiary_id);
        $beneficiaryName = $beneficiary ? $beneficiary->name : $request->beneficiary_company;

        $invoice->update([
            'invoice_number' => $request->invoice_number,
            'amount_usd' => $request->amount_usd,
            'exchange_rate' => $request->exchange_rate,
            'bank_commission' => $request->bank_commission ?? 0,
            'total_amount_iqd' => $totalAmountIqd,
            'amount' => $totalAmountIqd, // الاحتفاظ بالحقل القديم للتوافق
            'bank_id' => $request->bank_id,
            'invoice_date' => $request->invoice_date,
            'beneficiary_id' => $request->beneficiary_id,
            'beneficiary_company' => $beneficiaryName,
            'status' => 'paid', // دائماً مدفوعة
            'shipping_status' => $request->shipping_status,
        ]);

        // التعامل مع الحركات المصرفية وإرجاع رسالة تفصيلية
        $bankMessage = $this->handleBankTransactions($invoice, $oldBankId, $oldAmount, $oldStatus, $request->bank_id, $totalAmountIqd, 'paid');

        // تحديث ربط الفاتورة بالشحنات
        if ($request->shipping_status === 'shipped') {
            if ($request->has('shipments')) {
                $invoice->shipments()->sync($request->shipments);
            }
        } else {
            // إزالة جميع الشحنات إذا تم تغيير الحالة إلى "غير مشحون"
            $invoice->shipments()->detach();
        }

        $successMessage = 'تم تحديث الفاتورة بنجاح';
        if ($bankMessage) {
            $successMessage .= ' - ' . $bankMessage;
        }

        return redirect()->route('companies.invoices.index', $company)
            ->with('success', $successMessage);
    }

    /**
     * إدارة الحركات المصرفية المبسطة
     */
    private function handleBankTransactions($invoice, $oldBankId, $oldAmount, $oldStatus, $newBankId, $newAmount, $newStatus)
    {
        // حالة تعديل داخل نفس المصرف مع بقاء الحالة مدفوعة: سجل فرق المبلغ فقط
        if ($oldBankId && $newBankId && $oldBankId === $newBankId && $oldStatus === 'paid' && $newStatus === 'paid') {
            $delta = round($newAmount - $oldAmount, 2);
            if ($delta == 0.0) {
                return 'لا توجد تغييرات على حركة المصرف (المبلغ لم يتغير)';
            }

            $bank = \App\Models\Bank::find($newBankId);
            if (!$bank) {
                return null;
            }

            // إنشاء حركة واحدة بفرق المبلغ
            if ($delta > 0) {
                // زيادة المبلغ: خصم فرق المبلغ
                $bank->transactions()->create([
                    'type' => 'invoice_deduction',
                    'amount' => $delta,
                    'description' => "تعديل الفاتورة: فرق خصم +{$delta}",
                    'reference_id' => $invoice->id,
                    'reference_type' => \App\Models\Invoice::class,
                    'date' => $invoice->invoice_date,
                ]);
            } else {
                // نقصان المبلغ: إيداع فرق المبلغ
                $bank->transactions()->create([
                    'type' => 'deposit',
                    'amount' => abs($delta),
                    'description' => "تعديل الفاتورة: فرق إيداع ".abs($delta),
                    'reference_id' => $invoice->id,
                    'reference_type' => \App\Models\Invoice::class,
                    'date' => $invoice->invoice_date,
                ]);
            }

            $bank->updateBalance();

            $oldFormatted = number_format($oldAmount, 2);
            $newFormatted = number_format($newAmount, 2);
            $deltaFormatted = number_format(abs($delta), 2);
            $deltaSign = $delta > 0 ? '+' : '-';
            return "تأثير على المصرف: المبلغ القديم {$oldFormatted} → الجديد {$newFormatted} (فرق {$deltaSign}{$deltaFormatted}) وتاريخ الحركة {$invoice->invoice_date->format('Y-m-d')}";
        }

        // في حال تغيير المصرف أو تغيّر الحالة: استرجاع ثم خصم كامل (السلوك الحالي)
        $messages = [];
        if ($oldStatus === 'paid' && $oldBankId) {
            $oldBank = \App\Models\Bank::find($oldBankId);
            if ($oldBank) {
                $oldBank->refundInvoiceWithDetails($invoice, 'تعديل الفاتورة');
                $messages[] = 'تم إيداع مبلغ الفاتورة في المصرف القديم';
            }
        }

        if ($newStatus === 'paid' && $newBankId) {
            $newBank = \App\Models\Bank::find($newBankId);
            if ($newBank) {
                $newBank->deductInvoiceWithDetails($invoice, 'تحديث الفاتورة');
                $messages[] = 'تم خصم مبلغ الفاتورة من المصرف الجديد';
            }
        }

        if (!empty($messages)) {
            return implode('، ', $messages);
        }
        
        return null;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company, Invoice $invoice)
    {
        // إرجاع المبلغ للمصرف إذا كانت الفاتورة مدفوعة
        if ($invoice->bank_id && $invoice->status === 'paid') {
            $bank = \App\Models\Bank::find($invoice->bank_id);
            if ($bank) {
                $bank->deleteInvoice($invoice);
            }
        }

        $invoice->delete();

        return redirect()->route('companies.invoices.index', $company)
            ->with('success', 'تم حذف الفاتورة بنجاح وتم إرجاع المبلغ للمصرف');
    }

    /**
     * تسجيل دفع الفاتورة
     */
    public function processPayment(Company $company, Invoice $invoice)
    {
        if ($invoice->processPayment()) {
            return redirect()->route('companies.invoices.show', [$company, $invoice])
                ->with('success', 'تم تسجيل دفع الفاتورة وخصم المبلغ من المصرف');
        }

        return redirect()->route('companies.invoices.show', [$company, $invoice])
            ->with('error', 'حدث خطأ أثناء تسجيل الدفع. تأكد من تحديد المصرف وأن الفاتورة غير مدفوعة');
    }

    /**
     * ربط الفاتورة بشحنة
     */
    public function attachShipment(Request $request, Company $company, Invoice $invoice)
    {
        $request->validate([
            'shipment_id' => 'required|exists:shipments,id',
        ]);

        $invoice->shipments()->attach($request->shipment_id);

        return redirect()->route('companies.invoices.show', [$company, $invoice])
            ->with('success', 'تم ربط الشحنة بالفاتورة بنجاح');
    }

    /**
     * عرض الفاتورة كـ PDF في المتصفح
     */
    public function viewPdf(Company $company, Invoice $invoice)
    {
        $invoice->load(['bank', 'shipments', 'beneficiary']);

        // إنشاء PDF باستخدام TCPDF
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // إعدادات المستند
        $pdf->SetCreator('Al-Zubaidi Group');
        $pdf->SetAuthor('Al-Zubaidi Group');
        $pdf->SetTitle("فاتورة #{$invoice->invoice_number}");
        $pdf->SetSubject('فاتورة');

        // إزالة الهيدر والفوتر الافتراضيين
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // إضافة صفحة
        $pdf->AddPage();

        // إعداد الخط العربي
        $pdf->SetFont('dejavusans', '', 12);

        // محتوى الفاتورة
        $html = $this->generateInvoiceHtml($company, $invoice);

        // كتابة المحتوى
        $pdf->writeHTML($html, true, false, true, false, '');

        return $pdf->Output("invoice-{$invoice->invoice_number}.pdf", 'I');
    }

    /**
     * توليد HTML للفاتورة
     */
    private function generateInvoiceHtml(Company $company, Invoice $invoice)
    {
        $html = '
        <div style="text-align: center; font-size: 18px; font-weight: bold; margin-bottom: 20px;">
            فاتورة
        </div>

        <div style="margin-bottom: 20px;">
            <div style="font-size: 14px; font-weight: bold;">' . $company->name . '</div>
            <div style="font-size: 10px;">العراق، بغداد</div>
            <div style="font-size: 10px;">info@alzubaidgroup.com</div>
            <div style="font-size: 10px;">+964 (0) 770 123 4567</div>
        </div>

        <table border="1" cellpadding="5" cellspacing="0" style="width: 100%; margin-bottom: 20px;">
            <tr>
                <td style="width: 50%;">
                    <strong>معلومات الفاتورة</strong><br>
                    رقم الفاتورة: #' . $invoice->invoice_number . '<br>
                    تاريخ الإصدار: ' . $invoice->invoice_date->format('Y-m-d') . '<br>
                    الحالة: ' . ($invoice->status === 'paid' ? 'مدفوعة' : 'غير مدفوعة') . '
                </td>
                <td style="width: 50%;">
                    <strong>معلومات المستفيد</strong><br>
                    اسم المستفيد: ' . $invoice->beneficiary_company . '<br>
                    ' . ($invoice->beneficiary ? 'المستفيد المسجل: ' . $invoice->beneficiary->name : '') . '
                </td>
            </tr>
        </table>

        <table border="1" cellpadding="5" cellspacing="0" style="width: 100%; margin-bottom: 20px;">
            <tr style="background-color: #f5f5f5;">
                <th style="width: 40%;">الوصف</th>
                <th style="width: 30%;">المبلغ</th>
                <th style="width: 30%;">العملة</th>
            </tr>
            <tr>
                <td>المبلغ الأصلي</td>
                <td>' . number_format($invoice->amount_usd ?? 0, 2) . '</td>
                <td>USD</td>
            </tr>
            <tr>
                <td>سعر الصرف</td>
                <td>' . number_format($invoice->exchange_rate ?? 0, 2) . '</td>
                <td>دينار/دولار</td>
            </tr>
            <tr>
                <td>المبلغ بعد التحويل</td>
                <td>' . number_format(($invoice->amount_usd ?? 0) * ($invoice->exchange_rate ?? 0), 2) . '</td>
                <td>دينار عراقي</td>
            </tr>
            <tr>
                <td>عمولة المصرف</td>
                <td>' . number_format($invoice->bank_commission ?? 0, 2) . '</td>
                <td>دينار عراقي</td>
            </tr>
            <tr style="background-color: #f5f5f5; font-weight: bold;">
                <td>المبلغ الإجمالي</td>
                <td>' . number_format($invoice->total_amount_iqd ?? $invoice->amount ?? 0, 2) . '</td>
                <td>دينار عراقي</td>
            </tr>
        </table>';

        if ($invoice->bank) {
            $html .= '
            <div style="margin-bottom: 20px; padding: 10px; border: 1px solid #ccc; background-color: #f9f9f9;">
                <strong>معلومات المصرف:</strong><br>
                اسم المصرف: ' . $invoice->bank->name . '<br>
                العملة: ' . $invoice->bank->currency . '
            </div>';
        }

        if ($invoice->shipments && $invoice->shipments->count() > 0) {
            $html .= '
            <table border="1" cellpadding="3" cellspacing="0" style="width: 100%; margin-bottom: 20px;">
                <tr style="background-color: #f5f5f5;">
                    <th style="width: 10%;">م.</th>
                    <th style="width: 20%;">رقم الحاوية</th>
                    <th style="width: 20%;">رقم البوليصة</th>
                    <th style="width: 15%;">الحالة</th>
                    <th style="width: 15%;">الوزن</th>
                    <th style="width: 10%;">حجم الحاوية</th>
                    <th style="width: 10%;">عدد الكراتين</th>
                </tr>';

            foreach ($invoice->shipments as $index => $shipment) {
                $containerSize = '';
                if ($shipment->container_size === 'C') {
                    $containerSize = '20 قدم';
                } elseif ($shipment->container_size === 'B') {
                    $containerSize = '40 قدم';
                } elseif ($shipment->container_size === 'M') {
                    $containerSize = '45 قدم';
                } else {
                    $containerSize = $shipment->container_size ?? 'غير محدد';
                }

                $html .= '
                <tr>
                    <td>' . ($index + 1) . '</td>
                    <td>' . $shipment->container_number . '</td>
                    <td>' . $shipment->policy_number . '</td>
                    <td>' . ($shipment->status === 'shipped' ? 'مشحون' : 'غير مشحون') . '</td>
                    <td>' . ($shipment->weight ?? 'غير محدد') . '</td>
                    <td>' . $containerSize . '</td>
                    <td>' . ($shipment->carton_count ?? 'غير محدد') . '</td>
                </tr>';
            }

            $html .= '</table>';
        }

        $html .= '
        <div style="margin-top: 30px; text-align: center; font-size: 10px; color: #666;">
            <p><strong>شكراً لاختياركم خدماتنا</strong></p>
            <p>هذه الفاتورة صادرة من نظام إدارة الفواتير - مجموعة الزبيدي</p>
            <p>تاريخ الطباعة: ' . now()->format('Y-m-d H:i') . '</p>
        </div>';

        return $html;
    }

    /**
     * تحميل الفاتورة كـ PDF
     */
    public function downloadPdf(Company $company, Invoice $invoice)
    {
        $invoice->load(['bank', 'shipments', 'beneficiary']);

        // إنشاء PDF باستخدام TCPDF
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // إعدادات المستند
        $pdf->SetCreator('Al-Zubaidi Group');
        $pdf->SetAuthor('Al-Zubaidi Group');
        $pdf->SetTitle("فاتورة #{$invoice->invoice_number}");
        $pdf->SetSubject('فاتورة');

        // إزالة الهيدر والفوتر الافتراضيين
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // إضافة صفحة
        $pdf->AddPage();

        // إعداد الخط العربي
        $pdf->SetFont('dejavusans', '', 12);

        // محتوى الفاتورة
        $html = $this->generateInvoiceHtml($company, $invoice);

        // كتابة المحتوى
        $pdf->writeHTML($html, true, false, true, false, '');

        return $pdf->Output("invoice-{$invoice->invoice_number}.pdf", 'D');
    }

    /**
     * الحصول على رابط مشاركة الفاتورة
     */
    public function getShareLink(Company $company, Invoice $invoice)
    {
        // إنشاء رابط مؤقت صالح لمدة 24 ساعة
        $url = URL::temporarySignedRoute(
            'companies.invoices.pdf',
            now()->addHours(24),
            ['company' => $company->id, 'invoice' => $invoice->id]
        );

        return response()->json([
            'success' => true,
            'share_link' => $url,
            'expires_at' => now()->addHours(24)->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * إنشاء إشعار فوري للفواتير غير المشحونة
     */
    private function createUnshippedInvoiceNotification($invoice)
    {
        // إنشاء إشعار لجميع المستخدمين
        $users = \App\Models\User::all();

        if ($users->isNotEmpty()) {
            foreach ($users as $user) {
                \App\Models\Notification::create([
                    'user_id' => $user->id,
                    'notifiable_id' => $invoice->id,
                    'notifiable_type' => \App\Models\Invoice::class,
                    'type' => 'unshipped_invoice',
                    'message' => "تم إنشاء فاتورة جديدة غير مشحونة #{$invoice->invoice_number} بتاريخ {$invoice->invoice_date->format('Y-m-d')}",
                ]);
            }

            // تعيين تاريخ الإشعار الأول
            $invoice->update([
                'last_shipping_notification_sent_at' => now(),
            ]);
        }
    }

    /**
     * طباعة جميع الفواتير
     */
    public function printAll(Company $company)
    {
        $invoices = $company->invoices()
            ->with(['bank', 'beneficiary', 'shipments'])
            ->orderBy('invoice_date', 'desc')
            ->get();

        $totalAmountUsd = $invoices->sum('amount_usd');
        $totalAmountIqd = $invoices->sum('total_amount_iqd');

        return view('invoices.print-all', compact('company', 'invoices', 'totalAmountUsd', 'totalAmountIqd'));
    }
}

