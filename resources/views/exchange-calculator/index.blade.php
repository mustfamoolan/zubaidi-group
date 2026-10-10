<x-layout.custom-default>
    <div x-data="excelLedgerApp({
            currentLedgerId: {{ $selectedLedger->Id ?? 0 }},
            initialItems: {{ json_encode($items) }},
            csrfToken: '{{ csrf_token() }}'
         })"
         x-init="initApp()"
         class="space-y-6">

        <!-- Top Header & Customer/Ledger Selector -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <ul class="flex space-x-2 rtl:space-x-reverse text-sm mb-2">
                    <li>
                        <a href="{{ url('/companies') }}" class="text-primary hover:underline">لوحة التحكم</a>
                    </li>
                    <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                        <span class="text-black dark:text-white-light font-medium">سجل الحسابات وسعر الصرف (نمط إكسل)</span>
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
                        <div class="flex items-center gap-3">
                            <h4 class="text-xl font-bold text-[#0e1726] dark:text-white-light">
                                {{ $selectedLedger->Title ?? 'جدول الحسابات وسعر الصرف' }}
                            </h4>
                            <!-- حالة الحفظ التلقائي المباشر -->
                            <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold"
                                 :class="saveStatus === 'saving' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200' : (saveStatus === 'error' ? 'bg-rose-100 text-rose-800' : 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200')">
                                <span class="w-2 h-2 rounded-full"
                                      :class="saveStatus === 'saving' ? 'bg-amber-500 animate-ping' : (saveStatus === 'error' ? 'bg-rose-500' : 'bg-emerald-500')"></span>
                                <span x-text="saveStatusText"></span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                            العميل: <strong class="text-black dark:text-white">{{ $selectedLedger->CustomerName ?? 'عام' }}</strong>
                            @if(!empty($selectedLedger->CustomerPhone))
                                | هاتف: <span dir="ltr">{{ $selectedLedger->CustomerPhone }}</span>
                            @endif
                            | الكتابة والحفظ مباشر في قاعدة البيانات
                        </p>
                    </div>
                </div>
            </div>

            <!-- Ledger Selector & Action Buttons -->
            <div class="flex items-center flex-wrap gap-2 print:hidden">
                <!-- Dropdown اختيار كشف الحساب / العميل -->
                <div class="relative min-w-[240px]">
                    <select onchange="window.location.href = '{{ url('/exchange-calculator') }}?ledger_id=' + this.value"
                            class="form-select py-1.5 text-xs font-semibold text-primary border-primary/40 rounded-lg shadow-sm">
                        @foreach($ledgers as $l)
                            <option value="{{ $l->Id }}" {{ (isset($selectedLedger) && $selectedLedger->Id == $l->Id) ? 'selected' : '' }}>
                                {{ $l->CustomerName }} - {{ $l->Title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- زر إضافة كشف حساب جديد -->
                <button type="button" @click="openNewLedgerModal = true" class="btn btn-outline-primary btn-sm flex items-center gap-1.5 shadow-sm">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 4V20M20 12H4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                    <span>كشف جديد</span>
                </button>

                <!-- زر تصدير Excel -->
                <button type="button" @click="exportToExcel()" class="btn btn-outline-success btn-sm flex items-center gap-1.5 shadow-sm">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 16.004V17C4 18.8856 4 19.8284 4.58579 20.4142C5.17157 21 6.11438 21 8 21H16C17.8856 21 18.8284 21 19.4142 20.4142C20 19.8284 20 18.8856 20 17V16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        <path opacity="0.5" d="M12 3V15M12 15L8.5 11.5M12 15L15.5 11.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>تصدير Excel</span>
                </button>

                <!-- زر طباعة -->
                <button type="button" @click="window.print()" class="btn btn-outline-secondary btn-sm flex items-center gap-1.5 shadow-sm">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7 8V3H17V8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        <path opacity="0.5" d="M7 16H17V21H7V16Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        <path d="M3 11C3 9.34315 4.34315 8 6 8H18C19.6569 8 21 9.34315 21 11V16C21 17.6569 19.6569 19 18 19H17V16H7V19H6C4.34315 19 3 17.6569 3 16V11Z" stroke="currentColor" stroke-width="1.5"/>
                    </svg>
                    <span>طباعة</span>
                </button>
            </div>
        </div>

        <!-- 4 KPI Summary Cards (المجاميع الأربعة المحسوبة حياً) -->
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

        <!-- Excel-Like Spreadsheet Grid (جدول الإكسل المباشر بدون حقول إدخال علوية) -->
        <div class="panel p-0 border border-gray-200/80 dark:border-white-dark/10 rounded-xl overflow-hidden shadow-sm">

            <!-- Toolbar -->
            <div class="px-4 py-2.5 bg-gray-50/70 dark:bg-[#121e32]/60 border-b border-gray-200/60 dark:border-white-dark/10 flex items-center justify-between text-xs print:hidden">
                <div class="flex items-center gap-2">
                    <span class="font-bold text-[#0e1726] dark:text-white-light">جدول البيانات التفاعلي</span>
                    <span class="text-gray-400">|</span>
                    <span class="text-gray-500">اكتب في أي سطر مباشرة، يتم حساب المعادلة والحفظ فورياً في قاعدة البيانات</span>
                </div>
                <div class="flex items-center gap-2 font-medium">
                    <button type="button" @click="appendNewEmptyRow()" class="btn btn-sm btn-primary py-1 px-3 text-xs flex items-center gap-1 shadow-sm">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 4V20M20 12H4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        <span>إضافة سطر للجدول (Insert Row)</span>
                    </button>
                </div>
            </div>

            <!-- Table Container -->
            <div class="table-responsive max-h-[650px] overflow-auto">
                <table class="table-striped table-hover w-full text-xs text-right border-collapse">
                    <thead class="sticky top-0 bg-[#f1f3f6] dark:bg-[#0e1726] z-10 border-b border-gray-300 dark:border-white-dark/20 text-gray-700 dark:text-gray-200 font-bold text-[11px] shadow-sm">
                        <tr>
                            <th class="w-12 text-center py-2.5 px-2 border-l border-gray-200 dark:border-white-dark/10">#</th>
                            <th class="min-w-[220px] py-2.5 px-3 border-l border-gray-200 dark:border-white-dark/10">تفاصيل (المشتريات)</th>
                            <th class="min-w-[130px] py-2.5 px-3 text-left border-l border-gray-200 dark:border-white-dark/10" dir="ltr">مبلغ المشتريات (¥)</th>
                            <th class="min-w-[190px] py-2.5 px-3 border-l border-gray-200 dark:border-white-dark/10">تفاصيل دفع المحلات</th>
                            <th class="min-w-[130px] py-2.5 px-3 text-left border-l border-gray-200 dark:border-white-dark/10" dir="ltr">دفع محلات (¥)</th>
                            <th class="min-w-[140px] py-2.5 px-3 text-left text-blue-600 dark:text-blue-400 border-l border-gray-200 dark:border-white-dark/10" dir="ltr">مبلغ محول دولار ($)</th>
                            <th class="min-w-[110px] py-2.5 px-3 text-left text-amber-600 dark:text-amber-400 border-l border-gray-200 dark:border-white-dark/10" dir="ltr">سعر الصرف RMB</th>
                            <th class="min-w-[150px] py-2.5 px-3 text-left font-extrabold text-primary border-l border-gray-200 dark:border-white-dark/10 bg-primary/[0.04]" dir="ltr">مبلغ محول رممبي (¥)</th>
                            <th class="min-w-[110px] py-2.5 px-3 text-center border-l border-gray-200 dark:border-white-dark/10">تاريخ التحويل</th>
                            <th class="w-16 text-center py-2.5 px-2 print:hidden">حذف</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200/60 dark:divide-white-dark/10 font-medium">
                        <template x-for="(item, index) in items" :key="item.id || ('temp-' + index)">
                            <tr class="group hover:bg-primary/5 transition-colors focus-within:bg-primary/10">
                                <!-- Index -->
                                <td class="text-center font-bold text-gray-500 py-2 px-2 border-l border-gray-200/50 dark:border-white-dark/10" x-text="index + 1"></td>

                                <!-- 1. Details (تفاصيل) -->
                                <td class="p-0 border-l border-gray-200/50 dark:border-white-dark/10">
                                    <input type="text"
                                           x-model="item.details"
                                           @input="onCellEdited(item, index)"
                                           @keydown.enter.prevent="onEnterPressed(index, 'details')"
                                           placeholder="تفاصيل البند..."
                                           class="w-full h-full py-2 px-3 text-xs bg-transparent border-0 focus:ring-1 focus:ring-primary focus:bg-white dark:focus:bg-[#0e1726] rounded-none outline-none">
                                </td>

                                <!-- 2. Purchase Amount (مبلغ المشتريات) -->
                                <td class="p-0 border-l border-gray-200/50 dark:border-white-dark/10" dir="ltr">
                                    <input type="number" step="0.01"
                                           x-model="item.purchase_amount"
                                           @input="onCellEdited(item, index)"
                                           @keydown.enter.prevent="onEnterPressed(index, 'purchase_amount')"
                                           placeholder="0.00"
                                           class="w-full h-full py-2 px-3 text-xs text-left font-semibold bg-transparent border-0 focus:ring-1 focus:ring-primary focus:bg-white dark:focus:bg-[#0e1726] rounded-none outline-none">
                                </td>

                                <!-- 3. Store Payment Details (تفاصيل دفع المحلات) -->
                                <td class="p-0 border-l border-gray-200/50 dark:border-white-dark/10">
                                    <input type="text"
                                           x-model="item.store_payment_details"
                                           @input="onCellEdited(item, index)"
                                           @keydown.enter.prevent="onEnterPressed(index, 'store_payment_details')"
                                           placeholder="تفاصيل الدفع..."
                                           class="w-full h-full py-2 px-3 text-xs bg-transparent border-0 focus:ring-1 focus:ring-primary focus:bg-white dark:focus:bg-[#0e1726] rounded-none outline-none">
                                </td>

                                <!-- 4. Store Payment (دفع محلات) -->
                                <td class="p-0 border-l border-gray-200/50 dark:border-white-dark/10" dir="ltr">
                                    <input type="number" step="0.01"
                                           x-model="item.store_payment"
                                           @input="onCellEdited(item, index)"
                                           @keydown.enter.prevent="onEnterPressed(index, 'store_payment')"
                                           placeholder="0.00"
                                           class="w-full h-full py-2 px-3 text-xs text-left font-semibold bg-transparent border-0 focus:ring-1 focus:ring-primary focus:bg-white dark:focus:bg-[#0e1726] rounded-none outline-none">
                                </td>

                                <!-- 5. USD Amount (مبلغ محول دولار) -->
                                <td class="p-0 border-l border-gray-200/50 dark:border-white-dark/10 bg-blue-50/20 dark:bg-blue-950/10" dir="ltr">
                                    <input type="number" step="0.01"
                                           x-model="item.usd_amount"
                                           @input="onCurrencyChanged(item, index)"
                                           @keydown.enter.prevent="onEnterPressed(index, 'usd_amount')"
                                           placeholder="0.00"
                                           class="w-full h-full py-2 px-3 text-xs text-left font-bold text-blue-600 dark:text-blue-400 bg-transparent border-0 focus:ring-1 focus:ring-blue-500 focus:bg-white dark:focus:bg-[#0e1726] rounded-none outline-none">
                                </td>

                                <!-- 6. Exchange Rate (سعر الصرف RMB) -->
                                <td class="p-0 border-l border-gray-200/50 dark:border-white-dark/10 bg-amber-50/20 dark:bg-amber-950/10" dir="ltr">
                                    <input type="number" step="0.0001"
                                           x-model="item.exchange_rate"
                                           @input="onCurrencyChanged(item, index)"
                                           @keydown.enter.prevent="onEnterPressed(index, 'exchange_rate')"
                                           placeholder="7.2500"
                                           class="w-full h-full py-2 px-3 text-xs text-left font-bold text-amber-600 dark:text-amber-400 bg-transparent border-0 focus:ring-1 focus:ring-amber-500 focus:bg-white dark:focus:bg-[#0e1726] rounded-none outline-none">
                                </td>

                                <!-- 7. Calculated RMB (المعادلة الحسابية المباشرة: دولار * سعر الصرف) -->
                                <td class="py-2 px-3 border-l border-gray-200/50 dark:border-white-dark/10 bg-primary/5 select-all" dir="ltr">
                                    <div class="flex items-center justify-between font-extrabold text-primary text-xs">
                                        <span>¥</span>
                                        <span x-text="formatNumber(item.rmb_amount, 2)">0.00</span>
                                    </div>
                                </td>

                                <!-- 8. Transfer Date (تاريخ التحويل) -->
                                <td class="p-0 border-l border-gray-200/50 dark:border-white-dark/10 text-center">
                                    <input type="date"
                                           x-model="item.transfer_date"
                                           @input="onCellEdited(item, index)"
                                           @keydown.enter.prevent="onEnterPressed(index, 'transfer_date')"
                                           class="w-full h-full py-2 px-1 text-[11px] text-center bg-transparent border-0 focus:ring-1 focus:ring-primary focus:bg-white dark:focus:bg-[#0e1726] rounded-none outline-none">
                                </td>

                                <!-- Actions: Delete -->
                                <td class="text-center py-2 px-1 print:hidden whitespace-nowrap">
                                    <button type="button"
                                            @click="deleteRow(item, index)"
                                            title="حذف هذا السطر"
                                            class="w-6 h-6 rounded flex items-center justify-center text-gray-400 hover:text-danger hover:bg-danger/10 mx-auto transition">
                                        ✕
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>

                    <!-- Table Footer: Totals Row (سطر المجاميع الإجمالية المطابق للإكسل) -->
                    <tfoot class="bg-[#eaedf0] dark:bg-[#14233c] font-bold text-xs text-gray-800 dark:text-white-light border-t-2 border-gray-300 dark:border-white-dark/20 sticky bottom-0 z-10 shadow-md">
                        <tr>
                            <td class="text-center py-3 px-2 border-l border-gray-300 dark:border-white-dark/20">∑</td>
                            <td class="py-3 px-3 border-l border-gray-300 dark:border-white-dark/20 font-black">المجاميع الإجمالية</td>

                            <!-- Total Purchases -->
                            <td class="py-3 px-3 text-left border-l border-gray-300 dark:border-white-dark/20 font-extrabold" dir="ltr">
                                ¥ <span x-text="formatNumber(totalPurchases, 2)">0.00</span>
                            </td>

                            <td class="py-3 px-3 border-l border-gray-300 dark:border-white-dark/20 text-gray-500 font-normal">إجمالي دفع المحلات:</td>

                            <!-- Total Store Payments -->
                            <td class="py-3 px-3 text-left border-l border-gray-300 dark:border-white-dark/20 font-extrabold" dir="ltr">
                                ¥ <span x-text="formatNumber(totalStorePayments, 2)">0.00</span>
                            </td>

                            <!-- Total USD -->
                            <td class="py-3 px-3 text-left border-l border-gray-300 dark:border-white-dark/20 font-extrabold text-blue-600 dark:text-blue-400 text-sm" dir="ltr">
                                $ <span x-text="formatNumber(totalUsd, 2)">0.00</span>
                            </td>

                            <!-- Average Exchange Rate -->
                            <td class="py-3 px-3 text-left border-l border-gray-300 dark:border-white-dark/20 font-bold text-amber-600 dark:text-amber-400" dir="ltr">
                                <span class="text-[10px] text-gray-500 font-normal">متوسط: </span>
                                <span x-text="formatNumber(averageExchangeRate, 4)">0.0000</span>
                            </td>

                            <!-- Total RMB -->
                            <td class="py-3 px-3 text-left border-l border-gray-300 dark:border-white-dark/20 font-black text-primary text-sm" dir="ltr">
                                ¥ <span x-text="formatNumber(totalRmb, 2)">0.00</span>
                            </td>

                            <td class="py-3 px-3 text-center border-l border-gray-300 dark:border-white-dark/20 text-gray-500 text-[10px]" x-text="items.length + ' أسطر'"></td>
                            <td class="py-3 px-2 print:hidden"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Bottom Add Row Button Bar -->
            <div class="p-3 bg-gray-50/50 dark:bg-[#121e32]/40 border-t border-gray-200/60 dark:border-white-dark/10 flex items-center justify-between print:hidden">
                <button type="button" @click="appendNewEmptyRow()" class="btn btn-outline-primary btn-sm flex items-center gap-1.5">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 4V20M20 12H4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <span>إضافة سطر جديد (أو اضغط Enter في آخر خلية)</span>
                </button>

                <div class="text-xs text-gray-500 flex items-center gap-4">
                    <span>إجمالي الدفوعات: <strong class="text-purple-600" dir="ltr">¥ <span x-text="formatNumber(totalPayments, 2)"></span></strong></span>
                    <span>الرصيد المتبقي: <strong :class="remainingBalance >= 0 ? 'text-emerald-600' : 'text-rose-600'" dir="ltr">¥ <span x-text="formatNumber(remainingBalance, 2)"></span></strong></span>
                </div>
            </div>

        </div>

        <!-- Modal إنشاء كشف حساب جديد -->
        <div x-cloak x-show="openNewLedgerModal" class="fixed inset-0 bg-[black]/60 z-50 overflow-y-auto flex items-center justify-center p-4">
            <div x-show="openNewLedgerModal" x-transition.duration.300ms class="panel p-5 rounded-2xl w-full max-w-lg shadow-2xl border border-gray-200 dark:border-white-dark/10 bg-white dark:bg-[#0e1726]">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-gray-200 dark:border-white-dark/10">
                    <h5 class="font-bold text-base text-[#0e1726] dark:text-white-light">
                        إنشاء كشف حساب جديد في قاعدة البيانات
                    </h5>
                    <button type="button" @click="openNewLedgerModal = false" class="text-gray-400 hover:text-gray-700 dark:hover:text-white">✕</button>
                </div>

                <form method="POST" action="{{ url('/exchange-calculator/ledgers') }}" class="space-y-4 text-xs">
                    @csrf
                    <div>
                        <label class="block font-semibold mb-1">اختر العميل:</label>
                        <select name="CustomerId" required class="form-select text-xs">
                            @foreach($customers as $c)
                                <option value="{{ $c->Id }}">{{ $c->Name }} ({{ $c->Phone ?? '-' }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold mb-1">عنوان الكشف / الشيت:</label>
                        <input type="text" name="Title" required placeholder="مثال: سجل مشتريات وحسابات - حاوية رقم 14" class="form-input text-xs">
                    </div>

                    <div>
                        <label class="block font-semibold mb-1">ملاحظات إضافية:</label>
                        <textarea name="Notes" rows="2" placeholder="ملاحظات حول هذا الكشف..." class="form-textarea text-xs"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-200 dark:border-white-dark/10">
                        <button type="button" @click="openNewLedgerModal = false" class="btn btn-outline-danger btn-sm">إلغاء</button>
                        <button type="submit" class="btn btn-primary btn-sm">حفظ وإنشاء الكشف</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Printable Document Header (تظهر فقط أثناء أمر الطباعة) -->
        <div class="hidden print:block mb-6 p-4 border-b-2 border-black text-black">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-black">مجموعة الزبيدي للتجارة والشحن الدولي</h2>
                    <h4 class="text-base font-bold mt-1">{{ $selectedLedger->Title ?? '' }}</h4>
                    <p class="text-sm mt-0.5">العميل: {{ $selectedLedger->CustomerName ?? '' }} | {{ $selectedLedger->CustomerPhone ?? '' }}</p>
                </div>
                <div class="text-left text-xs">
                    <p>تاريخ الطباعة: {{ date('Y-m-d') }}</p>
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
        function excelLedgerApp(config) {
            return {
                currentLedgerId: config.currentLedgerId,
                csrfToken: config.csrfToken,
                items: config.initialItems || [],

                saveStatus: 'saved', // 'saved', 'saving', 'error'
                saveStatusText: 'محفوظ في قاعدة البيانات ✓',
                saveTimeout: null,

                openNewLedgerModal: false,

                initApp() {
                    // Ensure each item has calculated rmb_amount
                    this.items.forEach(item => {
                        const usd = parseFloat(item.usd_amount) || 0;
                        const rate = parseFloat(item.exchange_rate) || 7.25;
                        item.rmb_amount = Math.round((usd * rate) * 100) / 100;
                    });

                    // If items are empty, add one empty row ready for typing
                    if (this.items.length === 0) {
                        this.appendNewEmptyRow();
                    }
                },

                // Summary KPIs
                get totalUsd() {
                    return this.items.reduce((sum, item) => sum + (parseFloat(item.usd_amount) || 0), 0);
                },

                get totalRmb() {
                    return this.items.reduce((sum, item) => sum + (parseFloat(item.rmb_amount) || 0), 0);
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
                    return 7.2500;
                },

                // When USD or Rate changes: instant formula recalculation & debounced DB save
                onCurrencyChanged(item, index) {
                    const usd = parseFloat(item.usd_amount) || 0;
                    const rate = parseFloat(item.exchange_rate) || 7.25;
                    item.rmb_amount = Math.round((usd * rate) * 100) / 100;
                    this.scheduleSave(item, index);
                },

                // When any other cell changes
                onCellEdited(item, index) {
                    this.scheduleSave(item, index);
                },

                // Debounced real-time saving to database
                scheduleSave(item, index) {
                    this.saveStatus = 'saving';
                    this.saveStatusText = 'جاري الحفظ...';

                    if (this.saveTimeout) {
                        clearTimeout(this.saveTimeout);
                    }

                    this.saveTimeout = setTimeout(() => {
                        this.saveItemToDatabase(item, index);
                    }, 600);
                },

                async saveItemToDatabase(item, index) {
                    try {
                        const payload = {
                            CustomerLedgerId: this.currentLedgerId,
                            Details: item.details || '',
                            PurchaseAmount: parseFloat(item.purchase_amount) || 0,
                            StorePaymentDetails: item.store_payment_details || '',
                            StorePayment: parseFloat(item.store_payment) || 0,
                            UsdAmount: parseFloat(item.usd_amount) || 0,
                            RmbExchangeRate: parseFloat(item.exchange_rate) || 7.25,
                            TransferDate: item.transfer_date || new Date().toISOString().split('T')[0]
                        };

                        if (item.id && item.id > 0) {
                            // Update existing row
                            const res = await fetch(`{{ url('/exchange-calculator/items') }}/${item.id}`, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': this.csrfToken,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify(payload)
                            });

                            if (!res.ok) throw new Error('Save failed');
                            const data = await res.json();
                            if (data.rmb_amount !== undefined) {
                                item.rmb_amount = data.rmb_amount;
                            }
                        } else {
                            // Insert brand new row
                            const res = await fetch(`{{ url('/exchange-calculator/items') }}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': this.csrfToken,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify(payload)
                            });

                            if (!res.ok) throw new Error('Create failed');
                            const data = await res.json();
                            item.id = data.id;
                            if (data.rmb_amount !== undefined) {
                                item.rmb_amount = data.rmb_amount;
                            }
                        }

                        this.saveStatus = 'saved';
                        this.saveStatusText = 'تم الحفظ في قاعدة البيانات ✓';
                    } catch (e) {
                        console.error('Error saving item:', e);
                        this.saveStatus = 'error';
                        this.saveStatusText = 'خطأ في الحفظ';
                    }
                },

                // When Enter is pressed in a cell
                onEnterPressed(index, fieldName) {
                    if (index === this.items.length - 1) {
                        this.appendNewEmptyRow();
                    }
                },

                // Append an empty row to table & save it to DB
                async appendNewEmptyRow() {
                    const newRow = {
                        id: 0,
                        customer_ledger_id: this.currentLedgerId,
                        details: '',
                        purchase_amount: 0,
                        store_payment_details: '',
                        store_payment: 0,
                        usd_amount: 0,
                        exchange_rate: 7.25,
                        rmb_amount: 0,
                        transfer_date: new Date().toISOString().split('T')[0]
                    };

                    this.items.push(newRow);

                    // Save directly to DB to assign real Id
                    await this.saveItemToDatabase(newRow, this.items.length - 1);
                },

                // Delete row from table and DB
                async deleteRow(item, index) {
                    if (!confirm('هل أنت متأكد من رغبتك بحذف هذا السطر؟')) {
                        return;
                    }

                    if (item.id && item.id > 0) {
                        try {
                            const res = await fetch(`{{ url('/exchange-calculator/items') }}/${item.id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': this.csrfToken,
                                    'Accept': 'application/json'
                                }
                            });
                            if (!res.ok) throw new Error('Delete failed');
                        } catch (e) {
                            console.error('Delete failed:', e);
                            alert('تعذر حذف السطر من قاعدة البيانات');
                            return;
                        }
                    }

                    this.items.splice(index, 1);
                    if (this.items.length === 0) {
                        this.appendNewEmptyRow();
                    }
                },

                exportToExcel() {
                    if (this.items.length === 0) {
                        alert('لا توجد بيانات لتصديرها.');
                        return;
                    }

                    let csvContent = '\uFEFF'; // UTF-8 BOM
                    csvContent += 'مجموعة الزبيدي - {{ $selectedLedger->Title ?? "كشف الحسابات" }}\n';
                    csvContent += 'العميل: {{ $selectedLedger->CustomerName ?? "عام" }}\n';
                    csvContent += 'تاريخ التصدير: ' + new Date().toLocaleDateString('en-CA') + '\n\n';

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

                    this.items.forEach((item, idx) => {
                        const row = [
                            idx + 1,
                            `"${(item.details || '').replace(/"/g, '""')}"`,
                            (Number(item.purchase_amount) || 0).toFixed(2),
                            `"${(item.store_payment_details || '').replace(/"/g, '""')}"`,
                            (Number(item.store_payment) || 0).toFixed(2),
                            (Number(item.usd_amount) || 0).toFixed(2),
                            (Number(item.exchange_rate) || 0).toFixed(4),
                            (Number(item.rmb_amount) || 0).toFixed(2),
                            `"${item.transfer_date || ''}"`
                        ];
                        csvContent += row.join(',') + '\n';
                    });

                    csvContent += '\n';
                    csvContent += `المجاميع الإجمالية,,${this.totalPurchases.toFixed(2)},,${this.totalStorePayments.toFixed(2)},${this.totalUsd.toFixed(2)},,${this.totalRmb.toFixed(2)},\n`;
                    csvContent += `إجمالي الدفوعات:,,${this.totalPayments.toFixed(2)}\n`;
                    csvContent += `المجموع الباقي (الرصيد):,,${this.remainingBalance.toFixed(2)}\n`;

                    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                    const url = URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    link.setAttribute('href', url);
                    link.setAttribute('download', 'ledger_{{ $selectedLedger->Id ?? 1 }}_' + new Date().toISOString().split('T')[0] + '.csv');
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                },

                formatNumber(val, decimals = 2) {
                    const num = Number(val);
                    if (isNaN(num)) return '0.00';
                    return num.toLocaleString('en-US', {
                        minimumFractionDigits: decimals,
                        maximumFractionDigits: decimals
                    });
                }
            };
        }
    </script>
</x-layout.custom-default>
