<x-mail::message>
# تنبيه: حالة تصريح الدخول للشحنة

مرحباً {{ $user->name }},

{{ $notification->message }}

## تفاصيل الشحنة:
- **رقم الحاوية:** {{ $shipment->container_number }}
- **رقم البوليصة:** {{ $shipment->policy_number }}
- **تاريخ الشحن:** {{ $shipment->shipping_date->format('d M Y') }}
- **حالة تصريح الدخول:** {{ $shipment->entry_permit_status === 'received' ? 'مستلم ✅' : 'غير مستلم ❌' }}

@if($shipment->entry_permit_date)
**تاريخ آخر تحديث:** {{ $shipment->entry_permit_date->format('d M Y') }}
@endif

<x-mail::button :url="config('app.url')">
عرض تفاصيل الشحنة
</x-mail::button>

شكراً لاستخدامك نظام Al-Zubaidi Group,<br>
{{ config('app.name') }}
</x-mail::message>
