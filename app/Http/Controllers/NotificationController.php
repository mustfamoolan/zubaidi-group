<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->with('notifiable')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
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
    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return redirect()->back()->with('success', 'تم تحديد جميع الإشعارات كمقروءة');
    }

    /**
     * Get unread notifications count.
     */
    public function getUnreadCount()
    {
        $count = Notification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->count();

        return response()->json(['count' => $count]);
    }
}

