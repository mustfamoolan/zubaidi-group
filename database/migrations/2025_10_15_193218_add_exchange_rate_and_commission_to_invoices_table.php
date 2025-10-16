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
            // إضافة الحقول الجديدة للفواتير
            $table->decimal('amount_usd', 15, 2)->nullable()->after('invoice_number')->comment('المبلغ بالدولار');
            $table->decimal('exchange_rate', 10, 2)->nullable()->after('amount_usd')->comment('سعر الصرف');
            $table->decimal('bank_commission', 15, 2)->default(0)->after('exchange_rate')->comment('عمولة المصرف بالدينار العراقي');
            $table->decimal('total_amount_iqd', 15, 2)->nullable()->after('bank_commission')->comment('المبلغ الإجمالي بالدينار العراقي');

            // إضافة فهارس للحقول الجديدة
            $table->index('amount_usd');
            $table->index('total_amount_iqd');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // حذف الحقول المضافة
            $table->dropIndex(['amount_usd']);
            $table->dropIndex(['total_amount_iqd']);

            $table->dropColumn([
                'amount_usd',
                'exchange_rate',
                'bank_commission',
                'total_amount_iqd'
            ]);
        });
    }
};
