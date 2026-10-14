<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('exchange_calculator_records', function (Blueprint $table) {
            if (!Schema::hasColumn('exchange_calculator_records', 'swift_commission')) {
                $table->decimal('swift_commission', 18, 4)->default(0)->after('commission_iqd');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exchange_calculator_records', function (Blueprint $table) {
            if (Schema::hasColumn('exchange_calculator_records', 'swift_commission')) {
                $table->dropColumn('swift_commission');
            }
        });
    }
};
