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
        Schema::table('shipments', function (Blueprint $table) {
            // إضافة حقل تصريح حالة الدخول
            $table->enum('entry_permit_status', ['received', 'not_received'])->default('not_received')->after('entry_date');
            $table->date('entry_permit_date')->nullable()->after('entry_permit_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn(['entry_permit_status', 'entry_permit_date']);
        });
    }
};
