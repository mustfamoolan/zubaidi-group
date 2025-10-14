<?php

namespace App\Console\Commands;

use App\Models\Shipment;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Console\Command;
use Carbon\Carbon;

class TestShipmentNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shipments:test-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'اختبار نظام الإشعارات - يفحص جميع الشحنات بغض النظر عن التاريخ';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 بدء اختبار نظام الإشعارات...');
        $this->newLine();

        // عرض إحصائيات الشحنات
        $totalShipments = Shipment::count();
        $this->info("📦 إجمالي الشحنات: {$totalShipments}");

        if ($totalShipments === 0) {
            $this->warn('⚠️  لا توجد شحنات في النظام!');
            $this->info('💡 قم بإنشاء شحنة أولاً من خلال الواجهة.');
            return Command::SUCCESS;
        }

        $this->newLine();
        $this->info('📊 تحليل الشحنات:');
        $this->newLine();

        // فحص الشحنات التي مر عليها 15 يوم أو أكثر
        $fifteenDaysAgo = Carbon::now()->subDays(15);
        $oldShipments15 = Shipment::where('shipping_date', '<=', $fifteenDaysAgo)
            ->where('received_status', 'not_received')
            ->whereNull('received_date')
            ->get();

        $this->line("🕐 شحنات مر عليها 15+ يوم (حالة الاستلام غير محدثة): {$oldShipments15->count()}");

        // فحص الشحنات التي مر عليها 30 يوم أو أكثر (حالة الدخول)
        $thirtyDaysAgo = Carbon::now()->subDays(30);
        $oldShipments30Entry = Shipment::where('shipping_date', '<=', $thirtyDaysAgo)
            ->where('entry_status', 'not_entered')
            ->whereNull('entry_date')
            ->get();

        $this->line("🕐 شحنات مر عليها 30+ يوم (حالة الدخول غير محدثة): {$oldShipments30Entry->count()}");

        // فحص الشحنات التي مر عليها 30 يوم أو أكثر (تصريح الدخول)
        $oldShipments30Permit = Shipment::where('shipping_date', '<=', $thirtyDaysAgo)
            ->where('entry_permit_status', 'not_received')
            ->whereNull('entry_permit_date')
            ->get();

        $this->line("🕐 شحنات مر عليها 30+ يوم (تصريح الدخول غير محدث): {$oldShipments30Permit->count()}");

        $this->newLine();

        // عرض تفاصيل كل شحنة
        $allShipments = Shipment::with('company')->orderBy('shipping_date', 'desc')->get();

        $this->info('📋 تفاصيل الشحنات:');
        $this->newLine();

        foreach ($allShipments as $shipment) {
            $daysOld = Carbon::parse($shipment->shipping_date)->diffInDays(Carbon::now());

            $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            $this->line("📦 الشحنة: {$shipment->container_number}");
            $this->line("🏢 الشركة: {$shipment->company->name}");
            $this->line("📅 تاريخ الشحن: {$shipment->shipping_date->format('Y-m-d')} (قبل {$daysOld} يوم)");
            $this->line("📍 حالة الاستلام: " . ($shipment->received_status === 'received' ? '✅ مستلمة' : '❌ غير مستلمة'));
            $this->line("📍 حالة الدخول: " . ($shipment->entry_status === 'entered' ? '✅ داخلة' : '❌ غير داخلة'));
            $this->line("📍 تصريح الدخول: " . ($shipment->entry_permit_status === 'received' ? '✅ مستلم' : '❌ غير مستلم'));

            // تحديد ما إذا كانت تحتاج إشعار
            $needsNotification = [];
            if ($daysOld >= 15 && $shipment->received_status === 'not_received' && !$shipment->received_date) {
                $needsNotification[] = '🔔 تحتاج إشعار: حالة الاستلام (15+ يوم)';
            }
            if ($daysOld >= 30 && $shipment->entry_status === 'not_entered' && !$shipment->entry_date) {
                $needsNotification[] = '🔔 تحتاج إشعار: حالة الدخول (30+ يوم)';
            }
            if ($daysOld >= 30 && $shipment->entry_permit_status === 'not_received' && !$shipment->entry_permit_date) {
                $needsNotification[] = '🔔 تحتاج إشعار: تصريح الدخول (30+ يوم)';
            }

            if (count($needsNotification) > 0) {
                $this->newLine();
                foreach ($needsNotification as $notification) {
                    $this->warn($notification);
                }
            } else {
                $this->newLine();
                $this->info('✅ لا تحتاج إشعارات (محدثة أو لم يمر الوقت الكافي)');
            }
        }

        $this->newLine();
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->newLine();

        // سؤال المستخدم إذا أراد إنشاء إشعارات تجريبية
        if ($this->confirm('هل تريد إنشاء إشعارات تجريبية لجميع الشحنات القديمة؟', false)) {
            $this->newLine();
            $this->info('🚀 إنشاء إشعارات تجريبية...');
            $this->newLine();

            $count = 0;

            // إشعارات حالة الاستلام
            foreach ($oldShipments15 as $shipment) {
                $users = User::where('company_id', $shipment->company_id)->get();
                foreach ($users as $user) {
                    Notification::create([
                        'user_id' => $user->id,
                        'notifiable_id' => $shipment->id,
                        'notifiable_type' => Shipment::class,
                        'type' => 'received_reminder',
                        'message' => "تنبيه اختبار: مر {$shipment->shipping_date->diffInDays()} يوم على شحن الحاوية {$shipment->container_number}. يرجى تحديث حالة الاستلام.",
                    ]);
                    $count++;
                }
            }

            // إشعارات حالة الدخول
            foreach ($oldShipments30Entry as $shipment) {
                $users = User::where('company_id', $shipment->company_id)->get();
                foreach ($users as $user) {
                    Notification::create([
                        'user_id' => $user->id,
                        'notifiable_id' => $shipment->id,
                        'notifiable_type' => Shipment::class,
                        'type' => 'entry_reminder',
                        'message' => "تنبيه اختبار: مر {$shipment->shipping_date->diffInDays()} يوم على شحن الحاوية {$shipment->container_number}. يرجى تحديث حالة الدخول.",
                    ]);
                    $count++;
                }
            }

            // إشعارات تصريح الدخول
            foreach ($oldShipments30Permit as $shipment) {
                $users = User::where('company_id', $shipment->company_id)->get();
                foreach ($users as $user) {
                    Notification::create([
                        'user_id' => $user->id,
                        'notifiable_id' => $shipment->id,
                        'notifiable_type' => Shipment::class,
                        'type' => 'entry_permit_reminder',
                        'message' => "تنبيه اختبار: مر {$shipment->shipping_date->diffInDays()} يوم على شحن الحاوية {$shipment->container_number}. يرجى تحديث حالة تصريح الدخول.",
                    ]);
                    $count++;
                }
            }

            $this->info("✅ تم إنشاء {$count} إشعار تجريبي!");
            $this->info("💡 تحقق من صفحة الإشعارات أو أيقونة الجرس في الهيدر.");
        }

        $this->newLine();
        $this->info('✅ انتهى الاختبار!');

        return Command::SUCCESS;
    }
}
