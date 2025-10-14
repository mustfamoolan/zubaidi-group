<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'invoice_number',
        'amount',
        'bank_id',
        'invoice_date',
        'beneficiary_company',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'invoice_date' => 'date',
    ];

    /**
     * العلاقة مع الشركة
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * العلاقة مع المصرف
     */
    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    /**
     * العلاقة مع الشحنات
     */
    public function shipments()
    {
        return $this->belongsToMany(Shipment::class, 'invoice_shipment');
    }

    /**
     * العلاقة متعددة الأنواع للحركات
     */
    public function transactions()
    {
        return $this->morphMany(BankTransaction::class, 'reference');
    }

    /**
     * تسجيل الفاتورة وخصم المبلغ من المصرف
     */
    public function processPayment()
    {
        if ($this->status === 'paid') {
            return false; // الفاتورة مدفوعة بالفعل
        }

        if (!$this->bank) {
            return false; // لا يوجد مصرف محدد
        }

        // خصم المبلغ من المصرف
        $this->bank->deductInvoice($this);

        // تحديث حالة الفاتورة
        $this->status = 'paid';
        $this->save();

        return true;
    }
}

