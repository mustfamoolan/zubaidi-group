<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DinarCalculatorRecord extends Model
{
    use HasFactory;

    protected $table = 'dinar_calculator_records';

    protected $fillable = [
        'user_id',
        'company_id',
        'transaction_no',
        'currency',
        'exchange_rate',
        'iqd_base',
        'commission_rate',
        'commission_type',
        'commission_iqd',
        'customs_rate',
        'customs_type',
        'customs_iqd',
        'total_iqd',
        'country_rate',
        'country_op',
        'country_final_rate',
        'amount',
        'notes',
        'sort_order',
    ];

    protected $casts = [
        'exchange_rate'      => 'float',
        'iqd_base'           => 'float',
        'commission_rate'    => 'float',
        'commission_iqd'     => 'float',
        'customs_rate'       => 'float',
        'customs_iqd'        => 'float',
        'total_iqd'          => 'float',
        'country_rate'       => 'float',
        'country_final_rate' => 'float',
        'amount'             => 'float',
        'sort_order'         => 'integer',
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
