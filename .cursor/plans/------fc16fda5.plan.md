<!-- fc16fda5-0503-4ea7-aff6-bd2c87220bb4 e7c1dcb1-5ed8-4e7c-bd4a-d4ed71372abf -->
# خطة تطوير نظام إدارة الشركات

## المرحلة الأولى: تطوير قاعدة البيانات

### 1. إنشاء جداول قاعدة البيانات الجديدة

- إنشاء جدول المصارف (banks)
- id
- company_id (علاقة مع جدول الشركات)
- name (اسم المصرف)
- initial_balance (المبلغ الافتتاحي)
- current_balance (الرصيد الحالي)
- currency (العملة)
- timestamps

- إنشاء جدول حركات المصارف (bank_transactions)
- id
- bank_id (علاقة مع جدول المصارف)
- type (نوع العملية: إيداع/سحب/خصم_فاتورة)
- amount (المبلغ)
- description (وصف العملية)
- reference_id (معرف المرجع - يمكن أن يكون معرف فاتورة)
- reference_type (نوع المرجع - مثل "فاتورة" أو "إيداع يدوي" أو "سحب يدوي")
- date (تاريخ العملية)
- timestamps

- إنشاء جدول الفواتير (invoices)
- id
- company_id (علاقة مع جدول الشركات)
- invoice_number (رقم الفاتورة)
- amount (المبلغ)
- bank_id (علاقة مع جدول المصارف)
- date (تاريخ الفاتورة)
- beneficiary_company (الشركة المستفيدة)
- status (حالة الفاتورة: مدفوعة/غير مدفوعة)
- timestamps

- إنشاء جدول الشحنات (shipments)
- id
- company_id (علاقة مع جدول الشركات)
- status (حالة الشحنة: مشحون/غير مشحون)
- container_number (رقم الحاوية)
- policy_number (رقم البوليصة)
- invoice_file (ملف الفاتورة)
- weight (الوزن)
- container_size (حجم الحاوية)
- carton_count (عدد الكارتون)
- shipping_date (تاريخ الشحن)
- received_status (حالة الاستلام: مستلمة/غير مستلمة)
- received_date (تاريخ تحديث حالة الاستلام)
- entry_status (حالة الدخول: داخلة/غير داخلة)
- entry_date (تاريخ تحديث حالة الدخول)
- timestamps

- إنشاء جدول العلاقة بين الشحنات والفواتير (shipment_invoice)
- id
- shipment_id (علاقة مع جدول الشحنات)
- invoice_id (علاقة مع جدول الفواتير)
- timestamps

- إنشاء جدول الإشعارات (notifications)
- id
- user_id (علاقة مع جدول المستخدمين)
- notifiable_id (معرف العنصر المرتبط بالإشعار - مثل معرف شحنة)
- notifiable_type (نوع العنصر المرتبط بالإشعار - مثل "شحنة")
- type (نوع الإشعار: استلام/دخول)
- message (نص الإشعار)
- is_read (هل تم قراءة الإشعار)
- email_sent (هل تم إرسال بريد إلكتروني)
- timestamps

## المرحلة الثانية: تطوير النماذج (Models)

### 1. إنشاء نماذج Eloquent للكيانات الجديدة

- نموذج المصرف (Bank.php)
- علاقة مع الشركة (belongsTo)
- علاقة مع حركات المصرف (hasMany)
- علاقة مع الفواتير (hasMany)
- دالة لتحديث الرصيد الحالي (updateBalance)
- دالة لإضافة عملية إيداع (deposit)
- دالة لإضافة عملية سحب (withdraw)
- دالة لخصم مبلغ فاتورة (deductInvoice)

- نموذج حركات المصرف (BankTransaction.php)
- علاقة مع المصرف (belongsTo)
- علاقة متعددة الأنواع للمرجع (morphTo)

- نموذج الفاتورة (Invoice.php)
- علاقة مع الشركة (belongsTo)
- علاقة مع المصرف (belongsTo)
- علاقة مع الشحنات (belongsToMany)
- علاقة متعددة الأنواع للحركات (morphMany)
- دالة لتسجيل الفاتورة وخصم المبلغ من المصرف (processPayment)

- نموذج الشحنة (Shipment.php)
- علاقة مع الشركة (belongsTo)
- علاقة مع الفواتير (belongsToMany)
- علاقة متعددة الأنواع للإشعارات (morphMany)
- دالة لتحديث حالة الاستلام وإنشاء إشعار (updateReceivedStatus)
- دالة لتحديث حالة الدخول وإنشاء إشعار (updateEntryStatus)

- نموذج الإشعار (Notification.php)
- علاقة مع المستخدم (belongsTo)
- علاقة متعددة الأنواع للعنصر المرتبط (morphTo)
- دالة لإرسال بريد إلكتروني (sendEmail)

### 2. تحديث النماذج الحالية

- تحديث نموذج الشركة (Company.php)
- إضافة علاقات مع المصارف والفواتير والشحنات

## المرحلة الثالثة: تطوير وحدات التحكم (Controllers)

### 1. إنشاء وحدات تحكم جديدة

- وحدة تحكم المصارف (BankController.php)
- عرض قائمة مصارف الشركة
- إنشاء مصرف جديد
- عرض تفاصيل المصرف
- تعديل بيانات المصرف
- حذف المصرف
- إضافة عملية إيداع
- إضافة عملية سحب
- عرض سجل الحركات

- وحدة تحكم الفواتير (InvoiceController.php)
- عرض قائمة فواتير الشركة
- إنشاء فاتورة جديدة
- عرض تفاصيل الفاتورة
- تعديل بيانات الفاتورة
- حذف الفاتورة
- ربط الفاتورة بشحنة أو أكثر
- تسجيل دفع الفاتورة وخصم المبلغ من المصرف

- وحدة تحكم الشحنات (ShipmentController.php)
- عرض قائمة شحنات الشركة
- إنشاء شحنة جديدة
- عرض تفاصيل الشحنة
- تعديل بيانات الشحنة
- حذف الشحنة
- تحديث حالة الاستلام
- تحديث حالة الدخول
- ربط الشحنة بفاتورة أو أكثر

- وحدة تحكم الإشعارات (NotificationController.php)
- عرض قائمة الإشعارات
- تحديث حالة قراءة الإشعار
- إعادة إرسال الإشعار بالبريد الإلكتروني

### 2. تحديث وحدة تحكم الشركات (CompanyController.php)

- تعديل دالة show لتوجيه المستخدم إلى لوحة تحكم الشركة بدلاً من الصفحة الرئيسية

## المرحلة الرابعة: تطوير الطرق (Routes)

### 1. إضافة طرق جديدة في ملف web.php

- طرق المصارف
- Route::resource('companies.banks', BankController::class);
- Route::post('companies/{company}/banks/{bank}/deposit', [BankController::class, 'deposit'])->name('companies.banks.deposit');
- Route::post('companies/{company}/banks/{bank}/withdraw', [BankController::class, 'withdraw'])->name('companies.banks.withdraw');

- طرق الفواتير
- Route::resource('companies.invoices', InvoiceController::class);
- Route::post('companies/{company}/invoices/{invoice}/pay', [InvoiceController::class, 'processPayment'])->name('companies.invoices.pay');
- Route::post('companies/{company}/invoices/{invoice}/attach-shipment', [InvoiceController::class, 'attachShipment'])->name('companies.invoices.attach-shipment');

- طرق الشحنات
- Route::resource('companies.shipments', ShipmentController::class);
- Route::patch('companies/{company}/shipments/{shipment}/update-received-status', [ShipmentController::class, 'updateReceivedStatus'])->name('companies.shipments.update-received-status');
- Route::patch('companies/{company}/shipments/{shipment}/update-entry-status', [ShipmentController::class, 'updateEntryStatus'])->name('companies.shipments.update-entry-status');
- Route::post('companies/{company}/shipments/{shipment}/attach-invoice', [ShipmentController::class, 'attachInvoice'])->name('companies.shipments.attach-invoice');

- طرق الإشعارات
- Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
- Route::patch('notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
- Route::post('notifications/{notification}/resend-email', [NotificationController::class, 'resendEmail'])->name('notifications.resend-email');

## المرحلة الخامسة: تطوير واجهة المستخدم

### 1. تحديث السايدبار المخصص

- إضافة أقسام جديدة للمصارف والفواتير والشحنات في السايدبار
- تحديث ملف custom-sidebar.blade.php لإضافة الأقسام الجديدة

### 2. إنشاء صفحة لوحة تحكم الشركة

- إنشاء صفحة dashboard.blade.php خاصة بالشركة
- عرض ملخص للمصارف والفواتير والشحنات
- عرض الإشعارات الأخيرة

### 3. إنشاء صفحات المصارف

- صفحة قائمة المصارف
- نموذج إنشاء مصرف جديد
- صفحة تفاصيل المصرف وسجل الحركات
- نموذج إضافة عملية إيداع/سحب

### 4. إنشاء صفحات الفواتير

- صفحة قائمة الفواتير
- نموذج إنشاء فاتورة جديدة
- صفحة تفاصيل الفاتورة
- نموذج ربط الفاتورة بالشحنات

### 5. إنشاء صفحات الشحنات

- صفحة قائمة الشحنات
- نموذج إنشاء شحنة جديدة
- صفحة تفاصيل الشحنة
- نموذج تحديث حالة الاستلام/الدخول
- نموذج ربط الشحنة بالفواتير

### 6. إنشاء نظام الإشعارات

- إضافة مؤشر الإشعارات في الهيدر
- صفحة قائمة الإشعارات
- تحديث ملف header.blade.php لإضافة مؤشر الإشعارات

## المرحلة السادسة: تطوير نظام التنبيهات والبريد الإلكتروني

### 1. إنشاء قوالب البريد الإلكتروني

- إنشاء قالب بريد إلكتروني لتنبيه حالة الاستلام
- إنشاء قالب بريد إلكتروني لتنبيه حالة الدخول

### 2. إنشاء فئات البريد الإلكتروني

- إنشاء فئة ShipmentReceivedMail.php
- إنشاء فئة ShipmentEntryMail.php

### 3. إنشاء وظيفة مجدولة (Scheduled Command)

- إنشاء أمر للتحقق من الشحنات التي مر عليها 15 يوم لإنشاء تنبيه حالة الاستلام
- إنشاء أمر للتحقق من الشحنات التي مر عليها 30 يوم لإنشاء تنبيه حالة الدخول
- إضافة وظيفة إرسال بريد إلكتروني عند إنشاء التنبيهات

### 4. تكوين جدولة الأوامر

- تحديث ملف Kernel.php لتشغيل الأوامر بشكل يومي

## المرحلة السابعة: الاختبار والتحسينات

### 1. اختبار الوظائف

- اختبار إدارة المصارف وتحديث الرصيد التلقائي
- اختبار إدارة الفواتير وربطها بالشحنات
- اختبار إدارة الشحنات وربطها بالفواتير
- اختبار نظام التنبيهات والبريد الإلكتروني

### 2. تحسينات الأداء

- إضافة فهارس لقاعدة البيانات
- تحسين استعلامات قاعدة البيانات

### 3. تحسينات واجهة المستخدم

- إضافة تأكيدات للعمليات المهمة
- تحسين تجربة المستخدم في التنقل بين صفحات الشركة
- إضافة لوحات معلومات تفاعلية (dashboards) لعرض البيانات

### To-dos

- [ ] فهم هيكل المشروع الحالي وتحديد الميزات المنفذة
- [ ] تحديد الميزات غير المكتملة والثغرات في التنفيذ الحالي
- [ ] مناقشة المتطلبات الإضافية مع العميل
- [ ] إنشاء خطة تفصيلية للتطوير بناءً على المتطلبات