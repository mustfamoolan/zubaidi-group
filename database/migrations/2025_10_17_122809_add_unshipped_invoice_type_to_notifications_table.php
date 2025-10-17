<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // إضافة نوع جديد للإشعارات
        DB::statement("ALTER TABLE notifications MODIFY COLUMN type ENUM('received_reminder', 'entry_reminder', 'entry_permit_reminder', 'unshipped_invoice') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // إرجاع enum للقيم السابقة
        DB::statement("ALTER TABLE notifications MODIFY COLUMN type ENUM('received_reminder', 'entry_reminder', 'entry_permit_reminder') NOT NULL");
    }
};
