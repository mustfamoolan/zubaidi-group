<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // التحقق من حالة الاستلام (بعد 15 يوم) - يتم تشغيله يومياً عند الساعة 9 صباحاً
        $schedule->command('shipments:check-received')->dailyAt('09:00');

        // التحقق من حالة الدخول (بعد 30 يوم) - يتم تشغيله يومياً عند الساعة 9 صباحاً
        $schedule->command('shipments:check-entry')->dailyAt('09:00');

        // التحقق من حالة تصريح الدخول (بعد 30 يوم) - يتم تشغيله يومياً عند الساعة 9 صباحاً
        $schedule->command('shipments:check-entry-permit')->dailyAt('09:00');

        // فحص الفواتير غير المشحونة يومياً في الساعة 9 صباحاً
        $schedule->command('invoices:check-unshipped')
                 ->dailyAt('09:00')
                 ->timezone('Asia/Baghdad');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
