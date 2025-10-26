/**
 * Number Formatter - إضافة فواصل الآلاف للأرقام
 * يساعد في تحسين قابلية القراءة وتجنب الأخطاء في إدخال الأرقام
 */

// دالة لإضافة فواصل الآلاف للرقم
function addThousandSeparator(value) {
    if (!value) return '';

    // إزالة أي فواصل موجودة أولاً
    let cleanValue = value.toString().replace(/,/g, '');

    // التحقق من أن القيمة رقمية
    if (isNaN(cleanValue)) return value;

    // إضافة الفواصل كل 3 أرقام
    return cleanValue.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

// دالة لإزالة فواصل الآلاف من الرقم
function removeThousandSeparator(value) {
    if (!value) return '';
    return value.toString().replace(/,/g, '');
}

// دالة لتطبيق الفواصل على حقل إدخال
function formatNumberInput(input) {
    if (!input) return;

    input.addEventListener('input', function(e) {
        let cursorPosition = e.target.selectionStart;
        let oldValue = e.target.value;
        let newValue = addThousandSeparator(oldValue);

        // تحديث القيمة
        e.target.value = newValue;

        // إعادة وضع المؤشر في المكان الصحيح
        let lengthDiff = newValue.length - oldValue.length;
        let newCursorPosition = cursorPosition + lengthDiff;
        e.target.setSelectionRange(newCursorPosition, newCursorPosition);
    });
}

// دالة لتطبيق الفواصل على جميع الحقول التي تحتوي على class معين
function initializeNumberFormatters(className = 'number-input') {
    const inputs = document.querySelectorAll(`input.${className}`);
    inputs.forEach(input => {
        formatNumberInput(input);
    });
}

// دالة لإزالة الفواصل من جميع الحقول قبل إرسال النموذج
function removeFormattersFromForm(form) {
    if (!form) return;

    const numberInputs = form.querySelectorAll('input.number-input');
    numberInputs.forEach(input => {
        input.value = removeThousandSeparator(input.value);
    });
}

// دالة للحصول على القيمة الرقمية من حقل مع فواصل
function getNumericValue(input) {
    if (!input) return 0;
    return parseFloat(removeThousandSeparator(input.value)) || 0;
}

// تطبيق تلقائي عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    // تطبيق الفواصل على جميع الحقول
    initializeNumberFormatters();

    // إزالة الفواصل قبل إرسال أي نموذج
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            removeFormattersFromForm(this);
        });
    });
});

// تصدير الدوال للاستخدام الخارجي
window.NumberFormatter = {
    addThousandSeparator,
    removeThousandSeparator,
    formatNumberInput,
    initializeNumberFormatters,
    removeFormattersFromForm,
    getNumericValue
};
