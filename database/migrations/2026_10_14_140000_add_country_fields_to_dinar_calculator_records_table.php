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
        Schema::table('dinar_calculator_records', function (Blueprint $table) {
            if (!Schema::hasColumn('dinar_calculator_records', 'country_op')) {
                $table->string('country_op', 10)->default('mul')->after('country_rate');
            }
            if (!Schema::hasColumn('dinar_calculator_records', 'country_final_rate')) {
                $table->decimal('country_final_rate', 18, 4)->nullable()->default(0)->after('country_op');
            }
            if (Schema::hasColumn('dinar_calculator_records', 'amount')) {
                $table->decimal('amount', 18, 4)->nullable()->default(0)->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dinar_calculator_records', function (Blueprint $table) {
            if (Schema::hasColumn('dinar_calculator_records', 'country_final_rate')) {
                $table->dropColumn('country_final_rate');
            }
            if (Schema::hasColumn('dinar_calculator_records', 'country_op')) {
                $table->dropColumn('country_op');
            }
        });
    }
};
