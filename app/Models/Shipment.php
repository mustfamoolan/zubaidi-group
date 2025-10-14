<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'status',
        'container_number',
        'policy_number',
        'invoice_file',
        'weight',
        'container_size',
        'carton_count',
        'shipping_date',
        'received_status',
        'received_date',
        'entry_status',
        'entry_date',
        'entry_permit_status',
        'entry_permit_date',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'shipping_date' => 'date',
        'received_date' => 'date',
        'entry_date' => 'date',
        'entry_permit_date' => 'date',
    ];

    /**
     * العلاقة مع الشركة
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * العلاقة مع الفواتير
     */
    public function invoices()
    {
        return $this->belongsToMany(Invoice::class, 'invoice_shipment');
    }

    /**
     * العلاقة متعددة الأنواع للإشعارات
     */
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    /**
     * تحديث حالة الاستلام وإنشاء إشعار
     */
    public function updateReceivedStatus($status, $userId = null)
    {
        $this->received_status = $status;
        $this->received_date = now();
        $this->save();

        // إنشاء إشعار للمستخدم
        if ($userId) {
            $message = $status === 'received'
                ? "تم استلام الشحنة رقم {$this->container_number}"
                : "لم يتم استلام الشحنة رقم {$this->container_number}";

            Notification::create([
                'user_id' => $userId,
                'notifiable_id' => $this->id,
                'notifiable_type' => Shipment::class,
                'type' => 'received_reminder',
                'message' => $message,
            ]);
        }

        return true;
    }

    /**
     * تحديث حالة الدخول وإنشاء إشعار
     */
    public function updateEntryStatus($status, $userId = null)
    {
        $this->entry_status = $status;
        $this->entry_date = now();
        $this->save();

        // إنشاء إشعار للمستخدم
        if ($userId) {
            $message = $status === 'entered'
                ? "تم إدخال الشحنة رقم {$this->container_number}"
                : "لم يتم إدخال الشحنة رقم {$this->container_number}";

            Notification::create([
                'user_id' => $userId,
                'notifiable_id' => $this->id,
                'notifiable_type' => Shipment::class,
                'type' => 'entry_reminder',
                'message' => $message,
            ]);
        }

        return true;
    }

    /**
     * تحديث حالة تصريح الدخول وإنشاء إشعار
     */
    public function updateEntryPermitStatus($status, $userId = null)
    {
        $this->entry_permit_status = $status;
        $this->entry_permit_date = now();
        $this->save();

        // إنشاء إشعار للمستخدم
        if ($userId) {
            $message = $status === 'received'
                ? "تم استلام تصريح الدخول للشحنة رقم {$this->container_number}"
                : "لم يتم استلام تصريح الدخول للشحنة رقم {$this->container_number}";

            Notification::create([
                'user_id' => $userId,
                'notifiable_id' => $this->id,
                'notifiable_type' => Shipment::class,
                'type' => 'entry_permit_reminder',
                'message' => $message,
            ]);
        }

        return true;
    }
}

