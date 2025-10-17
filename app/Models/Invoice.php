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
        'amount_usd',
        'exchange_rate',
        'bank_commission',
        'total_amount_iqd',
        'amount', // الاحتفاظ بالحقل القديم للتوافق مع البيانات الموجودة
        'bank_id',
        'beneficiary_id',
        'invoice_date',
        'beneficiary_company',
        'status',
        'shipping_status',
        'last_shipping_notification_sent_at',
    ];

    protected $casts = [
        'amount_usd' => 'decimal:2',
        'exchange_rate' => 'decimal:2',
        'bank_commission' => 'decimal:2',
        'total_amount_iqd' => 'decimal:2',
        'amount' => 'decimal:2',
        'invoice_date' => 'date',
        'last_shipping_notification_sent_at' => 'datetime',
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

    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class);
    }

    /**
     * العلاقة متعددة الأنواع للحركات
     */
    public function transactions()
    {
        return $this->morphMany(BankTransaction::class, 'reference');
    }

    /**
     * حساب المبلغ الإجمالي بالدينار العراقي
     */
    public function calculateTotalAmountIqd()
    {
        if ($this->amount_usd && $this->exchange_rate) {
            return ($this->amount_usd * $this->exchange_rate) + $this->bank_commission;
        }

        // إذا لم تكن الحقول الجديدة متوفرة، استخدم المبلغ القديم
        return $this->amount ?? 0;
    }

    /**
     * الحصول على المبلغ للخصم من المصرف
     */
    public function getAmountForDeduction()
    {
        // استخدم المبلغ الجديد إذا كان متوفراً، وإلا استخدم القديم
        return $this->total_amount_iqd ?? $this->amount ?? 0;
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

