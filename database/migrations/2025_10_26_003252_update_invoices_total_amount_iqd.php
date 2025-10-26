<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // حساب total_amount_iqd للفواتير الموجودة
        DB::statement("
            UPDATE invoices
            SET total_amount_iqd = (amount_usd * exchange_rate) + bank_commission
            WHERE amount_usd IS NOT NULL
            AND exchange_rate IS NOT NULL
            AND bank_commission IS NOT NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // إعادة تعيين total_amount_iqd إلى NULL
        DB::statement("UPDATE invoices SET total_amount_iqd = NULL");
    }
};
