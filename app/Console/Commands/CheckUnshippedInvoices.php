<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Invoice;
use App\Models\Notification;
use Carbon\Carbon;

class CheckUnshippedInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:check-unshipped';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for unshipped invoices and send notifications every 15 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for unshipped invoices...');

        // جلب الفواتير غير المشحونة
        $unshippedInvoices = Invoice::where('shipping_status', 'not_shipped')
            ->with('company')
            ->get();

        $notificationsCreated = 0;

        foreach ($unshippedInvoices as $invoice) {
            $invoiceDate = Carbon::parse($invoice->invoice_date);
            $daysSinceInvoice = (int) $invoiceDate->diffInDays(now());

            $this->info("Invoice #{$invoice->invoice_number}: {$daysSinceInvoice} days since invoice date");

            // التحقق إذا مر 15 يوم أو أكثر، أو إذا كانت فاتورة جديدة (أقل من يوم)
            if ($daysSinceInvoice >= 15 || $daysSinceInvoice < 1) {
                // التحقق من آخر إشعار تم إرساله
                $lastNotificationSent = $invoice->last_shipping_notification_sent_at;

                // إرسال إشعار إذا لم يتم إرسال إشعار من قبل، أو إذا مر 15 يوم منذ آخر إشعار
                $shouldSendNotification = false;

                if (!$lastNotificationSent) {
                    // لم يتم إرسال إشعار من قبل
                    $shouldSendNotification = true;
                    $this->info("No previous notification sent for invoice #{$invoice->invoice_number}");
                } else {
                    $daysSinceLastNotification = (int) $lastNotificationSent->diffInDays(now());
                    if ($daysSinceLastNotification >= 15) {
                        $shouldSendNotification = true;
                        $this->info("Last notification was {$daysSinceLastNotification} days ago for invoice #{$invoice->invoice_number}");
                    }
                }

                if ($shouldSendNotification) {
                    // إنشاء الإشعار لجميع المستخدمين
                    $users = \App\Models\User::all();

                    if ($users->isNotEmpty()) {
                        foreach ($users as $user) {
                            // رسالة مختلفة للفواتير الجديدة مقابل الفواتير القديمة
                            if ($daysSinceInvoice < 1) {
                                $message = "تم إنشاء فاتورة جديدة غير مشحونة #{$invoice->invoice_number} بتاريخ {$invoiceDate->format('Y-m-d')}";
                            } else {
                                $message = "الفاتورة #{$invoice->invoice_number} مازالت غير مشحونة من تاريخ {$invoiceDate->format('Y-m-d')} ({$daysSinceInvoice} يوم)";
                            }
                            
                            $notification = Notification::create([
                                'user_id' => $user->id,
                                'notifiable_id' => $invoice->id,
                                'notifiable_type' => Invoice::class,
                                'type' => 'unshipped_invoice',
                                'message' => $message,
                            ]);
                        }

                        // تحديث تاريخ آخر إشعار
                        $invoice->update([
                            'last_shipping_notification_sent_at' => now(),
                        ]);

                        $notificationsCreated++;
                        $this->info("Created notification for invoice #{$invoice->invoice_number} ({$daysSinceInvoice} days old) for {$users->count()} users");
                    }
                }
            } else {
                $this->info("Invoice #{$invoice->invoice_number} is only {$daysSinceInvoice} days old, no notification needed yet");
            }
        }

        $this->info("Process completed. {$notificationsCreated} notifications created.");

        return 0;
    }
}
