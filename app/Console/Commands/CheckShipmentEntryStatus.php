<?php

namespace App\Console\Commands;

use App\Models\Shipment;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CheckShipmentEntryStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shipments:check-entry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'التحقق من الشحنات التي مر عليها 30 يوم وإنشاء تنبيه لحالة الدخول';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('بدء التحقق من حالة دخول الشحنات...');

        // الحصول على الشحنات التي مر عليها 30 يوم بالضبط وحالة الدخول غير محدثة
        $thirtyDaysAgo = Carbon::now()->subDays(30)->startOfDay();

        $shipments = Shipment::whereDate('shipping_date', $thirtyDaysAgo)
            ->where('entry_status', 'not_entered')
            ->whereNull('entry_date')
            ->get();

        $count = 0;

        foreach ($shipments as $shipment) {
            // الحصول على جميع مستخدمي الشركة
            $users = User::where('company_id', $shipment->company_id)->get();

            foreach ($users as $user) {
                // إنشاء إشعار
                $notification = Notification::create([
                    'user_id' => $user->id,
                    'notifiable_id' => $shipment->id,
                    'notifiable_type' => Shipment::class,
                    'type' => 'entry_reminder',
                    'message' => "تنبيه: مر 30 يوم على شحن الحاوية {$shipment->container_number}. يرجى تحديث حالة الدخول.",
                ]);

                // إرسال بريد إلكتروني
                try {
                    $notification->sendEmail();
                    $this->info("✅ تم إرسال إشعار للمستخدم {$user->name} بخصوص الشحنة {$shipment->container_number}");
                } catch (\Exception $e) {
                    $this->error("❌ فشل إرسال البريد للمستخدم {$user->name}: " . $e->getMessage());
                }

                $count++;
            }
        }

        $this->info("✅ تم إنشاء {$count} إشعار لـ " . $shipments->count() . " شحنة");

        return Command::SUCCESS;
    }
}
