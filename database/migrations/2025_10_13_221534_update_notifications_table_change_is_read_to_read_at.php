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
        Schema::table('notifications', function (Blueprint $table) {
            // حذف العمود القديم is_read
            $table->dropColumn('is_read');

            // إضافة العمود الجديد read_at
            $table->timestamp('read_at')->nullable()->after('message');

            // تعديل email_sent ليصبح sent_at
            $table->dropColumn('email_sent');
            $table->timestamp('sent_at')->nullable()->after('read_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // إرجاع العمود القديم is_read
            $table->boolean('is_read')->default(false);
            $table->dropColumn('read_at');

            // إرجاع email_sent
            $table->boolean('email_sent')->default(false);
            $table->dropColumn('sent_at');
        });
    }
};
