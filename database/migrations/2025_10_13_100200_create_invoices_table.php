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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('invoice_number')->unique();
            $table->decimal('amount', 15, 2);
            $table->foreignId('bank_id')->nullable()->constrained()->onDelete('set null');
            $table->date('invoice_date');
            $table->string('beneficiary_company');
            $table->enum('status', ['paid', 'unpaid'])->default('unpaid'); // مدفوعة، غير مدفوعة
            $table->timestamps();

            $table->index('company_id');
            $table->index('bank_id');
            $table->index('invoice_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};

