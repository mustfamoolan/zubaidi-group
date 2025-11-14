<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Company $company = null)
    {
        $query = Notification::where('user_id', Auth::id());

        // تصفية حسب الشركة إذا تم تمريرها
        if ($company) {
            $query->whereHasMorph('notifiable', [\App\Models\Shipment::class, \App\Models\Invoice::class], function ($q) use ($company) {
                $q->where('company_id', $company->id);
            });
        }

        $notifications = $query->orderBy('created_at', 'desc')->paginate(20);

        // تحميل notifiable أولاً
        $notifications->load('notifiable');

        // ثم تحميل company لكل نوع
        foreach ($notifications as $notification) {
            if ($notification->notifiable) {
                if ($notification->notifiable instanceof \App\Models\Shipment) {
                    $notification->notifiable->load('company');
                } elseif ($notification->notifiable instanceof \App\Models\Invoice) {
                    $notification->notifiable->load('company');
                }
            }
        }

        return view('notifications.index', compact('notifications', 'company'));
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead(Notification $notification)
    {
        // التحقق من أن الإشعار يخص المستخدم الحالي
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->markAsRead();

        return redirect()->back()->with('success', 'تم وضع علامة كمقروء');
    }

    /**
     * Resend notification email.
     */
    public function resendEmail(Notification $notification)
    {
        // التحقق من أن الإشعار يخص المستخدم الحالي
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        if ($notification->sendEmail()) {
            return redirect()->back()->with('success', 'تم إرسال البريد الإلكتروني بنجاح');
        }

        return redirect()->back()->with('error', 'حدث خطأ أثناء إرسال البريد الإلكتروني');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(Company $company = null)
    {
        $query = Notification::where('user_id', Auth::id())
            ->whereNull('read_at');

        // تصفية حسب الشركة إذا تم تمريرها
        if ($company) {
            $query->whereHasMorph('notifiable', [\App\Models\Shipment::class, \App\Models\Invoice::class], function ($q) use ($company) {
                $q->where('company_id', $company->id);
            });
        }

        $query->update(['read_at' => now()]);

        return redirect()->back()->with('success', 'تم تحديد جميع الإشعارات كمقروءة');
    }

    /**
     * Get unread notifications count.
     */
    public function getUnreadCount(Company $company = null)
    {
        $query = Notification::where('user_id', Auth::id())
            ->whereNull('read_at');

        // تصفية حسب الشركة إذا تم تمريرها
        if ($company) {
            $query->whereHasMorph('notifiable', [\App\Models\Shipment::class, \App\Models\Invoice::class], function ($q) use ($company) {
                $q->where('company_id', $company->id);
            });
        }

        $count = $query->count();

        return response()->json(['count' => $count]);
    }
}

