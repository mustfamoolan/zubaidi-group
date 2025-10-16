<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'opening_balance',
        'current_balance',
        'currency',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
    ];

    /**
     * العلاقة مع الشركة
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * العلاقة مع حركات المصرف
     */
    public function transactions()
    {
        return $this->hasMany(BankTransaction::class)->orderBy('date', 'desc')->orderBy('created_at', 'desc');
    }

    /**
     * العلاقة مع الفواتير
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * تحديث الرصيد الحالي
     */
    public function updateBalance()
    {
        $deposits = $this->transactions()->where('type', 'deposit')->sum('amount');
        $withdrawals = $this->transactions()->where('type', 'withdrawal')->sum('amount');
        $deductions = $this->transactions()->where('type', 'invoice_deduction')->sum('amount');

        $this->current_balance = round($this->opening_balance + $deposits - $withdrawals - $deductions, 2);
        $this->save();
    }

    /**
     * إضافة عملية إيداع
     */
    public function deposit($amount, $description = null, $date = null)
    {
        $transaction = $this->transactions()->create([
            'type' => 'deposit',
            'amount' => $amount,
            'description' => $description,
            'date' => $date ?? now()->format('Y-m-d'),
        ]);

        $this->updateBalance();

        return $transaction;
    }

    /**
     * إضافة عملية سحب
     */
    public function withdraw($amount, $description = null, $date = null)
    {
        $transaction = $this->transactions()->create([
            'type' => 'withdrawal',
            'amount' => $amount,
            'description' => $description,
            'date' => $date ?? now()->format('Y-m-d'),
        ]);

        $this->updateBalance();

        return $transaction;
    }

    /**
     * خصم مبلغ فاتورة
     */
    public function deductInvoice(Invoice $invoice)
    {
        $amount = $invoice->getAmountForDeduction();

        $transaction = $this->transactions()->create([
            'type' => 'invoice_deduction',
            'amount' => $amount,
            'description' => 'خصم فاتورة رقم ' . $invoice->invoice_number . ' - ' . $invoice->beneficiary_company,
            'reference_id' => $invoice->id,
            'reference_type' => Invoice::class,
            'date' => $invoice->invoice_date,
        ]);

        $this->updateBalance();

        return $transaction;
    }

    /**
     * إرجاع مبلغ فاتورة
     */
    public function refundInvoice(Invoice $invoice)
    {
        $amount = $invoice->getAmountForDeduction();

        $transaction = $this->transactions()->create([
            'type' => 'deposit',
            'amount' => $amount,
            'description' => "إرجاع فاتورة رقم {$invoice->invoice_number} - {$invoice->beneficiary_company}",
            'reference_id' => $invoice->id,
            'reference_type' => Invoice::class,
            'date' => $invoice->invoice_date,
        ]);

        $this->updateBalance();

        return $transaction;
    }

    /**
     * إرجاع مبلغ فاتورة مع تفاصيل إضافية
     */
    public function refundInvoiceWithDetails(Invoice $invoice, $reason = 'تعديل الفاتورة')
    {
        $amount = $invoice->getAmountForDeduction();

        $transaction = $this->transactions()->create([
            'type' => 'deposit',
            'amount' => $amount,
            'description' => "إرجاع فاتورة رقم {$invoice->invoice_number} - {$invoice->beneficiary_company} ({$reason})",
            'reference_id' => $invoice->id,
            'reference_type' => Invoice::class,
            'date' => $invoice->invoice_date,
        ]);

        $this->updateBalance();

        return $transaction;
    }

    /**
     * خصم مبلغ فاتورة مع تفاصيل إضافية
     */
    public function deductInvoiceWithDetails(Invoice $invoice, $reason = 'دفع الفاتورة')
    {
        $amount = $invoice->getAmountForDeduction();

        $transaction = $this->transactions()->create([
            'type' => 'invoice_deduction',
            'amount' => $amount,
            'description' => "خصم فاتورة رقم {$invoice->invoice_number} - {$invoice->beneficiary_company} ({$reason})",
            'reference_id' => $invoice->id,
            'reference_type' => Invoice::class,
            'date' => $invoice->invoice_date,
        ]);

        $this->updateBalance();

        return $transaction;
    }

    /**
     * إدارة تغيير حالة الفاتورة من مدفوعة إلى غير مدفوعة
     */
    public function changeInvoiceStatusToUnpaid(Invoice $invoice)
    {
        return $this->refundInvoiceWithDetails($invoice, 'تغيير الحالة إلى غير مدفوعة');
    }

    /**
     * إدارة تغيير حالة الفاتورة من غير مدفوعة إلى مدفوعة
     */
    public function changeInvoiceStatusToPaid(Invoice $invoice)
    {
        return $this->deductInvoiceWithDetails($invoice, 'تغيير الحالة إلى مدفوعة');
    }

    /**
     * إدارة تغيير مبلغ الفاتورة
     */
    public function changeInvoiceAmount(Invoice $invoice, $oldAmount, $newAmount, $reason = 'تعديل المبلغ')
    {
        $difference = $newAmount - $oldAmount;

        if ($difference > 0) {
            // المبلغ الجديد أكبر - خصم الفرق
            $transaction = $this->transactions()->create([
                'type' => 'invoice_deduction',
                'amount' => $difference,
                'description' => "زيادة مبلغ فاتورة رقم {$invoice->invoice_number} - {$invoice->beneficiary_company} ({$reason})",
                'reference_id' => $invoice->id,
                'reference_type' => Invoice::class,
                'date' => $invoice->invoice_date,
            ]);
        } elseif ($difference < 0) {
            // المبلغ الجديد أصغر - إرجاع الفرق
            $transaction = $this->transactions()->create([
                'type' => 'deposit',
                'amount' => abs($difference),
                'description' => "تقليل مبلغ فاتورة رقم {$invoice->invoice_number} - {$invoice->beneficiary_company} ({$reason})",
                'reference_id' => $invoice->id,
                'reference_type' => Invoice::class,
                'date' => $invoice->invoice_date,
            ]);
        }

        $this->updateBalance();

        return $transaction ?? null;
    }

    /**
     * إدارة تغيير المصرف للفاتورة
     */
    public function changeInvoiceBank(Invoice $invoice, Bank $oldBank, Bank $newBank)
    {
        $amount = $invoice->getAmountForDeduction();

        // إرجاع المبلغ من المصرف القديم
        $oldBank->refundInvoiceWithDetails($invoice, 'نقل الفاتورة إلى مصرف آخر');

        // خصم المبلغ من المصرف الجديد
        $newBank->deductInvoiceWithDetails($invoice, 'استلام فاتورة من مصرف آخر');

        return true;
    }

    /**
     * حذف فاتورة وإرجاع المبلغ
     */
    public function deleteInvoice(Invoice $invoice)
    {
        return $this->refundInvoiceWithDetails($invoice, 'حذف الفاتورة');
    }
}

