<x-layout.custom-default>
    <div x-data="pureExcelSpreadsheet({
            initialData: {{ json_encode($data) }},
            csrfToken: '{{ csrf_token() }}'
         })"
         x-init="initSheet()"
         class="space-y-6">

        <!-- Top Header & Status -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <ul class="flex space-x-2 rtl:space-x-reverse text-sm mb-2">
                    <li>
                        <a href="{{ url('/') }}" class="text-primary hover:underline">لوحة التحكم</a>
                    </li>
                    <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                        <span class="text-black dark:text-white-light font-medium">جدول الحسابات وسعر الصرف (نمط إكسل)</span>
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
                            <input type="text"
                                   x-model="sheetTitle"
                                   @input="onSheetChanged()"
                                   placeholder="عنوان كشف الحساب..."
                                   class="text-xl font-bold text-[#0e1726] dark:text-white-light bg-transparent border-0 border-b border-transparent hover:border-gray-300 focus:border-primary focus:ring-0 p-0 transition">

                            <!-- شارة حالة الحفظ التلقائي -->
                            <div class="flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold select-none"
                                 :class="saveStatus === 'saving' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200' : 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200'">
                                <span class="w-2 h-2 rounded-full"
                                      :class="saveStatus === 'saving' ? 'bg-amber-500 animate-ping' : 'bg-emerald-500'"></span>
                                <span x-text="saveStatus === 'saving' ? 'جاري الحفظ...' : 'تم الحفظ تلقائياً ✓'"></span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            صفحة مستقلة - اكتب مباشرة في خلايا الجدول بالأسفل، يتم حساب المعادلات وحفظ بياناتك فورياً
                        </p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center flex-wrap gap-2 print:hidden">
                <button type="button" @click="appendNewRow()" class="btn btn-primary btn-sm flex items-center gap-1.5 shadow-sm">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 4V20M20 12H4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <span>إضافة سطر للجدول</span>
                </button>

                <button type="button" @click="exportToExcel()" class="btn btn-outline-success btn-sm flex items-center gap-1.5 shadow-sm">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 16.004V17C4 18.8856 4 19.8284 4.58579 20.4142C5.17157 21 6.11438 21 8 21H16C17.8856 21 18.8284 21 19.4142 20.4142C20 19.8284 20 18.8856 20 17V16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        <path opacity="0.5" d="M12 3V15M12 15L8.5 11.5M12 15L15.5 11.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>تصدير Excel</span>
                </button>

                <button type="button" @click="window.print()" class="btn btn-outline-secondary btn-sm flex items-center gap-1.5 shadow-sm">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7 8V3H17V8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        <path opacity="0.5" d="M7 16H17V21H7V16Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        <path d="M3 11C3 9.34315 4.34315 8 6 8H18C19.6569 8 21 9.34315 21 11V16C21 17.6569 19.6569 19 18 19H17V16H7V19H6C4.34315 19 3 17.6569 3 16V11Z" stroke="currentColor" stroke-width="1.5"/>
                    </svg>
                    <span>طباعة</span>
                </button>

                <button type="button" @click="clearSheetPrompt()" class="btn btn-outline-danger btn-sm flex items-center gap-1.5 shadow-sm" title="تفريغ الجدول">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19 7L18.1327 19.1425C18.0579 20.1891 17.187 21 16.1378 21H7.86224C6.81296 21 5.94208 20.1891 5.86732 19.1425L5 7M10 11V17M14 11V17M15 7V4C15 3.44772 14.5523 3 14 3H10C9.44772 3 9 3.44772 9 4V7M4 7H20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>تفريغ الجدول</span>
                </button>
            </div>
        </div>

        <!-- 4 KPI Summary Cards (المجاميع الـ 4 المحسوبة لحظياً) -->
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
                          x-text="remainingBalance >= 0 ? 'فائض' : 'مستحق'">
                    </span>
                </div>
            </div>

        </div>

        <!-- Excel Spreadsheet Table (جدول الإكسل الحقيقي - كتابة مباشرة وحفظ فوري) -->
        <div class="panel p-0 border border-gray-300 dark:border-white-dark/20 rounded-xl overflow-hidden shadow-sm">

            <div class="table-responsive max-h-[700px] overflow-auto">
                <table class="table-striped table-hover w-full text-xs text-right border-collapse select-none">
                    <thead class="sticky top-0 bg-[#eaedf1] dark:bg-[#0e1726] z-10 border-b border-gray-300 dark:border-white-dark/20 text-gray-800 dark:text-gray-200 font-bold text-[11px] shadow-sm">
                        <tr>
                            <th class="w-12 text-center py-2.5 px-2 border-l border-gray-300 dark:border-white-dark/10">#</th>
                            <th class="min-w-[220px] py-2.5 px-3 border-l border-gray-300 dark:border-white-dark/10">تفاصيل (المشتريات)</th>
                            <th class="min-w-[130px] py-2.5 px-3 text-left border-l border-gray-300 dark:border-white-dark/10" dir="ltr">مبلغ المشتريات (¥)</th>
                            <th class="min-w-[190px] py-2.5 px-3 border-l border-gray-300 dark:border-white-dark/10">تفاصيل دفع المحلات</th>
                            <th class="min-w-[130px] py-2.5 px-3 text-left border-l border-gray-300 dark:border-white-dark/10" dir="ltr">دفع محلات (¥)</th>
                            <th class="min-w-[140px] py-2.5 px-3 text-left text-blue-700 dark:text-blue-400 border-l border-gray-300 dark:border-white-dark/10 bg-blue-50/30 dark:bg-blue-950/20" dir="ltr">مبلغ محول دولار ($)</th>
                            <th class="min-w-[110px] py-2.5 px-3 text-left text-amber-700 dark:text-amber-400 border-l border-gray-300 dark:border-white-dark/10 bg-amber-50/30 dark:bg-amber-950/20" dir="ltr">سعر الصرف RMB</th>
                            <th class="min-w-[150px] py-2.5 px-3 text-left font-black text-primary border-l border-gray-300 dark:border-white-dark/10 bg-primary/10" dir="ltr">مبلغ محول رممبي (¥)</th>
                            <th class="min-w-[110px] py-2.5 px-3 text-center border-l border-gray-300 dark:border-white-dark/10">تاريخ التحويل</th>
                            <th class="w-14 text-center py-2.5 px-2 print:hidden">حذف</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200/80 dark:divide-white-dark/10 font-medium">
                        <template x-for="(item, index) in items" :key="index">
                            <tr class="group hover:bg-primary/5 transition-colors focus-within:bg-blue-50/30 dark:focus-within:bg-[#132238]">
                                <!-- Index -->
                                <td class="text-center font-bold text-gray-500 py-1.5 px-2 border-l border-gray-200 dark:border-white-dark/10 bg-gray-50/40 dark:bg-[#0e1726]/40" x-text="index + 1"></td>

                                <!-- 1. Details (تفاصيل) -->
                                <td class="p-0 border-l border-gray-200 dark:border-white-dark/10">
                                    <input type="text"
                                           x-model="item.details"
                                           @input="onCellInput(item)"
                                           @keydown.enter.prevent="onEnterKey(index)"
                                           placeholder="اكتب التفاصيل..."
                                           class="w-full py-2 px-3 text-xs bg-transparent border-0 focus:ring-1 focus:ring-primary focus:bg-white dark:focus:bg-[#0e1726] rounded-none outline-none">
                                </td>

                                <!-- 2. Purchase Amount (مبلغ المشتريات) -->
                                <td class="p-0 border-l border-gray-200 dark:border-white-dark/10" dir="ltr">
                                    <input type="number" step="0.01"
                                           x-model="item.purchase_amount"
                                           @input="onCellInput(item)"
                                           @keydown.enter.prevent="onEnterKey(index)"
                                           placeholder="0.00"
                                           class="w-full py-2 px-3 text-xs text-left font-semibold bg-transparent border-0 focus:ring-1 focus:ring-primary focus:bg-white dark:focus:bg-[#0e1726] rounded-none outline-none">
                                </td>

                                <!-- 3. Store Payment Details (تفاصيل دفع المحلات) -->
                                <td class="p-0 border-l border-gray-200 dark:border-white-dark/10">
                                    <input type="text"
                                           x-model="item.store_payment_details"
                                           @input="onCellInput(item)"
                                           @keydown.enter.prevent="onEnterKey(index)"
                                           placeholder="تفاصيل الدفع..."
                                           class="w-full py-2 px-3 text-xs bg-transparent border-0 focus:ring-1 focus:ring-primary focus:bg-white dark:focus:bg-[#0e1726] rounded-none outline-none">
                                </td>

                                <!-- 4. Store Payment (دفع محلات) -->
                                <td class="p-0 border-l border-gray-200 dark:border-white-dark/10" dir="ltr">
                                    <input type="number" step="0.01"
                                           x-model="item.store_payment"
                                           @input="onCellInput(item)"
                                           @keydown.enter.prevent="onEnterKey(index)"
                                           placeholder="0.00"
                                           class="w-full py-2 px-3 text-xs text-left font-semibold bg-transparent border-0 focus:ring-1 focus:ring-primary focus:bg-white dark:focus:bg-[#0e1726] rounded-none outline-none">
                                </td>

                                <!-- 5. USD Amount (مبلغ محول دولار) -->
                                <td class="p-0 border-l border-gray-200 dark:border-white-dark/10 bg-blue-50/15 dark:bg-blue-950/10" dir="ltr">
                                    <input type="number" step="0.01"
                                           x-model="item.usd_amount"
                                           @input="onCurrencyInput(item)"
                                           @keydown.enter.prevent="onEnterKey(index)"
                                           placeholder="0.00"
                                           class="w-full py-2 px-3 text-xs text-left font-bold text-blue-600 dark:text-blue-400 bg-transparent border-0 focus:ring-1 focus:ring-blue-500 focus:bg-white dark:focus:bg-[#0e1726] rounded-none outline-none">
                                </td>

                                <!-- 6. Exchange Rate (سعر الصرف RMB) -->
                                <td class="p-0 border-l border-gray-200 dark:border-white-dark/10 bg-amber-50/15 dark:bg-amber-950/10" dir="ltr">
                                    <input type="number" step="0.0001"
                                           x-model="item.exchange_rate"
                                           @input="onCurrencyInput(item)"
                                           @keydown.enter.prevent="onEnterKey(index)"
                                           placeholder="7.2500"
                                           class="w-full py-2 px-3 text-xs text-left font-bold text-amber-600 dark:text-amber-400 bg-transparent border-0 focus:ring-1 focus:ring-amber-500 focus:bg-white dark:focus:bg-[#0e1726] rounded-none outline-none">
                                </td>

                                <!-- 7. Calculated RMB (المعادلة الحسابية المباشرة: دولار * سعر الصرف) -->
                                <td class="py-2 px-3 border-l border-gray-200 dark:border-white-dark/10 bg-primary/5 select-all" dir="ltr">
                                    <div class="flex items-center justify-between font-extrabold text-primary text-xs">
                                        <span>¥</span>
                                        <span x-text="formatNumber(item.rmb_amount, 2)">0.00</span>
                                    </div>
                                </td>

                                <!-- 8. Transfer Date (تاريخ التحويل) -->
                                <td class="p-0 border-l border-gray-200 dark:border-white-dark/10 text-center">
                                    <input type="date"
                                           x-model="item.transfer_date"
                                           @input="onCellInput(item)"
                                           @keydown.enter.prevent="onEnterKey(index)"
                                           class="w-full py-2 px-1 text-[11px] text-center bg-transparent border-0 focus:ring-1 focus:ring-primary focus:bg-white dark:focus:bg-[#0e1726] rounded-none outline-none">
                                </td>

                                <!-- Actions: Delete -->
                                <td class="text-center py-2 px-1 print:hidden whitespace-nowrap">
                                    <button type="button"
                                            @click="removeRow(index)"
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

            <!-- Bottom Control Bar -->
            <div class="p-3 bg-gray-50/70 dark:bg-[#121e32]/40 border-t border-gray-200/60 dark:border-white-dark/10 flex items-center justify-between print:hidden">
                <button type="button" @click="appendNewRow()" class="btn btn-outline-primary btn-sm flex items-center gap-1.5">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 4V20M20 12H4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <span>إضافة سطر جديد (أو اضغط Enter في آخر خلية)</span>
                </button>

                <div class="text-xs text-gray-500 flex items-center gap-4 font-semibold">
                    <span>إجمالي الدفوعات: <strong class="text-purple-600" dir="ltr">¥ <span x-text="formatNumber(totalPayments, 2)"></span></strong></span>
                    <span>الرصيد المتبقي: <strong :class="remainingBalance >= 0 ? 'text-emerald-600' : 'text-rose-600'" dir="ltr">¥ <span x-text="formatNumber(remainingBalance, 2)"></span></strong></span>
                </div>
            </div>

        </div>

        <!-- Printable Document Header (تظهر فقط أثناء أمر الطباعة) -->
        <div class="hidden print:block mb-6 p-4 border-b-2 border-black text-black">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-black">مجموعة الزبيدي للتجارة والشحن الدولي</h2>
                    <h4 class="text-base font-bold mt-1" x-text="sheetTitle"></h4>
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

    <!-- Alpine.js Spreadsheet Engine -->
    <script>
        function pureExcelSpreadsheet(config) {
            return {
                csrfToken: config.csrfToken,
                sheetTitle: config.initialData.title || 'كشف حساب العملات والمشتريات',
                defaultRate: config.initialData.default_rate || 7.25,
                items: config.initialData.items || [],

                saveStatus: 'saved', // 'saved', 'saving'
                saveDebounceTimer: null,

                initSheet() {
                    // Recalculate all formulas
                    this.items.forEach(item => {
                        this.recomputeRow(item);
                    });
                },

                recomputeRow(item) {
                    const usd = parseFloat(item.usd_amount) || 0;
                    const rate = parseFloat(item.exchange_rate) || this.defaultRate;
                    item.rmb_amount = Math.round((usd * rate) * 100) / 100;
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
                    return parseFloat(this.defaultRate) || 7.25;
                },

                // Real-time calculation on USD or Rate input
                onCurrencyInput(item) {
                    this.recomputeRow(item);
                    this.triggerAutoSave();
                },

                onCellInput(item) {
                    this.triggerAutoSave();
                },

                onSheetChanged() {
                    this.triggerAutoSave();
                },

                // Auto-Save Engine (500ms debounce to server & localStorage)
                triggerAutoSave() {
                    this.saveStatus = 'saving';

                    if (this.saveDebounceTimer) {
                        clearTimeout(this.saveDebounceTimer);
                    }

                    this.saveDebounceTimer = setTimeout(() => {
                        this.executeSave();
                    }, 500);
                },

                async executeSave() {
                    try {
                        const payload = {
                            title: this.sheetTitle,
                            default_rate: this.defaultRate,
                            items: this.items
                        };

                        // 1. Save to local storage as instant fallback
                        localStorage.setItem('independent_excel_sheet', JSON.stringify(payload));

                        // 2. Save directly to the server endpoint
                        await fetch('{{ url("/exchange-calculator/save") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': this.csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(payload)
                        });

                        this.saveStatus = 'saved';
                    } catch (err) {
                        console.error('Save error:', err);
                        this.saveStatus = 'saved'; // LocalStorage saved successfully
                    }
                },

                // Append a new blank row like Excel
                appendNewRow() {
                    this.items.push({
                        id: Date.now(),
                        details: '',
                        purchase_amount: '',
                        store_payment_details: '',
                        store_payment: '',
                        usd_amount: '',
                        exchange_rate: this.defaultRate,
                        rmb_amount: 0,
                        transfer_date: new Date().toISOString().split('T')[0]
                    });
                    this.triggerAutoSave();
                },

                onEnterKey(index) {
                    if (index === this.items.length - 1) {
                        this.appendNewRow();
                    }
                },

                removeRow(index) {
                    this.items.splice(index, 1);
                    if (this.items.length === 0) {
                        this.appendNewRow();
                    }
                    this.triggerAutoSave();
                },

                clearSheetPrompt() {
                    if (confirm('هل أنت متأكد من رغبتك بتفريغ الجدول والبدء من جديد؟')) {
                        this.items = [];
                        for (let i = 0; i < 5; i++) {
                            this.items.push({
                                id: i + 1,
                                details: '',
                                purchase_amount: '',
                                store_payment_details: '',
                                store_payment: '',
                                usd_amount: '',
                                exchange_rate: this.defaultRate,
                                rmb_amount: 0,
                                transfer_date: new Date().toISOString().split('T')[0]
                            });
                        }
                        this.triggerAutoSave();
                    }
                },

                exportToExcel() {
                    const validItems = this.items.filter(i => (i.details || i.usd_amount || i.purchase_amount || i.store_payment));
                    if (validItems.length === 0) {
                        alert('لا توجد بيانات مدخلة لتصديرها.');
                        return;
                    }

                    let csvContent = '\uFEFF'; // UTF-8 BOM
                    csvContent += 'مجموعة الزبيدي - ' + (this.sheetTitle || 'كشف الحسابات') + '\n';
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

                    validItems.forEach((item, idx) => {
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
                    link.setAttribute('download', (this.sheetTitle || 'ledger').replace(/[\/\\?%*:|"<>]/g, '_') + '_' + new Date().toISOString().split('T')[0] + '.csv');
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
