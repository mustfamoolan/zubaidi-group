<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_id',
        'type',
        'amount',
        'description',
        'reference_id',
        'reference_type',
        'date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
    ];

    /**
     * العلاقة مع المصرف
     */
    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    /**
     * العلاقة متعددة الأنواع للمرجع
     */
    public function reference()
    {
        return $this->morphTo();
    }
}

