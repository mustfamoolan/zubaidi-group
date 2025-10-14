# دليل نظام الإشعارات والتنبيهات

## نظرة عامة

تم تطوير نظام إشعارات تلقائي لمتابعة حالة الشحنات في نظام Al-Zubaidi Group. يقوم النظام بإرسال تنبيهات تلقائية بعد فترات محددة من تاريخ الشحن.

## آلية عمل النظام

### 1. التنبيه الأول: حالة الاستلام (بعد 15 يوم)

- **المدة:** بعد 15 يوم من تاريخ الشحن
- **الهدف:** التحقق من استلام الشحنة
- **الإجراء التلقائي:**
  - يتم فحص جميع الشحنات التي مر عليها 15 يوم بالضبط
  - إذا كانت حالة الاستلام لم تُحدّث (لا تزال "غير مستلمة")
  - يتم إنشاء إشعار لجميع مستخدمي الشركة
  - يتم إرسال بريد إلكتروني تلقائياً

### 2. التنبيه الثاني: حالة الدخول (بعد 30 يوم)

- **المدة:** بعد 30 يوم من تاريخ الشحن
- **الهدف:** التحقق من دخول الشحنة
- **الإجراء التلقائي:**
  - يتم فحص جميع الشحنات التي مر عليها 30 يوم بالضبط
  - إذا كانت حالة الدخول لم تُحدّث (لا تزال "غير داخلة")
  - يتم إنشاء إشعار لجميع مستخدمي الشركة
  - يتم إرسال بريد إلكتروني تلقائياً

## الأوامر المتاحة

### فحص حالة الاستلام

```bash
php artisan shipments:check-received
```

يقوم هذا الأمر بـ:
- فحص الشحنات التي مر عليها 15 يوم
- إنشاء إشعارات للمستخدمين
- إرسال رسائل بريد إلكتروني

### فحص حالة الدخول

```bash
php artisan shipments:check-entry
```

يقوم هذا الأمر بـ:
- فحص الشحنات التي مر عليها 30 يوم
- إنشاء إشعارات للمستخدمين
- إرسال رسائل بريد إلكتروني

## الجدولة التلقائية

تم جدولة الأوامر للتشغيل تلقائياً كل يوم عند الساعة 9 صباحاً:

```php
// في ملف app/Console/Kernel.php
$schedule->command('shipments:check-received')->dailyAt('09:00');
$schedule->command('shipments:check-entry')->dailyAt('09:00');
```

### تفعيل الجدولة

لتفعيل الجدولة التلقائية، يجب تشغيل أحد الخيارات التالية:

#### الخيار الأول: Cron Job (لخوادم Linux)

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

#### الخيار الثاني: Task Scheduler (لخوادم Windows)

1. افتح Task Scheduler
2. أنشئ مهمة جديدة
3. اجعلها تعمل كل دقيقة
4. الأمر: `php artisan schedule:run`
5. المسار: مجلد المشروع

#### الخيار الثالث: تشغيل يدوي (للتطوير)

```bash
php artisan schedule:work
```

## إدارة الإشعارات

### عرض الإشعارات

- **الرابط:** `/notifications`
- **الوظائف:**
  - عرض جميع الإشعارات
  - تحديد الإشعارات كمقروءة
  - إعادة إرسال البريد الإلكتروني
  - الانتقال إلى تفاصيل الشحنة

### API للإشعارات

#### الحصول على عدد الإشعارات غير المقروءة

```javascript
fetch('/notifications/unread-count')
  .then(response => response.json())
  .then(data => console.log(data.unread_count));
```

#### تحديد إشعار كمقروء

```http
PATCH /notifications/{notification}/mark-as-read
```

#### إعادة إرسال بريد إلكتروني

```http
POST /notifications/{notification}/resend-email
```

## قوالب البريد الإلكتروني

### قالب تنبيه الاستلام

- **الملف:** `resources/views/emails/shipment-received.blade.php`
- **الموضوع:** "تنبيه: حالة استلام الشحنة - Al-Zubaidi Group"

### قالب تنبيه الدخول

- **الملف:** `resources/views/emails/shipment-entry.blade.php`
- **الموضوع:** "تنبيه: حالة دخول الشحنة - Al-Zubaidi Group"

## تحديث حالة الشحنة

عندما يقوم المستخدم بتحديث حالة الاستلام أو الدخول، يتم تلقائياً:

1. تحديث تاريخ آخر تعديل
2. إنشاء إشعار للمستخدم الذي قام بالتحديث
3. إرسال بريد إلكتروني تأكيدي

## إعدادات البريد الإلكتروني

تأكد من تكوين إعدادات البريد الإلكتروني في ملف `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@alzubaidigroup.com
MAIL_FROM_NAME="${APP_NAME}"
```

## الاختبار

### اختبار الأمر يدوياً

```bash
# اختبار فحص الاستلام
php artisan shipments:check-received

# اختبار فحص الدخول
php artisan shipments:check-entry
```

### اختبار البريد الإلكتروني

يمكنك استخدام [Mailtrap](https://mailtrap.io/) أو [MailHog](https://github.com/mailhog/MailHog) لاختبار إرسال الرسائل في بيئة التطوير.

## الملفات الرئيسية

### النماذج (Models)
- `app/Models/Notification.php` - نموذج الإشعارات
- `app/Models/Shipment.php` - نموذج الشحنات

### وحدات التحكم (Controllers)
- `app/Http/Controllers/NotificationController.php` - إدارة الإشعارات
- `app/Http/Controllers/ShipmentController.php` - إدارة الشحنات

### الأوامر (Commands)
- `app/Console/Commands/CheckShipmentReceivedStatus.php` - فحص حالة الاستلام
- `app/Console/Commands/CheckShipmentEntryStatus.php` - فحص حالة الدخول

### البريد (Mail)
- `app/Mail/ShipmentReceivedMail.php` - رسالة تنبيه الاستلام
- `app/Mail/ShipmentEntryMail.php` - رسالة تنبيه الدخول

### العروض (Views)
- `resources/views/notifications/index.blade.php` - صفحة الإشعارات
- `resources/views/emails/shipment-received.blade.php` - قالب بريد الاستلام
- `resources/views/emails/shipment-entry.blade.php` - قالب بريد الدخول

## الأسئلة الشائعة

### س: كيف يمكنني تغيير وقت إرسال التنبيهات؟

ج: قم بتعديل ملف `app/Console/Kernel.php`:

```php
// للإرسال عند الساعة 10 صباحاً بدلاً من 9
$schedule->command('shipments:check-received')->dailyAt('10:00');
```

### س: هل يمكنني تغيير الفترة من 15 إلى 10 أيام؟

ج: نعم، قم بتعديل ملف `app/Console/Commands/CheckShipmentReceivedStatus.php`:

```php
// من:
$fifteenDaysAgo = Carbon::now()->subDays(15)->startOfDay();

// إلى:
$tenDaysAgo = Carbon::now()->subDays(10)->startOfDay();
```

### س: كيف يمكنني تعطيل إرسال البريد الإلكتروني؟

ج: قم بتعطيل السطر في الأمر:

```php
// comment out this line
// $notification->sendEmail();
```

## الدعم

للحصول على مساعدة إضافية، يرجى مراجعة:
- [Laravel Documentation - Task Scheduling](https://laravel.com/docs/11.x/scheduling)
- [Laravel Documentation - Mail](https://laravel.com/docs/11.x/mail)
- [Laravel Documentation - Notifications](https://laravel.com/docs/11.x/notifications)

