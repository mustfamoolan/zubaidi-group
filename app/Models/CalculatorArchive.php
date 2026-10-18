<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalculatorArchive extends Model
{
    use HasFactory;

    protected $table = 'calculator_archives';

    protected $fillable = [
        'company_id',
        'user_id',
        'type',
        'title',
        'archive_date',
        'rows_count',
        'totals',
        'items',
        'notes',
    ];

    protected $casts = [
        'archive_date' => 'date',
        'rows_count'   => 'integer',
        'totals'       => 'array',
        'items'        => 'array',
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
