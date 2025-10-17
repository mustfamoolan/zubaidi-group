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
        Schema::table('invoices', function (Blueprint $table) {
            $table->enum('shipping_status', ['shipped', 'not_shipped'])
                  ->default('shipped')
                  ->after('status');
            $table->timestamp('last_shipping_notification_sent_at')
                  ->nullable()
                  ->after('shipping_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['shipping_status', 'last_shipping_notification_sent_at']);
        });
    }
};
