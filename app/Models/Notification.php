<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'notifiable_id',
        'notifiable_type',
        'type',
        'message',
        'read_at',
        'sent_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    /**
     * العلاقة مع المستخدم
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * العلاقة متعددة الأنواع للعنصر المرتبط
     */
    public function notifiable()
    {
        return $this->morphTo();
    }

    /**
     * إرسال بريد إلكتروني
     */
    public function sendEmail()
    {
        if ($this->sent_at) {
            return false; // تم إرسال البريد بالفعل
        }

        try {
            // إرسال البريد الإلكتروني
            $mailClass = match($this->type) {
                'received_reminder' => \App\Mail\ShipmentReceivedMail::class,
                'entry_reminder' => \App\Mail\ShipmentEntryMail::class,
                'entry_permit_reminder' => \App\Mail\ShipmentEntryPermitMail::class,
                default => \App\Mail\ShipmentReceivedMail::class,
            };

            Mail::to($this->user->email)->send(new $mailClass($this));

            $this->sent_at = now();
            $this->save();

            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to send notification email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * وضع علامة كمقروء
     */
    public function markAsRead()
    {
        $this->read_at = now();
        $this->save();
    }
}

