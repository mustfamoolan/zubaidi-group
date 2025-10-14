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

        $this->current_balance = $this->opening_balance + $deposits - $withdrawals - $deductions;
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
        $transaction = $this->transactions()->create([
            'type' => 'invoice_deduction',
            'amount' => $invoice->amount,
            'description' => 'خصم فاتورة رقم ' . $invoice->invoice_number . ' - ' . $invoice->beneficiary_company,
            'reference_id' => $invoice->id,
            'reference_type' => Invoice::class,
            'date' => $invoice->invoice_date,
        ]);

        $this->updateBalance();

        return $transaction;
    }
}

