<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
    ];

    /**
     * العلاقة مع الشركة
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * العلاقة مع الفواتير
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * الحصول على إحصائيات المستفيد
     */
    public function getStatistics()
    {
        $totalInvoices = $this->invoices()->count();
        $paidInvoices = $this->invoices()->where('status', 'paid')->count();
        $unpaidInvoices = $this->invoices()->where('status', 'unpaid')->count();
        $totalAmount = $this->invoices()->sum('total_amount_iqd') ?? $this->invoices()->sum('amount');
        $paidAmount = $this->invoices()->where('status', 'paid')->sum('total_amount_iqd') ?? $this->invoices()->where('status', 'paid')->sum('amount');
        $unpaidAmount = $this->invoices()->where('status', 'unpaid')->sum('total_amount_iqd') ?? $this->invoices()->where('status', 'unpaid')->sum('amount');

        return [
            'total_invoices' => $totalInvoices,
            'paid_invoices' => $paidInvoices,
            'unpaid_invoices' => $unpaidInvoices,
            'total_amount' => $totalAmount,
            'paid_amount' => $paidAmount,
            'unpaid_amount' => $unpaidAmount,
        ];
    }
}
