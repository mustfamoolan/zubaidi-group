<x-mail::message>
# تنبيه: حالة دخول الشحنة

مرحباً {{ $user->name }},

{{ $notification->message }}

## تفاصيل الشحنة:
- **رقم الحاوية:** {{ $shipment->container_number }}
- **رقم البوليصة:** {{ $shipment->policy_number }}
- **تاريخ الشحن:** {{ $shipment->shipping_date->format('d M Y') }}
- **الحالة الحالية:** {{ $shipment->entry_status === 'entered' ? 'داخلة ✅' : 'غير داخلة ❌' }}

@if($shipment->entry_date)
**تاريخ آخر تحديث:** {{ $shipment->entry_date->format('d M Y') }}
@endif

<x-mail::button :url="config('app.url')">
عرض تفاصيل الشحنة
</x-mail::button>

شكراً لاستخدامك نظام Al-Zubaidi Group,<br>
{{ config('app.name') }}
</x-mail::message>
