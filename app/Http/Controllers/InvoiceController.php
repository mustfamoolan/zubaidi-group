<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Company;
use App\Models\Shipment;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Company $company)
    {
        $invoices = $company->invoices()->with(['bank', 'shipments'])->orderBy('invoice_date', 'desc')->get();
        $banks = $company->banks;
        return view('invoices.index', compact('company', 'invoices', 'banks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Company $company)
    {
        $banks = $company->banks;
        $shipments = $company->shipments;
        return view('invoices.create', compact('company', 'banks', 'shipments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Company $company)
    {
        $request->validate([
            'invoice_number' => 'required|string|unique:invoices,invoice_number',
            'amount' => 'required|numeric|min:0',
            'bank_id' => 'nullable|exists:banks,id',
            'invoice_date' => 'required|date',
            'beneficiary_company' => 'required|string|max:255',
            'status' => 'required|in:paid,unpaid',
            'shipments' => 'nullable|array',
            'shipments.*' => 'exists:shipments,id',
        ]);

        $invoice = $company->invoices()->create([
            'invoice_number' => $request->invoice_number,
            'amount' => $request->amount,
            'bank_id' => $request->bank_id,
            'invoice_date' => $request->invoice_date,
            'beneficiary_company' => $request->beneficiary_company,
            'status' => $request->status,
        ]);

        // ربط الفاتورة بالشحنات
        if ($request->has('shipments')) {
            $invoice->shipments()->attach($request->shipments);
        }

        // خصم المبلغ من المصرف تلقائياً إذا تم تحديد مصرف
        if ($request->bank_id) {
            $bank = \App\Models\Bank::find($request->bank_id);
            if ($bank) {
                $bank->deductInvoice($invoice);
                return redirect()->route('companies.invoices.index', $company)
                    ->with('success', 'تم إنشاء الفاتورة بنجاح وتم خصم المبلغ من المصرف');
            }
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
        return view('invoices.edit', compact('company', 'invoice', 'banks', 'shipments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company, Invoice $invoice)
    {
        $request->validate([
            'invoice_number' => 'required|string|unique:invoices,invoice_number,' . $invoice->id,
            'amount' => 'required|numeric|min:0',
            'bank_id' => 'nullable|exists:banks,id',
            'invoice_date' => 'required|date',
            'beneficiary_company' => 'required|string|max:255',
            'status' => 'required|in:paid,unpaid',
            'shipments' => 'nullable|array',
            'shipments.*' => 'exists:shipments,id',
        ]);

        $oldBankId = $invoice->bank_id;
        $oldAmount = $invoice->amount;

        $invoice->update([
            'invoice_number' => $request->invoice_number,
            'amount' => $request->amount,
            'bank_id' => $request->bank_id,
            'invoice_date' => $request->invoice_date,
            'beneficiary_company' => $request->beneficiary_company,
            'status' => $request->status,
        ]);

        // تحديث ربط الفاتورة بالشحنات
        if ($request->has('shipments')) {
            $invoice->shipments()->sync($request->shipments);
        }

        // إذا تغير المصرف أو المبلغ، نحتاج لتحديث الحركات
        if ($oldBankId != $request->bank_id || $oldAmount != $request->amount) {
            // حذف الحركة القديمة إن وُجدت
            if ($oldBankId) {
                \App\Models\BankTransaction::where('reference_type', \App\Models\Invoice::class)
                    ->where('reference_id', $invoice->id)
                    ->delete();

                // إعادة حساب رصيد المصرف القديم
                $oldBank = \App\Models\Bank::find($oldBankId);
                if ($oldBank) {
                    $oldBank->updateBalance();
                }
            }

            // إضافة حركة جديدة للمصرف الجديد
            if ($request->bank_id) {
                $newBank = \App\Models\Bank::find($request->bank_id);
                if ($newBank) {
                    $newBank->deductInvoice($invoice);
                }
            }

            return redirect()->route('companies.invoices.index', $company)
                ->with('success', 'تم تحديث الفاتورة بنجاح وتم تحديث حركة المصرف');
        }

        return redirect()->route('companies.invoices.index', $company)
            ->with('success', 'تم تحديث الفاتورة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company, Invoice $invoice)
    {
        // حذف الحركة المرتبطة من المصرف
        if ($invoice->bank_id) {
            \App\Models\BankTransaction::where('reference_type', \App\Models\Invoice::class)
                ->where('reference_id', $invoice->id)
                ->delete();

            // إعادة حساب رصيد المصرف
            $bank = \App\Models\Bank::find($invoice->bank_id);
            if ($bank) {
                $bank->updateBalance();
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
}

