# نظام إدارة المبالغ والحركات المصرفية المتقدم

## نظرة عامة

تم تطوير نظام متقدم لإدارة المبالغ والحركات المصرفية في نظام إدارة الفواتير. هذا النظام يدعم جميع سيناريوهات التعديل والحذف مع تتبع دقيق للحركات المصرفية.

## الميزات الرئيسية

### 1. حساب المبلغ النهائي
- **المعادلة**: `total_amount_iqd = (amount_usd * exchange_rate) + bank_commission`
- **المبلغ المستخدم**: المبلغ النهائي بالدينار العراقي (بعد تحويل العملة + العمولة)

### 2. السيناريوهات المدعومة

#### السيناريو 1: تغيير حالة الدفع فقط
- **من مدفوعة إلى غير مدفوعة**: إرجاع المبلغ للمصرف
- **من غير مدفوعة إلى مدفوعة**: خصم المبلغ من المصرف

#### السيناريو 2: تغيير المبلغ فقط
- **زيادة المبلغ**: خصم الفرق من المصرف
- **تقليل المبلغ**: إرجاع الفرق للمصرف

#### السيناريو 3: تغيير المصرف فقط
- إرجاع المبلغ من المصرف القديم
- خصم المبلغ من المصرف الجديد

#### السيناريو 4: تغيير المصرف والمبلغ
- إرجاع المبلغ القديم من المصرف القديم
- خصم المبلغ الجديد من المصرف الجديد

#### السيناريو 5: تغيير الحالة والمبلغ
- إدارة متقدمة للتغييرات المركبة

#### السيناريو 6: تغيير المصرف والحالة
- إدارة متقدمة للتغييرات المركبة

#### السيناريو 7: تغيير كل شيء
- إدارة شاملة لجميع التغييرات

#### السيناريو 8: الدفع الأول
- خصم المبلغ عند تغيير الحالة من غير مدفوعة إلى مدفوعة

### 3. حذف الفاتورة
- إرجاع المبلغ للمصرف إذا كانت مدفوعة
- حذف جميع الحركات المرتبطة

## البنية التقنية

### Bank Model Methods

#### Methods الأساسية
```php
// خصم فاتورة مع تفاصيل
public function deductInvoiceWithDetails(Invoice $invoice, $reason = 'دفع الفاتورة')

// إرجاع فاتورة مع تفاصيل
public function refundInvoiceWithDetails(Invoice $invoice, $reason = 'تعديل الفاتورة')

// تغيير حالة الفاتورة إلى غير مدفوعة
public function changeInvoiceStatusToUnpaid(Invoice $invoice)

// تغيير حالة الفاتورة إلى مدفوعة
public function changeInvoiceStatusToPaid(Invoice $invoice)

// تغيير مبلغ الفاتورة
public function changeInvoiceAmount(Invoice $invoice, $oldAmount, $newAmount, $reason = 'تعديل المبلغ')

// تغيير مصرف الفاتورة
public function changeInvoiceBank(Invoice $invoice, Bank $oldBank, Bank $newBank)

// حذف فاتورة
public function deleteInvoice(Invoice $invoice)
```

### InvoiceController Methods

#### Method الرئيسي
```php
// إدارة الحركات المصرفية المتقدمة
private function handleAdvancedBankTransactions($invoice, $oldBankId, $oldAmount, $oldStatus, $newBankId, $newAmount, $newStatus)
```

#### Methods المساعدة
```php
// التعامل مع تغيير حالة الدفع فقط
private function handleStatusChange($invoice, $oldStatus, $newStatus, $bankId)

// التعامل مع تغيير المبلغ فقط
private function handleAmountChange($invoice, $oldAmount, $newAmount, $bankId)

// التعامل مع تغيير المصرف فقط
private function handleBankChange($invoice, $oldBankId, $newBankId)

// التعامل مع تغيير المصرف والمبلغ
private function handleBankAndAmountChange($invoice, $oldBankId, $newBankId, $oldAmount, $newAmount)

// التعامل مع تغيير الحالة والمبلغ
private function handleStatusAndAmountChange($invoice, $oldStatus, $newStatus, $oldAmount, $newAmount, $bankId)

// التعامل مع تغيير المصرف والحالة
private function handleBankAndStatusChange($invoice, $oldBankId, $newBankId, $oldStatus, $newStatus)

// التعامل مع تغيير كل شيء
private function handleCompleteChange($invoice, $oldBankId, $newBankId, $oldStatus, $newStatus, $oldAmount, $newAmount)

// التعامل مع الدفع الأول للفاتورة
private function handleFirstPayment($invoice, $bankId)
```

## تتبع الحركات المصرفية

### أنواع الحركات
1. **deposit**: إيداع (إرجاع مبلغ فاتورة)
2. **withdrawal**: سحب عادي
3. **invoice_deduction**: خصم فاتورة

### معلومات الحركة
- **النوع**: نوع الحركة
- **المبلغ**: المبلغ بالدينار العراقي
- **الوصف**: وصف تفصيلي مع السبب
- **المرجع**: ربط بالفاتورة المرتبطة
- **التاريخ**: تاريخ الفاتورة

### أمثلة على الأوصاف
- "خصم فاتورة رقم INV-001 - شركة ABC (دفع الفاتورة)"
- "إرجاع فاتورة رقم INV-001 - شركة ABC (تغيير الحالة إلى غير مدفوعة)"
- "زيادة مبلغ فاتورة رقم INV-001 - شركة ABC (تعديل المبلغ)"
- "نقل الفاتورة إلى مصرف آخر"

## الفوائد

### 1. دقة في التتبع
- كل حركة لها سبب واضح
- تتبع كامل لتاريخ التغييرات
- ربط مباشر بالفاتورة

### 2. مرونة في التعديل
- دعم جميع سيناريوهات التعديل
- معالجة ذكية للتغييرات المركبة
- عدم فقدان أي حركة

### 3. أمان في المعاملات
- التحقق من وجود المصارف
- معالجة الأخطاء
- ضمان صحة الرصيد

### 4. سهولة الصيانة
- كود منظم ومقسم
- methods متخصصة لكل سيناريو
- سهولة إضافة سيناريوهات جديدة

## الاختبار

### سيناريوهات الاختبار المطلوبة
1. ✅ تغيير حالة الدفع فقط
2. ✅ تغيير المبلغ فقط
3. ✅ تغيير المصرف فقط
4. ✅ تغيير المصرف والمبلغ
5. ✅ تغيير الحالة والمبلغ
6. ✅ تغيير المصرف والحالة
7. ✅ تغيير كل شيء
8. ✅ الدفع الأول
9. ✅ حذف الفاتورة

### التحقق من النتائج
- صحة الرصيد في المصارف
- وجود الحركات الصحيحة
- صحة الأوصاف والمراجع
- عدم فقدان أي مبلغ

## الخلاصة

تم تطوير نظام متقدم وشامل لإدارة المبالغ والحركات المصرفية يدعم جميع سيناريوهات التعديل والحذف مع تتبع دقيق ومرن. النظام يضمن دقة المعاملات المالية وسهولة الصيانة والتطوير المستقبلي.
