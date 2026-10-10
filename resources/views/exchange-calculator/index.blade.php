<x-layout.custom-default>
    <div x-data="exchangeLedger()" x-init="initData()" class="space-y-6">

        <!-- Breadcrumbs & Quick Header Actions -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <ul class="flex space-x-2 rtl:space-x-reverse text-sm mb-2">
                    <li>
                        <a href="{{ route('companies.index') }}" class="text-primary hover:underline">لوحة التحكم</a>
                    </li>
                    <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                        <span class="text-black dark:text-white-light font-medium">حاسبة الصرف وسجل الحسابات (نمط إكسل)</span>
                    </li>
                </ul>
                <div class="flex items-center gap-3">
                    <div class="p-2.5 bg-primary/10 text-primary rounded-xl">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="3" y="3" width="18" height="18" rx="3" stroke="currentColor" stroke-width="1.8"/>
                            <path opacity="0.5" d="M3 9H21" stroke="currentColor" stroke-width="1.8"/>
                            <path opacity="0.5" d="M3 15H21" stroke="currentColor" stroke-width="1.8"/>
                            <path opacity="0.5" d="M9 3V21" stroke="currentColor" stroke-width="1.8"/>
                            <path opacity="0.5" d="M15 9V21" stroke="currentColor" stroke-width="1.8"/>
                            <path d="M5.5 6H6.5M11.5 6H12.5M17.5 6H18.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xl font-bold text-[#0e1726] dark:text-white-light">حاسبة الأسعار وسعر الصرف التفصيلي</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400">جدول حسابات تفاعلي نمط إكسل لحساب تحويلات الدولار/الرممبي ودفع المحلات والمشتريات</p>
                    </div>
                </div>
            </div>

            <!-- Header Action Buttons -->
            <div class="flex items-center flex-wrap gap-2 print:hidden">
                <button type="button" @click="openSavedSheetsModal = true" class="btn btn-outline-primary btn-sm flex items-center gap-1.5 shadow-sm">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 7V17C3 19.2091 4.79086 21 7 21H17C19.2091 21 21 19.2091 21 17V7M3 7C3 4.79086 4.79086 3 7 3H17C19.2091 3 21 4.79086 21 7M3 7H21" stroke="currentColor" stroke-width="1.5"/>
                        <path opacity="0.5" d="M9 11H15M9 15H13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    <span>الأرشيف والكشوفات</span>
                    <span class="badge bg-primary text-white text-xs px-1.5 py-0.5 rounded-full ml-1" x-text="savedSheets.length"></span>
                </button>

                <button type="button" @click="saveCurrentSheet()" class="btn btn-primary btn-sm flex items-center gap-1.5 shadow-sm">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19 21H5C3.89543 21 3 20.1046 3 19V5C3 3.89543 3.89543 3 5 3H16L21 8V19C21 20.1046 20.1046 21 19 21Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path opacity="0.5" d="M17 21V13H7V21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path opacity="0.5" d="M7 3V8H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>حفظ الكشف</span>
                </button>

                <button type="button" @click="exportToExcel()" class="btn btn-outline-success btn-sm flex items-center gap-1.5 shadow-sm">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 16.004V17C4 18.8856 4 19.8284 4.58579 20.4142C5.17157 21 6.11438 21 8 21H16C17.8856 21 18.8284 21 19.4142 20.4142C20 19.8284 20 18.8856 20 17V16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        <path opacity="0.5" d="M12 3V15M12 15L8.5 11.5M12 15L15.5 11.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>تصدير Excel</span>
                </button>

                <button type="button" @click="printSheet()" class="btn btn-outline-secondary btn-sm flex items-center gap-1.5 shadow-sm">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7 8V3H17V8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        <path opacity="0.5" d="M7 16H17V21H7V16Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        <path d="M3 11C3 9.34315 4.34315 8 6 8H18C19.6569 8 21 9.34315 21 11V16C21 17.6569 19.6569 19 18 19H17V16H7V19H6C4.34315 19 3 17.6569 3 16V11Z" stroke="currentColor" stroke-width="1.5"/>
                    </svg>
                    <span>طباعة</span>
                </button>

                <button type="button" @click="createNewSheetPrompt()" class="btn btn-outline-danger btn-sm flex items-center gap-1.5 shadow-sm" title="بدء جدول جديد">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 4V20M20 12H4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>جدول جديد</span>
                </button>
            </div>
        </div>

        <!-- Sheet Metadata Bar (عنوان الكشف، العميل، سعر الصرف الافتراضي) -->
        <div class="panel p-4 shadow-sm border border-gray-200/70 dark:border-white-dark/10">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
                <div class="md:col-span-5">
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">
                        عنوان الكشف / الشيت:
                    </label>
                    <input type="text" x-model="sheetTitle" @input="autoSave()" placeholder="مثال: كشف حساب طلبيات ومشتريات الصين - شحنة رقم 12"
                           class="form-input py-1.5 text-sm font-medium">
                </div>

                <div class="md:col-span-4">
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">
                        اسم العميل / التاجر:
                    </label>
                    <input type="text" x-model="customerName" @input="autoSave()" placeholder="مثال: شركة النور للتجارة العامة"
                           class="form-input py-1.5 text-sm">
                </div>

                <div class="md:col-span-3">
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">
                        سعر الصرف الافتراضي (RMB مقابل $):
                    </label>
                    <div class="relative">
                        <input type="number" step="0.0001" x-model="defaultExchangeRate" @input="onDefaultRateChanged()"
                               class="form-input py-1.5 text-sm ltr:pl-8 rtl:pr-8 font-semibold text-primary">
                        <div class="absolute inset-y-0 ltr:left-2.5 rtl:right-2.5 flex items-center pointer-events-none text-xs text-gray-400">
                            ¥
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4 Summary KPI Cards (المجاميع الـ 4 المطابقة لـ D:\ZUPIDY\china) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            <!-- 1. مجموع الدولار ($) -->
            <div class="panel p-4 bg-gradient-to-br from-blue-50 to-indigo-50/50 dark:from-blue-950/20 dark:to-indigo-950/20 border border-blue-200/60 dark:border-blue-900/40 rounded-xl relative overflow-hidden shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-blue-700 dark:text-blue-300">مجموع الدولار المحول</span>
                    <span class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-sm">
                        $
                    </span>
                </div>
                <div class="text-2xl font-extrabold text-blue-800 dark:text-blue-200 tracking-tight" dir="ltr">
                    $ <span x-text="formatNumber(totalUsd, 2)">0.00</span>
                </div>
                <div class="mt-2 text-[11px] text-blue-600/80 dark:text-blue-300/70 flex items-center justify-between">
                    <span>إجمالي مبالغ الحوالات ($)</span>
                    <span class="badge bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200 text-[10px] py-0.5 px-1.5" x-text="items.filter(i => Number(i.usd_amount) > 0).length + ' عمليات'"></span>
                </div>
            </div>

            <!-- 2. مجموع الرممبي (¥) -->
            <div class="panel p-4 bg-gradient-to-br from-amber-50 to-orange-50/50 dark:from-amber-950/20 dark:to-orange-950/20 border border-amber-200/60 dark:border-amber-900/40 rounded-xl relative overflow-hidden shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-amber-700 dark:text-amber-300">مجموع الرممبي المحول</span>
                    <span class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold text-sm">
                        ¥
                    </span>
                </div>
                <div class="text-2xl font-extrabold text-amber-800 dark:text-amber-200 tracking-tight" dir="ltr">
                    ¥ <span x-text="formatNumber(totalRmb, 2)">0.00</span>
                </div>
                <div class="mt-2 text-[11px] text-amber-600/80 dark:text-amber-300/70 flex items-center justify-between">
                    <span>الحصيلة = الدولار × الصرف</span>
                    <span class="text-[10px] font-semibold text-amber-700 dark:text-amber-300">اليوان الصيني</span>
                </div>
            </div>

            <!-- 3. مجموع الدفوعات (¥) -->
            <div class="panel p-4 bg-gradient-to-br from-purple-50 to-fuchsia-50/50 dark:from-purple-950/20 dark:to-fuchsia-950/20 border border-purple-200/60 dark:border-purple-900/40 rounded-xl relative overflow-hidden shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-purple-700 dark:text-purple-300">مجموع الدفوعات</span>
                    <span class="w-8 h-8 rounded-lg bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center font-bold text-sm">
                        💳
                    </span>
                </div>
                <div class="text-2xl font-extrabold text-purple-800 dark:text-purple-200 tracking-tight" dir="ltr">
                    ¥ <span x-text="formatNumber(totalPayments, 2)">0.00</span>
                </div>
                <div class="mt-2 text-[11px] text-purple-600/80 dark:text-purple-300/70 flex items-center justify-between">
                    <span>مشتريات: <strong x-text="formatNumber(totalPurchases, 2)"></strong></span>
                    <span>محلات: <strong x-text="formatNumber(totalStorePayments, 2)"></strong></span>
                </div>
            </div>

            <!-- 4. المجموع الباقي (الرصيد) (¥) -->
            <div class="panel p-4 rounded-xl relative overflow-hidden shadow-sm border transition-all"
                 :class="remainingBalance >= 0 ?
                 'bg-gradient-to-br from-emerald-50 to-teal-50/50 dark:from-emerald-950/20 dark:to-teal-950/20 border-emerald-200/60 dark:border-emerald-900/40' :
                 'bg-gradient-to-br from-rose-50 to-red-50/50 dark:from-rose-950/20 dark:to-red-950/20 border-rose-200/60 dark:border-rose-900/40'">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold"
                          :class="remainingBalance >= 0 ? 'text-emerald-700 dark:text-emerald-300' : 'text-rose-700 dark:text-rose-300'">
                        المجموع الباقي (الرصيد)
                    </span>
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-sm"
                          :class="remainingBalance >= 0 ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-rose-500/10 text-rose-600 dark:text-rose-400'">
                        <span x-show="remainingBalance >= 0">✓</span>
                        <span x-show="remainingBalance < 0">⚠</span>
                    </span>
                </div>
                <div class="text-2xl font-extrabold tracking-tight"
                     :class="remainingBalance >= 0 ? 'text-emerald-800 dark:text-emerald-200' : 'text-rose-800 dark:text-rose-200'" dir="ltr">
                    ¥ <span x-text="formatNumber(remainingBalance, 2)">0.00</span>
                </div>
                <div class="mt-2 text-[11px] flex items-center justify-between"
                     :class="remainingBalance >= 0 ? 'text-emerald-600/80 dark:text-emerald-300/70' : 'text-rose-600/80 dark:text-rose-300/70'">
                    <span>(الرممبي المحول - الدفوعات)</span>
                    <span class="badge text-[10px] py-0.5 px-1.5"
                          :class="remainingBalance >= 0 ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-200' : 'bg-rose-100 text-rose-800 dark:bg-rose-900/50 dark:text-rose-200'"
                          x-text="remainingBalance >= 0 ? 'فائض لصالح العميل' : 'مستحق بذمة العميل'">
                    </span>
                </div>
            </div>

        </div>

        <!-- Excel-Style Quick Entry Row (شريط إدخال الأسطر المباشر - نمط إكسل) -->
        <div class="panel p-4 bg-gray-50/70 dark:bg-[#121e32] border-2 border-primary/30 rounded-xl shadow-sm print:hidden">
            <div class="flex items-center justify-between mb-3 pb-2 border-b border-gray-200/60 dark:border-white-dark/10">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-primary animate-pulse"></span>
                    <h5 class="font-bold text-sm text-[#0e1726] dark:text-white-light">
                        إضافة سطر جديد (إدخال مباشر وفوري نمط إكسل)
                    </h5>
                    <span class="text-xs text-gray-500">اضغط Enter في أي حقل للإضافة السريعة</span>
                </div>

                <!-- شارة الرممبي المحسوب حياً -->
                <div class="flex items-center gap-2 bg-white dark:bg-[#0e1726] border border-primary/40 px-3 py-1 rounded-lg shadow-sm">
                    <span class="text-xs text-gray-500 dark:text-gray-400">الرممبي المحسوب:</span>
                    <span class="text-sm font-extrabold text-primary" dir="ltr">
                        ¥ <span x-text="formatNumber(newCalculatedRmb, 2)">0.00</span>
                    </span>
                </div>
            </div>

            <!-- Input Fields Row -->
            <form @submit.prevent="addNewRow()" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-12 gap-2.5 items-end">
                <!-- 1. تفاصيل (مشتريات) -->
                <div class="md:col-span-3">
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">تفاصيل (المشتريات):</label>
                    <input type="text" x-model="newRow.details" placeholder="مثال: شراء كراتين بضاعة أقمشة"
                           class="form-input py-1.5 text-xs font-medium" autofocus>
                </div>

                <!-- 2. مبلغ المشتريات -->
                <div class="md:col-span-1">
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">مشتريات (¥):</label>
                    <input type="number" step="0.01" x-model="newRow.purchase_amount" placeholder="0.00"
                           class="form-input py-1.5 text-xs font-medium text-left" dir="ltr">
                </div>

                <!-- 3. تفاصيل دفع المحلات -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">تفاصيل دفع المحلات:</label>
                    <input type="text" x-model="newRow.store_payment_details" placeholder="مثال: دفع لمحل الأمل"
                           class="form-input py-1.5 text-xs font-medium">
                </div>

                <!-- 4. دفع محلات -->
                <div class="md:col-span-1">
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">دفع محلات (¥):</label>
                    <input type="number" step="0.01" x-model="newRow.store_payment" placeholder="0.00"
                           class="form-input py-1.5 text-xs font-medium text-left" dir="ltr">
                </div>

                <!-- 5. مبلغ محول دولار ($) -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-blue-600 dark:text-blue-400 mb-1">مبلغ محول دولار ($):</label>
                    <div class="relative">
                        <input type="number" step="0.01" x-model="newRow.usd_amount" placeholder="0.00"
                               class="form-input py-1.5 text-xs font-bold text-blue-600 text-left ltr:pl-5 rtl:pr-5" dir="ltr">
                        <span class="absolute inset-y-0 ltr:left-2 rtl:right-2 flex items-center text-xs text-gray-400">$</span>
                    </div>
                </div>

                <!-- 6. سعر الصرف RMB -->
                <div class="md:col-span-1">
                    <label class="block text-xs font-semibold text-amber-600 dark:text-amber-400 mb-1">سعر الصرف:</label>
                    <input type="number" step="0.0001" x-model="newRow.exchange_rate" placeholder="7.2500"
                           class="form-input py-1.5 text-xs font-bold text-amber-600 text-left" dir="ltr">
                </div>

                <!-- 7. تاريخ التحويل -->
                <div class="md:col-span-1">
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">تاريخ التحويل:</label>
                    <input type="date" x-model="newRow.transfer_date"
                           class="form-input py-1.5 text-xs font-medium">
                </div>

                <!-- Add Button -->
                <div class="md:col-span-1">
                    <button type="submit" class="btn btn-primary w-full py-1.5 text-xs font-bold flex items-center justify-center gap-1 shadow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 4V20M20 12H4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        <span>إضافة</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Main Ledger Table (جدول الحسابات التفصيلي نمط إكسل) -->
        <div class="panel p-0 border border-gray-200/80 dark:border-white-dark/10 rounded-xl overflow-hidden shadow-sm">

            <!-- Table Toolbar -->
            <div class="p-4 bg-gray-50/50 dark:bg-[#121e32]/60 border-b border-gray-200/60 dark:border-white-dark/10 flex flex-wrap items-center justify-between gap-3 print:hidden">
                <div class="flex items-center gap-3">
                    <span class="text-sm font-bold text-[#0e1726] dark:text-white-light">بنود الجدول الحسابي</span>
                    <span class="badge bg-primary/10 text-primary font-bold text-xs px-2 py-0.5" x-text="items.length + ' أسطر'"></span>
                    <span class="text-xs text-gray-500">يمكنك النقر على أي خلية لتعديلها مباشرة</span>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button" @click="addEmptyRow()" class="btn btn-sm btn-outline-primary flex items-center gap-1">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 4V20M20 12H4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>
                        <span>إضافة سطر فارغ</span>
                    </button>

                    <button type="button" @click="loadSampleData()" class="btn btn-sm btn-outline-dark flex items-center gap-1" x-show="items.length === 0">
                        <span>تحميل بيانات تجريبية</span>
                    </button>

                    <button type="button" @click="clearAllPrompt()" class="btn btn-sm btn-outline-danger flex items-center gap-1" x-show="items.length > 0">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M19 7L18.1327 19.1425C18.0579 20.1891 17.187 21 16.1378 21H7.86224C6.81296 21 5.94208 20.1891 5.86732 19.1425L5 7M10 11V17M14 11V17M15 7V4C15 3.44772 14.5523 3 14 3H10C9.44772 3 9 3.44772 9 4V7M4 7H20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>تفريغ الجدول</span>
                    </button>
                </div>
            </div>

            <!-- Table Container -->
            <div class="table-responsive max-h-[600px] overflow-auto">
                <table class="table-striped table-hover w-full text-xs text-right border-collapse select-none">
                    <thead class="sticky top-0 bg-[#f6f8fa] dark:bg-[#0e1726] z-10 border-b border-gray-200 dark:border-white-dark/10 text-gray-700 dark:text-gray-200 uppercase font-bold text-[11px]">
                        <tr>
                            <th class="w-12 text-center py-3 px-2 border-l border-gray-200/50 dark:border-white-dark/10">#</th>
                            <th class="min-w-[200px] py-3 px-3 border-l border-gray-200/50 dark:border-white-dark/10">تفاصيل (المشتريات)</th>
                            <th class="min-w-[130px] py-3 px-3 text-left border-l border-gray-200/50 dark:border-white-dark/10" dir="ltr">مبلغ المشتريات (¥)</th>
                            <th class="min-w-[170px] py-3 px-3 border-l border-gray-200/50 dark:border-white-dark/10">تفاصيل دفع المحلات</th>
                            <th class="min-w-[130px] py-3 px-3 text-left border-l border-gray-200/50 dark:border-white-dark/10" dir="ltr">دفع محلات (¥)</th>
                            <th class="min-w-[140px] py-3 px-3 text-left text-blue-600 dark:text-blue-400 border-l border-gray-200/50 dark:border-white-dark/10" dir="ltr">مبلغ محول دولار ($)</th>
                            <th class="min-w-[110px] py-3 px-3 text-left text-amber-600 dark:text-amber-400 border-l border-gray-200/50 dark:border-white-dark/10" dir="ltr">سعر الصرف RMB</th>
                            <th class="min-w-[150px] py-3 px-3 text-left font-extrabold text-primary border-l border-gray-200/50 dark:border-white-dark/10" dir="ltr">مبلغ محول رممبي (¥)</th>
                            <th class="min-w-[110px] py-3 px-3 text-center border-l border-gray-200/50 dark:border-white-dark/10">تاريخ التحويل</th>
                            <th class="w-24 text-center py-3 px-2 print:hidden">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200/60 dark:divide-white-dark/10 font-medium">
                        <template x-for="(item, index) in items" :key="item.id || index">
                            <tr class="group hover:bg-primary/5 transition-colors">
                                <!-- Index -->
                                <td class="text-center font-bold text-gray-500 py-2.5 px-2 border-l border-gray-200/40 dark:border-white-dark/10" x-text="index + 1"></td>

                                <!-- Details -->
                                <td class="py-2.5 px-3 border-l border-gray-200/40 dark:border-white-dark/10">
                                    <input type="text" x-model="item.details" @input="autoSave()"
                                           class="form-input py-1 px-2 text-xs w-full bg-transparent border-transparent hover:border-gray-300 focus:border-primary focus:bg-white dark:focus:bg-[#0e1726] rounded transition">
                                </td>

                                <!-- Purchase Amount -->
                                <td class="py-2.5 px-3 border-l border-gray-200/40 dark:border-white-dark/10" dir="ltr">
                                    <input type="number" step="0.01" x-model="item.purchase_amount" @input="autoSave()"
                                           class="form-input py-1 px-2 text-xs w-full text-left bg-transparent border-transparent hover:border-gray-300 focus:border-primary focus:bg-white dark:focus:bg-[#0e1726] rounded transition font-medium">
                                </td>

                                <!-- Store Payment Details -->
                                <td class="py-2.5 px-3 border-l border-gray-200/40 dark:border-white-dark/10">
                                    <input type="text" x-model="item.store_payment_details" @input="autoSave()"
                                           class="form-input py-1 px-2 text-xs w-full bg-transparent border-transparent hover:border-gray-300 focus:border-primary focus:bg-white dark:focus:bg-[#0e1726] rounded transition">
                                </td>

                                <!-- Store Payment -->
                                <td class="py-2.5 px-3 border-l border-gray-200/40 dark:border-white-dark/10" dir="ltr">
                                    <input type="number" step="0.01" x-model="item.store_payment" @input="autoSave()"
                                           class="form-input py-1 px-2 text-xs w-full text-left bg-transparent border-transparent hover:border-gray-300 focus:border-primary focus:bg-white dark:focus:bg-[#0e1726] rounded transition font-medium">
                                </td>

                                <!-- USD Amount -->
                                <td class="py-2.5 px-3 border-l border-gray-200/40 dark:border-white-dark/10" dir="ltr">
                                    <div class="relative">
                                        <input type="number" step="0.01" x-model="item.usd_amount" @input="autoSave()"
                                               class="form-input py-1 px-2 text-xs w-full text-left bg-transparent border-transparent hover:border-blue-300 focus:border-blue-500 focus:bg-white dark:focus:bg-[#0e1726] text-blue-600 dark:text-blue-400 font-bold rounded transition">
                                    </div>
                                </td>

                                <!-- Exchange Rate -->
                                <td class="py-2.5 px-3 border-l border-gray-200/40 dark:border-white-dark/10" dir="ltr">
                                    <input type="number" step="0.0001" x-model="item.exchange_rate" @input="autoSave()"
                                           class="form-input py-1 px-2 text-xs w-full text-left bg-transparent border-transparent hover:border-amber-300 focus:border-amber-500 focus:bg-white dark:focus:bg-[#0e1726] text-amber-600 dark:text-amber-400 font-bold rounded transition">
                                </td>

                                <!-- Calculated RMB (المعادلة الحسابية: دولار * سعر الصرف) -->
                                <td class="py-2.5 px-3 border-l border-gray-200/40 dark:border-white-dark/10 bg-primary/[0.02]" dir="ltr">
                                    <div class="font-extrabold text-primary text-xs flex items-center justify-between px-2 py-1 bg-primary/5 rounded">
                                        <span>¥</span>
                                        <span x-text="formatNumber(calculateItemRmb(item), 2)">0.00</span>
                                    </div>
                                </td>

                                <!-- Transfer Date -->
                                <td class="py-2.5 px-3 border-l border-gray-200/40 dark:border-white-dark/10 text-center">
                                    <input type="date" x-model="item.transfer_date" @input="autoSave()"
                                           class="form-input py-1 px-1 text-[11px] text-center w-full bg-transparent border-transparent hover:border-gray-300 focus:border-primary focus:bg-white dark:focus:bg-[#0e1726] rounded transition">
                                </td>

                                <!-- Actions -->
                                <td class="text-center py-2 px-2 print:hidden whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1 opacity-70 group-hover:opacity-100 transition">
                                        <button type="button" @click="openEditRowModal(index)" title="تعديل تفصيلي" class="p-1 hover:text-primary rounded">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M15.2322 5.23223L18.7678 8.76777M16.7322 3.73223C17.7085 2.75592 19.2915 2.75592 20.2678 3.73223C21.2441 4.70854 21.2441 6.29146 20.2678 7.26777L6.5 21.0355H3V17.5L16.7322 3.73223Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </button>

                                        <button type="button" @click="duplicateRow(index)" title="تكرار السطر" class="p-1 hover:text-success rounded">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect x="9" y="9" width="12" height="12" rx="2" stroke="currentColor" stroke-width="1.8"/>
                                                <path opacity="0.5" d="M5 15H4C2.89543 15 2 14.1046 2 13V4C2 2.89543 2.89543 2 4 2H13C14.1046 2 15 2.89543 15 4V5" stroke="currentColor" stroke-width="1.8"/>
                                            </svg>
                                        </button>

                                        <button type="button" @click="deleteRow(index)" title="حذف السطر" class="p-1 hover:text-danger rounded">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M19 7L18.1327 19.1425C18.0579 20.1891 17.187 21 16.1378 21H7.86224C6.81296 21 5.94208 20.1891 5.86732 19.1425L5 7M10 11V17M14 11V17M15 7V4C15 3.44772 14.5523 3 14 3H10C9.44772 3 9 3.44772 9 4V7M4 7H20" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>

                        <!-- Empty State -->
                        <tr x-show="items.length === 0">
                            <td colspan="10" class="text-center py-12 text-gray-500">
                                <div class="flex flex-col items-center justify-center gap-3">
                                    <div class="w-14 h-14 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect x="3" y="3" width="18" height="18" rx="3" stroke="currentColor" stroke-width="1.5"/>
                                            <path opacity="0.5" d="M3 9H21M3 15H21M9 3V21M15 9V21" stroke="currentColor" stroke-width="1.5"/>
                                        </svg>
                                    </div>
                                    <div class="font-bold text-base text-[#0e1726] dark:text-white-light">الجدول فارغ حالياً</div>
                                    <p class="text-xs text-gray-400 max-w-sm">أضف سطراً جديداً باستخدام شريط الإدخال السريع بالأعلى، أو اضغط على الزر أدناه لإضافة بيانات توضيحية فوراً.</p>
                                    <div class="flex gap-2 mt-2">
                                        <button type="button" @click="loadSampleData()" class="btn btn-primary btn-sm">تحميل نموذج تجريبي جاهز</button>
                                        <button type="button" @click="addEmptyRow()" class="btn btn-outline-primary btn-sm">إضافة سطر فارغ</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>

                    <!-- Table Footer: Totals Row (سطر المجاميع الإجمالية المطابق للإكسل) -->
                    <tfoot class="bg-[#eaedf0] dark:bg-[#14233c] font-bold text-xs text-gray-800 dark:text-white-light border-t-2 border-gray-300 dark:border-white-dark/20 sticky bottom-0 z-10">
                        <tr>
                            <td class="text-center py-3 px-2 border-l border-gray-300/60 dark:border-white-dark/20">∑</td>
                            <td class="py-3 px-3 border-l border-gray-300/60 dark:border-white-dark/20">المجاميع الإجمالية</td>

                            <!-- Total Purchases -->
                            <td class="py-3 px-3 text-left border-l border-gray-300/60 dark:border-white-dark/20 font-bold" dir="ltr">
                                ¥ <span x-text="formatNumber(totalPurchases, 2)">0.00</span>
                            </td>

                            <td class="py-3 px-3 border-l border-gray-300/60 dark:border-white-dark/20 text-gray-500 font-normal">إجمالي دفع المحلات:</td>

                            <!-- Total Store Payments -->
                            <td class="py-3 px-3 text-left border-l border-gray-300/60 dark:border-white-dark/20 font-bold" dir="ltr">
                                ¥ <span x-text="formatNumber(totalStorePayments, 2)">0.00</span>
                            </td>

                            <!-- Total USD -->
                            <td class="py-3 px-3 text-left border-l border-gray-300/60 dark:border-white-dark/20 font-extrabold text-blue-600 dark:text-blue-400" dir="ltr">
                                $ <span x-text="formatNumber(totalUsd, 2)">0.00</span>
                            </td>

                            <!-- Average Exchange Rate -->
                            <td class="py-3 px-3 text-left border-l border-gray-300/60 dark:border-white-dark/20 font-bold text-amber-600 dark:text-amber-400" dir="ltr">
                                <span class="text-[10px] text-gray-500 font-normal">متوسط: </span>
                                <span x-text="formatNumber(averageExchangeRate, 4)">0.0000</span>
                            </td>

                            <!-- Total RMB -->
                            <td class="py-3 px-3 text-left border-l border-gray-300/60 dark:border-white-dark/20 font-extrabold text-primary text-sm" dir="ltr">
                                ¥ <span x-text="formatNumber(totalRmb, 2)">0.00</span>
                            </td>

                            <td class="py-3 px-3 text-center border-l border-gray-300/60 dark:border-white-dark/20 text-gray-500 text-[10px]" x-text="items.length + ' بند'"></td>
                            <td class="py-3 px-2 print:hidden"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>

        <!-- Row Edit Modal -->
        <div x-cloak x-show="openEditModal" class="fixed inset-0 bg-[black]/60 z-50 overflow-y-auto flex items-center justify-center p-4">
            <div x-show="openEditModal" x-transition.duration.300ms class="panel p-5 rounded-2xl w-full max-w-lg shadow-2xl border border-gray-200 dark:border-white-dark/10 bg-white dark:bg-[#0e1726]">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-gray-200 dark:border-white-dark/10">
                    <h5 class="font-bold text-base text-[#0e1726] dark:text-white-light flex items-center gap-2">
                        <span>تعديل السطر الحسابي #<span x-text="editingIndex + 1"></span></span>
                    </h5>
                    <button type="button" @click="openEditModal = false" class="text-gray-400 hover:text-gray-700 dark:hover:text-white">
                        ✕
                    </button>
                </div>

                <div class="space-y-3.5 text-xs" x-show="editingRow">
                    <div>
                        <label class="block font-semibold mb-1">تفاصيل (المشتريات):</label>
                        <input type="text" x-model="editingRow.details" class="form-input text-xs">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold mb-1">مبلغ المشتريات (¥):</label>
                            <input type="number" step="0.01" x-model="editingRow.purchase_amount" class="form-input text-xs text-left" dir="ltr">
                        </div>
                        <div>
                            <label class="block font-semibold mb-1">دفع محلات (¥):</label>
                            <input type="number" step="0.01" x-model="editingRow.store_payment" class="form-input text-xs text-left" dir="ltr">
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold mb-1">تفاصيل دفع المحلات:</label>
                        <input type="text" x-model="editingRow.store_payment_details" class="form-input text-xs">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-blue-600 mb-1">مبلغ محول دولار ($):</label>
                            <input type="number" step="0.01" x-model="editingRow.usd_amount" class="form-input text-xs text-left font-bold text-blue-600" dir="ltr">
                        </div>
                        <div>
                            <label class="block font-semibold text-amber-600 mb-1">سعر الصرف RMB:</label>
                            <input type="number" step="0.0001" x-model="editingRow.exchange_rate" class="form-input text-xs text-left font-bold text-amber-600" dir="ltr">
                        </div>
                    </div>

                    <div class="p-3 bg-primary/10 rounded-xl flex items-center justify-between">
                        <span class="font-bold text-gray-700 dark:text-gray-200">الرممبي المحسوب تلقائياً:</span>
                        <span class="font-extrabold text-primary text-base" dir="ltr">
                            ¥ <span x-text="formatNumber((Number(editingRow.usd_amount) || 0) * (Number(editingRow.exchange_rate) || 0), 2)"></span>
                        </span>
                    </div>

                    <div>
                        <label class="block font-semibold mb-1">تاريخ التحويل:</label>
                        <input type="date" x-model="editingRow.transfer_date" class="form-input text-xs">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 mt-5 pt-3 border-t border-gray-200 dark:border-white-dark/10">
                    <button type="button" @click="openEditModal = false" class="btn btn-outline-danger btn-sm">إلغاء</button>
                    <button type="button" @click="saveEditedRow()" class="btn btn-primary btn-sm">حفظ التعديل</button>
                </div>
            </div>
        </div>

        <!-- Saved Sheets & Archive Modal (إدارة الكشوفات المحفوظة) -->
        <div x-cloak x-show="openSavedSheetsModal" class="fixed inset-0 bg-[black]/60 z-50 overflow-y-auto flex items-center justify-center p-4">
            <div x-show="openSavedSheetsModal" x-transition.duration.300ms class="panel p-5 rounded-2xl w-full max-w-2xl shadow-2xl border border-gray-200 dark:border-white-dark/10 bg-white dark:bg-[#0e1726]">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-gray-200 dark:border-white-dark/10">
                    <div>
                        <h5 class="font-bold text-base text-[#0e1726] dark:text-white-light">أرشيف الكشوفات وسجلات الحسابات</h5>
                        <p class="text-xs text-gray-500">يمكنك حفظ واسترجاع أي كشف حساب تم العمل عليه سابقاً</p>
                    </div>
                    <button type="button" @click="openSavedSheetsModal = false" class="text-gray-400 hover:text-gray-700 dark:hover:text-white">✕</button>
                </div>

                <div class="space-y-3 max-h-[400px] overflow-y-auto">
                    <template x-for="(sheet, sIndex) in savedSheets" :key="sheet.id || sIndex">
                        <div class="p-3.5 bg-gray-50 dark:bg-[#121e32] border border-gray-200 dark:border-white-dark/10 rounded-xl flex items-center justify-between hover:border-primary transition">
                            <div>
                                <h6 class="font-bold text-sm text-[#0e1726] dark:text-white-light" x-text="sheet.title || 'كشف بدون عنوان'"></h6>
                                <div class="flex items-center gap-3 text-xs text-gray-500 mt-1">
                                    <span x-text="'العميل: ' + (sheet.customer || 'غير محدد')"></span>
                                    <span>•</span>
                                    <span x-text="sheet.items.length + ' بنود'"></span>
                                    <span>•</span>
                                    <span x-text="'التاريخ: ' + sheet.savedAt"></span>
                                </div>
                                <div class="flex items-center gap-3 text-xs mt-1.5 font-semibold">
                                    <span class="text-blue-600" dir="ltr">$ <span x-text="formatNumber(sheet.totalUsd, 2)"></span></span>
                                    <span class="text-primary" dir="ltr">¥ <span x-text="formatNumber(sheet.totalRmb, 2)"></span></span>
                                    <span :class="sheet.remainingBalance >= 0 ? 'text-emerald-600' : 'text-rose-600'" dir="ltr">
                                        الرصيد: ¥ <span x-text="formatNumber(sheet.remainingBalance, 2)"></span>
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <button type="button" @click="loadSheet(sheet)" class="btn btn-primary btn-sm py-1 px-3 text-xs">
                                    فتح الكشف
                                </button>
                                <button type="button" @click="deleteSheet(sIndex)" class="btn btn-outline-danger btn-sm p-1.5" title="حذف من الأرشيف">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M19 7L18.1327 19.1425C18.0579 20.1891 17.187 21 16.1378 21H7.86224C6.81296 21 5.94208 20.1891 5.86732 19.1425L5 7M10 11V17M14 11V17M15 7V4C15 3.44772 14.5523 3 14 3H10C9.44772 3 9 3.44772 9 4V7M4 7H20" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>

                    <div x-show="savedSheets.length === 0" class="text-center py-8 text-gray-400 text-xs">
                        لا توجد كشوفات محفوظة في الأرشيف حالياً. اضغط على "حفظ الكشف" لحفظ الكشف الحالي في أي وقت.
                    </div>
                </div>

                <div class="flex items-center justify-end mt-4 pt-3 border-t border-gray-200 dark:border-white-dark/10">
                    <button type="button" @click="openSavedSheetsModal = false" class="btn btn-secondary btn-sm">إغلاق</button>
                </div>
            </div>
        </div>

        <!-- Printable Document Header (تظهر فقط أثناء أمر الطباعة) -->
        <div class="hidden print:block mb-6 p-4 border-b-2 border-black text-black">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-black">مجموعة الزبيدي للتجارة والشحن الدولي</h2>
                    <h4 class="text-base font-bold mt-1" x-text="sheetTitle"></h4>
                    <p class="text-sm mt-0.5" x-show="customerName" x-text="'العميل: ' + customerName"></p>
                </div>
                <div class="text-left text-xs">
                    <p>تاريخ الطباعة: {{ date('Y-m-d') }}</p>
                    <p>سعر الصرف المعتمد: <span x-text="defaultExchangeRate"></span> RMB/$</p>
                </div>
            </div>
            <div class="grid grid-cols-4 gap-2 mt-4 text-xs font-bold text-center">
                <div class="p-2 border border-black">إجمالي الدولار: $ <span x-text="formatNumber(totalUsd, 2)"></span></div>
                <div class="p-2 border border-black">إجمالي الرممبي: ¥ <span x-text="formatNumber(totalRmb, 2)"></span></div>
                <div class="p-2 border border-black">إجمالي الدفوعات: ¥ <span x-text="formatNumber(totalPayments, 2)"></span></div>
                <div class="p-2 border border-black">الرصيد المتبقي: ¥ <span x-text="formatNumber(remainingBalance, 2)"></span></div>
            </div>
        </div>

    </div>

    <!-- Alpine.js Application Logic -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('exchangeLedger', () => ({
                sheetTitle: 'كشف حساب مشتريات وتحويلات العملة',
                customerName: '',
                defaultExchangeRate: 7.2500,

                items: [],

                newRow: {
                    details: '',
                    purchase_amount: '',
                    store_payment_details: '',
                    store_payment: '',
                    usd_amount: '',
                    exchange_rate: '7.2500',
                    transfer_date: new Date().toISOString().split('T')[0]
                },

                openEditModal: false,
                editingIndex: -1,
                editingRow: {
                    details: '',
                    purchase_amount: '',
                    store_payment_details: '',
                    store_payment: '',
                    usd_amount: '',
                    exchange_rate: '',
                    transfer_date: ''
                },

                openSavedSheetsModal: false,
                savedSheets: [],

                initData() {
                    // Load saved sheets from localStorage
                    try {
                        const storedArchive = localStorage.getItem('al_zubaidi_saved_sheets');
                        if (storedArchive) {
                            this.savedSheets = JSON.parse(storedArchive);
                        }

                        // Load current working sheet
                        const currentData = localStorage.getItem('al_zubaidi_current_ledger');
                        if (currentData) {
                            const parsed = JSON.parse(currentData);
                            this.sheetTitle = parsed.sheetTitle || this.sheetTitle;
                            this.customerName = parsed.customerName || '';
                            this.defaultExchangeRate = parsed.defaultExchangeRate || 7.2500;
                            this.items = parsed.items || [];
                            this.newRow.exchange_rate = this.defaultExchangeRate;
                        } else {
                            this.loadSampleData();
                        }
                    } catch (e) {
                        console.error('Error loading ledger data from localStorage', e);
                        this.loadSampleData();
                    }
                },

                // Realtime calculated RMB for the quick entry row
                get newCalculatedRmb() {
                    const usd = parseFloat(this.newRow.usd_amount) || 0;
                    const rate = parseFloat(this.newRow.exchange_rate) || 0;
                    return usd * rate;
                },

                calculateItemRmb(item) {
                    const usd = parseFloat(item.usd_amount) || 0;
                    const rate = parseFloat(item.exchange_rate) || 0;
                    return usd * rate;
                },

                // Summary KPIs
                get totalUsd() {
                    return this.items.reduce((sum, item) => sum + (parseFloat(item.usd_amount) || 0), 0);
                },

                get totalRmb() {
                    return this.items.reduce((sum, item) => sum + this.calculateItemRmb(item), 0);
                },

                get totalPurchases() {
                    return this.items.reduce((sum, item) => sum + (parseFloat(item.purchase_amount) || 0), 0);
                },

                get totalStorePayments() {
                    return this.items.reduce((sum, item) => sum + (parseFloat(item.store_payment) || 0), 0);
                },

                get totalPayments() {
                    return this.totalPurchases + this.totalStorePayments;
                },

                get remainingBalance() {
                    return this.totalRmb - this.totalPayments;
                },

                get averageExchangeRate() {
                    if (this.totalUsd > 0) {
                        return this.totalRmb / this.totalUsd;
                    }
                    return parseFloat(this.defaultExchangeRate) || 7.2500;
                },

                onDefaultRateChanged() {
                    if (this.defaultExchangeRate && this.newRow) {
                        this.newRow.exchange_rate = this.defaultExchangeRate;
                    }
                    this.autoSave();
                },

                addNewRow() {
                    if (!this.newRow.details && !this.newRow.usd_amount && !this.newRow.purchase_amount && !this.newRow.store_payment) {
                        return;
                    }

                    const item = {
                        id: Date.now() + Math.random(),
                        details: this.newRow.details || '-',
                        purchase_amount: parseFloat(this.newRow.purchase_amount) || 0,
                        store_payment_details: this.newRow.store_payment_details || '-',
                        store_payment: parseFloat(this.newRow.store_payment) || 0,
                        usd_amount: parseFloat(this.newRow.usd_amount) || 0,
                        exchange_rate: parseFloat(this.newRow.exchange_rate) || this.defaultExchangeRate || 7.2500,
                        transfer_date: this.newRow.transfer_date || new Date().toISOString().split('T')[0]
                    };

                    this.items.push(item);
                    this.autoSave();

                    // Reset form keeping default rate and today's date
                    this.newRow.details = '';
                    this.newRow.purchase_amount = '';
                    this.newRow.store_payment_details = '';
                    this.newRow.store_payment = '';
                    this.newRow.usd_amount = '';
                    this.newRow.exchange_rate = this.defaultExchangeRate || '7.2500';

                    if (window.Swal) {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'bottom-start',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });
                        Toast.fire({
                            icon: 'success',
                            title: 'تمت إضافة السطر بنجاح'
                        });
                    }
                },

                addEmptyRow() {
                    this.items.push({
                        id: Date.now() + Math.random(),
                        details: '',
                        purchase_amount: 0,
                        store_payment_details: '',
                        store_payment: 0,
                        usd_amount: 0,
                        exchange_rate: this.defaultExchangeRate || 7.2500,
                        transfer_date: new Date().toISOString().split('T')[0]
                    });
                    this.autoSave();
                },

                duplicateRow(index) {
                    const original = this.items[index];
                    if (!original) return;

                    const copy = JSON.parse(JSON.stringify(original));
                    copy.id = Date.now() + Math.random();
                    copy.details = (copy.details || '') + ' (نسخة)';
                    this.items.splice(index + 1, 0, copy);
                    this.autoSave();
                },

                deleteRow(index) {
                    if (confirm('هل أنت متأكد من رغبتك بحذف هذا البند؟')) {
                        this.items.splice(index, 1);
                        this.autoSave();
                    }
                },

                clearAllPrompt() {
                    if (confirm('هل أنت متأكد من رغبتك بتفريغ الجدول بالكامل؟')) {
                        this.items = [];
                        this.autoSave();
                    }
                },

                openEditRowModal(index) {
                    this.editingIndex = index;
                    this.editingRow = JSON.parse(JSON.stringify(this.items[index]));
                    this.openEditModal = true;
                },

                saveEditedRow() {
                    if (this.editingIndex >= 0 && this.editingIndex < this.items.length) {
                        this.items[this.editingIndex] = {
                            ...this.editingRow,
                            purchase_amount: parseFloat(this.editingRow.purchase_amount) || 0,
                            store_payment: parseFloat(this.editingRow.store_payment) || 0,
                            usd_amount: parseFloat(this.editingRow.usd_amount) || 0,
                            exchange_rate: parseFloat(this.editingRow.exchange_rate) || 7.2500
                        };
                        this.autoSave();
                        this.openEditModal = false;
                    }
                },

                autoSave() {
                    try {
                        const payload = {
                            sheetTitle: this.sheetTitle,
                            customerName: this.customerName,
                            defaultExchangeRate: this.defaultExchangeRate,
                            items: this.items,
                            lastModified: new Date().toISOString()
                        };
                        localStorage.setItem('al_zubaidi_current_ledger', JSON.stringify(payload));
                    } catch (e) {
                        console.error('AutoSave failed', e);
                    }
                },

                saveCurrentSheet() {
                    const sheet = {
                        id: Date.now(),
                        title: this.sheetTitle || 'كشف بدون عنوان',
                        customer: this.customerName || 'عام',
                        defaultExchangeRate: this.defaultExchangeRate,
                        items: JSON.parse(JSON.stringify(this.items)),
                        totalUsd: this.totalUsd,
                        totalRmb: this.totalRmb,
                        totalPayments: this.totalPayments,
                        remainingBalance: this.remainingBalance,
                        savedAt: new Date().toLocaleDateString('ar-EG', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' })
                    };

                    this.savedSheets.unshift(sheet);
                    localStorage.setItem('al_zubaidi_saved_sheets', JSON.stringify(this.savedSheets));
                    this.autoSave();

                    if (window.Swal) {
                        Swal.fire({
                            icon: 'success',
                            title: 'تم الحفظ في الأرشيف',
                            text: 'تم حفظ الكشف بنجاح، يمكنك استرجاعه في أي وقت من قائمة الأرشيف.',
                            timer: 2500
                        });
                    } else {
                        alert('تم حفظ الكشف بنجاح في الأرشيف!');
                    }
                },

                loadSheet(sheet) {
                    if (confirm('هل تريد تحميل هذا الكشف واستبدال الجدول الحالي؟')) {
                        this.sheetTitle = sheet.title;
                        this.customerName = sheet.customer || '';
                        this.defaultExchangeRate = sheet.defaultExchangeRate || 7.2500;
                        this.items = JSON.parse(JSON.stringify(sheet.items || []));
                        this.autoSave();
                        this.openSavedSheetsModal = false;
                    }
                },

                deleteSheet(index) {
                    if (confirm('هل أنت متأكد من حذف هذا الكشف من الأرشيف؟')) {
                        this.savedSheets.splice(index, 1);
                        localStorage.setItem('al_zubaidi_saved_sheets', JSON.stringify(this.savedSheets));
                    }
                },

                createNewSheetPrompt() {
                    if (confirm('هل تريد بدء جدول جديد؟ يرجى التأكد من حفظ الكشف الحالي إذا أردت الرجوع إليه لاحقاً.')) {
                        this.sheetTitle = 'كشف حساب جديد';
                        this.customerName = '';
                        this.items = [];
                        this.autoSave();
                    }
                },

                loadSampleData() {
                    this.items = [
                        {
                            id: 1,
                            details: 'حوالة شراء بضائع - مصنع جوانزو',
                            purchase_amount: 54000.00,
                            store_payment_details: 'دفعة أولى لمحل الأمل',
                            store_payment: 12000.00,
                            usd_amount: 10000.00,
                            exchange_rate: 7.2500,
                            transfer_date: '2026-10-01'
                        },
                        {
                            id: 2,
                            details: 'شراء كراتين تغليف وتجهيز الشحن',
                            purchase_amount: 18500.00,
                            store_payment_details: 'تسديد فاتورة مستودع إيوو',
                            store_payment: 8500.00,
                            usd_amount: 5000.00,
                            exchange_rate: 7.2450,
                            transfer_date: '2026-10-04'
                        },
                        {
                            id: 3,
                            details: 'دفعة مشتريات إلكترونيات وإكسسوارات',
                            purchase_amount: 72000.00,
                            store_payment_details: 'تحويل مباشر لمكتب الشحن الصيني',
                            store_payment: 25000.00,
                            usd_amount: 18000.00,
                            exchange_rate: 7.2550,
                            transfer_date: '2026-10-08'
                        }
                    ];
                    this.autoSave();
                },

                exportToExcel() {
                    if (this.items.length === 0) {
                        alert('لا توجد بيانات لتصديرها.');
                        return;
                    }

                    let csvContent = '\uFEFF'; // UTF-8 BOM for Arabic support in Excel
                    csvContent += 'مجموعة الزبيدي - ' + (this.sheetTitle || 'كشف الحسابات') + '\n';
                    if (this.customerName) {
                        csvContent += 'العميل: ' + this.customerName + '\n';
                    }
                    csvContent += 'تاريخ التصدير: ' + new Date().toLocaleDateString('en-CA') + '\n\n';

                    // Headers
                    const headers = [
                        '#',
                        'تفاصيل (المشتريات)',
                        'مبلغ المشتريات (RMB)',
                        'تفاصيل دفع المحلات',
                        'دفع محلات (RMB)',
                        'مبلغ محول دولار ($)',
                        'سعر الصرف RMB',
                        'مبلغ محول رممبي (RMB)',
                        'تاريخ التحويل'
                    ];
                    csvContent += headers.map(h => `"${h}"`).join(',') + '\n';

                    // Rows
                    this.items.forEach((item, idx) => {
                        const calculatedRmb = this.calculateItemRmb(item);
                        const row = [
                            idx + 1,
                            `"${(item.details || '').replace(/"/g, '""')}"`,
                            (Number(item.purchase_amount) || 0).toFixed(2),
                            `"${(item.store_payment_details || '').replace(/"/g, '""')}"`,
                            (Number(item.store_payment) || 0).toFixed(2),
                            (Number(item.usd_amount) || 0).toFixed(2),
                            (Number(item.exchange_rate) || 0).toFixed(4),
                            calculatedRmb.toFixed(2),
                            `"${item.transfer_date || ''}"`
                        ];
                        csvContent += row.join(',') + '\n';
                    });

                    // Totals
                    csvContent += '\n';
                    csvContent += `المجاميع الإجمالية,,${this.totalPurchases.toFixed(2)},,${this.totalStorePayments.toFixed(2)},${this.totalUsd.toFixed(2)},,${this.totalRmb.toFixed(2)},\n`;
                    csvContent += `إجمالي الدفوعات:,,${this.totalPayments.toFixed(2)}\n`;
                    csvContent += `المجموع الباقي (الرصيد):,,${this.remainingBalance.toFixed(2)}\n`;

                    // Trigger Download
                    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                    const url = URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    const filename = (this.sheetTitle || 'ledger').replace(/[\/\\?%*:|"<>]/g, '_') + '_' + new Date().toISOString().split('T')[0] + '.csv';
                    link.setAttribute('href', url);
                    link.setAttribute('download', filename);
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                },

                printSheet() {
                    window.print();
                },

                formatNumber(val, decimals = 2) {
                    const num = Number(val);
                    if (isNaN(num)) return '0.00';
                    return num.toLocaleString('en-US', {
                        minimumFractionDigits: decimals,
                        maximumFractionDigits: decimals
                    });
                }
            }));
        });
    </script>
</x-layout.custom-default>
