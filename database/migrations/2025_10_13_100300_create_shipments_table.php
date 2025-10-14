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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['shipped', 'not_shipped'])->default('not_shipped'); // مشحون، غير مشحون
            $table->string('container_number');
            $table->string('policy_number');
            $table->string('invoice_file')->nullable();
            $table->decimal('weight', 10, 2)->nullable();
            $table->string('container_size')->nullable();
            $table->integer('carton_count')->nullable();
            $table->date('shipping_date');
            $table->enum('received_status', ['received', 'not_received'])->default('not_received'); // مستلمة، غير مستلمة
            $table->date('received_date')->nullable();
            $table->enum('entry_status', ['entered', 'not_entered'])->default('not_entered'); // داخلة، غير داخلة
            $table->date('entry_date')->nullable();
            $table->timestamps();

            $table->index('company_id');
            $table->index('shipping_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};

