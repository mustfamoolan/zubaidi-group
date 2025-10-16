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
            // أولاً، تحديث البيانات الموجودة لتتوافق مع القيم الجديدة
            \DB::statement("UPDATE shipments SET container_size = CASE
                WHEN container_size = '20ft' THEN 'C'
                WHEN container_size = '40ft' THEN 'B'
                WHEN container_size = '40ft_hc' THEN 'M'
                ELSE 'C'
            END");

            // ثم تحديث الحقل ليكون enum
            $table->enum('container_size', ['C', 'B', 'M'])->nullable()->change()->comment('C = 20 قدم, B = 40 قدم, M = 45 قدم');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            // إرجاع البيانات إلى القيم القديمة
            \DB::statement("UPDATE shipments SET container_size = CASE
                WHEN container_size = 'C' THEN '20ft'
                WHEN container_size = 'B' THEN '40ft'
                WHEN container_size = 'M' THEN '40ft_hc'
                ELSE '20ft'
            END");

            // إرجاع الحقل إلى النص
            $table->string('container_size')->nullable()->change();
        });
    }
};
