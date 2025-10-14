<?php

namespace App\Console\Commands;

use App\Models\Shipment;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CheckShipmentReceivedStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shipments:check-received';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'التحقق من الشحنات التي مر عليها 15 يوم وإنشاء تنبيه لحالة الاستلام';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('بدء التحقق من حالة استلام الشحنات...');

        // الحصول على الشحنات التي مر عليها 15 يوم بالضبط وحالة الاستلام غير محدثة
        $fifteenDaysAgo = Carbon::now()->subDays(15)->startOfDay();

        $shipments = Shipment::whereDate('shipping_date', $fifteenDaysAgo)
            ->where('received_status', 'not_received')
            ->whereNull('received_date')
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
                    'type' => 'received_reminder',
                    'message' => "تنبيه: مر 15 يوم على شحن الحاوية {$shipment->container_number}. يرجى تحديث حالة الاستلام.",
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
