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
        Schema::create('dinar_calculator_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->string('transaction_no')->nullable(); // رقم الحركة
            $table->string('currency', 10)->default('USD'); // USD, AED, RMB
            $table->decimal('amount', 18, 4)->default(0); // مبلغ الحركة
            $table->decimal('exchange_rate', 18, 4)->default(0); // سعر الصرف الرئيسي
            $table->decimal('country_rate', 18, 4)->nullable(); // ضرب سعر الصرف بالبلد (AED / RMB)
            $table->decimal('iqd_base', 18, 4)->default(0); // مبلغ عراقي
            $table->decimal('commission_rate', 10, 4)->default(0); // نسبة العمولة
            $table->string('commission_type', 20)->default('per_thousand'); // per_thousand / percent
            $table->decimal('commission_iqd', 18, 4)->default(0); // مبلغ العمولة
            $table->decimal('customs_rate', 10, 4)->default(0); // نسبة كمرك + ضريبة
            $table->string('customs_type', 20)->default('percent'); // percent / per_thousand
            $table->decimal('customs_iqd', 18, 4)->default(0); // مبلغ الكمرك
            $table->decimal('total_iqd', 18, 4)->default(0); // المجموع عراقي
            $table->text('notes')->nullable(); // البيان / ملاحظات
            $table->integer('sort_order')->default(0); // ترتيب الحركة في الجدول
            $table->timestamps();

            $table->index('user_id');
            $table->index('company_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dinar_calculator_records');
    }
};
