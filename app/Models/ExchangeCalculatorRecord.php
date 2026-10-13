<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExchangeCalculatorRecord extends Model
{
    use HasFactory;

    protected $table = 'exchange_calculator_records';

    protected $fillable = [
        'user_id',
        'company_id',
        'transaction_no',
        'currency',
        'amount',
        'exchange_rate',
        'iqd_amount',
        'commission_rate',
        'commission_type',
        'commission_iqd',
        'total_iqd',
        'notes',
        'sort_order',
    ];

    protected $casts = [
        'amount'          => 'float',
        'exchange_rate'   => 'float',
        'iqd_amount'      => 'float',
        'commission_rate' => 'float',
        'commission_iqd'  => 'float',
        'total_iqd'       => 'float',
        'sort_order'      => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
