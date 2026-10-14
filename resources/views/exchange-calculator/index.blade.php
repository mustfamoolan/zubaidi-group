<x-layout.custom-default>
    <!-- Include SheetJS for real native Excel (.xlsx) export -->
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

    <!-- Custom Print Stylesheet: Ensures ONLY clean formal document is printed -->
    <style>
        @media print {
            /* 1. Hide whole website layout chrome */
            header, footer, nav, aside, .toggle-sidebar,
            .main-container > aside, .main-content > header, .main-content > footer,
            .screen_loader, [x-data="scrollToTop"], .theme-customiser,
            .screen-only-section, .print\:hidden, .no-print {
                display: none !important;
            }

            /* 2. Reset layout containers */
            html, body, .main-container, .main-content, .dvanimation {
                margin: 0 !important;
                padding: 0 !important;
                background: #ffffff !important;
                color: #000000 !important;
                width: 100% !important;
                min-height: auto !important;
                overflow: visible !important;
            }

            /* 3. Page Setup (A4 Landscape for 10-column financial ledger) */
            @page {
                size: A4 landscape;
                margin: 7mm 6mm;
            }

            /* 4. Display ONLY the dedicated print container */
            #realPrintSection {
                display: block !important;
                visibility: visible !important;
                position: relative !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                box-sizing: border-box !important;
                font-family: 'Segoe UI', Tahoma, Arial, sans-serif !important;
                color: #000000 !important;
                direction: rtl !important;
            }

            /* 5. Clean, crisp borders for official printing */
            #realPrintSection table {
                width: 100% !important;
                border-collapse: collapse !important;
                page-break-inside: auto !important;
            }

            #realPrintSection tr {
                page-break-inside: avoid !important;
                page-break-after: auto !important;
            }

            #realPrintSection th, #realPrintSection td {
                border: 1px solid #444444 !important;
                padding: 4px 6px !important;
                font-size: 11px !important;
                line-height: 1.3 !important;
                color: #000000 !important;
            }

            #realPrintSection th {
                background-color: #f1f3f5 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                font-weight: bold !important;
            }

            #realPrintSection .mini-kpi-box {
                border: 1px solid #333333 !important;
                background-color: #fafafa !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>

    <div x-data="pureExcelSpreadsheet({
            initialData: {{ json_encode($data) }},
            csrfToken: '{{ csrf_token() }}'
         })"
         x-init="initSheet()"
         class="space-y-6">

        <!-- ======================================================== -->
        <!-- 🖥️ 1. قسم الشاشة (يظهر فقط في المتصفح ويختفي تماماً أثناء الطباعة) -->
        <!-- ======================================================== -->
        <div class="screen-only-section space-y-6 print:hidden">

            <!-- Top Header & Status -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <ul class="flex space-x-2 rtl:space-x-reverse text-sm mb-2">
                        <li>
                            <a href="{{ url('/') }}" class="text-primary hover:underline">لوحة التحكم</a>
                        </li>
                        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                            <span class="text-black dark:text-white-light font-medium">جدول الحسابات وسعر الصرف والعمولات</span>
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
                                       placeholder="عنوان جدول الحسابات..."
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
                                حاسبة الصرف والعمولات - حدد العملة واكتب المبلغ وسعر الصرف ونسبة العمولة، وتُحسب المبالغ والمجاميع بالعراقي فورياً
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center flex-wrap gap-2">
                    <button type="button" @click="appendNewRow()" class="btn btn-primary btn-sm flex items-center gap-1.5 shadow-sm">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 4V20M20 12H4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        <span>إضافة حركة جديدة</span>
                    </button>

                    <button type="button" @click="exportToExcel()" class="btn btn-outline-success btn-sm flex items-center gap-1.5 shadow-sm" title="تصدير ملف إكسل منسق حقيقي .xlsx">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 16.004V17C4 18.8856 4 19.8284 4.58579 20.4142C5.17157 21 6.11438 21 8 21H16C17.8856 21 18.8284 21 19.4142 20.4142C20 19.8284 20 18.8856 20 17V16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            <path opacity="0.5" d="M12 3V15M12 15L8.5 11.5M12 15L15.5 11.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>تصدير Excel (.xlsx)</span>
                    </button>

                    <button type="button" @click="triggerPrint()" class="btn btn-outline-secondary btn-sm flex items-center gap-1.5 shadow-sm" title="طباعة رسمية A4 للجدول والمجاميع">
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

            <!-- New KPI Summary Cards (بطاقات المجاميع التفاعلية للشاشة) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-3.5">

                <!-- 1. كم دولار ($) -->
                <div class="panel p-3.5 bg-gradient-to-br from-blue-50 to-indigo-50/50 dark:from-blue-950/20 dark:to-indigo-950/20 border border-blue-200/70 dark:border-blue-900/40 rounded-xl relative overflow-hidden shadow-sm">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-bold text-blue-700 dark:text-blue-300">مجموع الدولار</span>
                        <span class="w-7 h-7 rounded-lg bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-xs">
                            $
                        </span>
                    </div>
                    <div class="text-xl font-extrabold text-blue-800 dark:text-blue-200 tracking-tight" dir="ltr">
                        $ <span x-text="formatNumber(totalUsd, 2)">0.00</span>
                    </div>
                    <div class="mt-1.5 text-[10px] text-blue-600/80 dark:text-blue-300/70 flex items-center justify-between">
                        <span>دولار أمريكي (USD)</span>
                        <span class="badge bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200 text-[10px] py-0 px-1" x-text="countCurrency('USD') + ' حركات'"></span>
                    </div>
                </div>

                <!-- 2. كم إماراتي (AED) -->
                <div class="panel p-3.5 bg-gradient-to-br from-emerald-50 to-teal-50/50 dark:from-emerald-950/20 dark:to-teal-950/20 border border-emerald-200/70 dark:border-emerald-900/40 rounded-xl relative overflow-hidden shadow-sm">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-bold text-emerald-700 dark:text-emerald-300">مجموع الإماراتي</span>
                        <span class="w-7 h-7 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold text-[11px]">
                            د.إ
                        </span>
                    </div>
                    <div class="text-xl font-extrabold text-emerald-800 dark:text-emerald-200 tracking-tight" dir="ltr">
                        د.إ <span x-text="formatNumber(totalAed, 2)">0.00</span>
                    </div>
                    <div class="mt-1.5 text-[10px] text-emerald-600/80 dark:text-emerald-300/70 flex items-center justify-between">
                        <span>درهم إماراتي (AED)</span>
                        <span class="badge bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-200 text-[10px] py-0 px-1" x-text="countCurrency('AED') + ' حركات'"></span>
                    </div>
                </div>

                <!-- 3. كم ريمبي (RMB) -->
                <div class="panel p-3.5 bg-gradient-to-br from-amber-50 to-orange-50/50 dark:from-amber-950/20 dark:to-orange-950/20 border border-amber-200/70 dark:border-amber-900/40 rounded-xl relative overflow-hidden shadow-sm">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-bold text-amber-700 dark:text-amber-300">مجموع الريمبي</span>
                        <span class="w-7 h-7 rounded-lg bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold text-xs">
                            ¥
                        </span>
                    </div>
                    <div class="text-xl font-extrabold text-amber-800 dark:text-amber-200 tracking-tight" dir="ltr">
                        ¥ <span x-text="formatNumber(totalRmb, 2)">0.00</span>
                    </div>
                    <div class="mt-1.5 text-[10px] text-amber-600/80 dark:text-amber-300/70 flex items-center justify-between">
                        <span>يوان صيني (RMB)</span>
                        <span class="badge bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-200 text-[10px] py-0 px-1" x-text="countCurrency('RMB') + ' حركات'"></span>
                    </div>
                </div>

                <!-- 4. كم عراقي (المبالغ قبل العمولة) -->
                <div class="panel p-3.5 bg-gradient-to-br from-cyan-50 to-sky-50/50 dark:from-cyan-950/20 dark:to-sky-950/20 border border-cyan-200/70 dark:border-cyan-900/40 rounded-xl relative overflow-hidden shadow-sm">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-bold text-cyan-700 dark:text-cyan-300">مجموع العراقي</span>
                        <span class="w-7 h-7 rounded-lg bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 flex items-center justify-center font-bold text-[10px]">
                            د.ع
                        </span>
                    </div>
                    <div class="text-xl font-extrabold text-cyan-800 dark:text-cyan-200 tracking-tight" dir="ltr">
                        <span x-text="formatNumber(totalIqdAmount, 0)">0</span>
                    </div>
                    <div class="mt-1.5 text-[10px] text-cyan-600/80 dark:text-cyan-300/70 flex items-center justify-between">
                        <span>مبالغ الصرف (د.ع)</span>
                        <span class="text-[10px] text-gray-500">قبل العمولات</span>
                    </div>
                </div>

                <!-- 5. كم عمولات (إجمالي مبالغ العمولات بالعراقي) -->
                <div class="panel p-3.5 bg-gradient-to-br from-purple-50 to-pink-50/50 dark:from-purple-950/20 dark:to-pink-950/20 border border-purple-200/70 dark:border-purple-900/40 rounded-xl relative overflow-hidden shadow-sm">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-bold text-purple-700 dark:text-purple-300">مجموع العمولات</span>
                        <span class="w-7 h-7 rounded-lg bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center font-bold text-[11px]">
                            %
                        </span>
                    </div>
                    <div class="text-xl font-extrabold text-purple-800 dark:text-purple-200 tracking-tight" dir="ltr">
                        <span x-text="formatNumber(totalCommissionIqd, 0)">0</span> <span class="text-xs font-medium">د.ع</span>
                    </div>
                    <div class="mt-1.5 text-[10px] text-purple-600/80 dark:text-purple-300/70 flex items-center justify-between">
                        <span>إجمالي العمولات المحسوبة</span>
                        <span class="text-[10px] font-semibold text-purple-700 dark:text-purple-300">دينار عراقي</span>
                    </div>
                </div>

                <!-- 6. المجموع الكلي النهائي بالعراقي (المبلغ بعد الصرف + العمولة) -->
                <div class="panel p-3.5 bg-gradient-to-br from-[#0e1726] to-[#1e293b] text-white dark:from-[#1b2e4b] dark:to-[#0e1726] border border-gray-700 dark:border-blue-900/40 rounded-xl relative overflow-hidden shadow-md">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-bold text-amber-300">المجموع الكلي عراقي</span>
                        <span class="w-7 h-7 rounded-lg bg-amber-400/20 text-amber-300 flex items-center justify-center font-black text-xs">
                            ∑
                        </span>
                    </div>
                    <div class="text-xl font-extrabold text-white tracking-tight" dir="ltr">
                        <span x-text="formatNumber(grandTotalIqd, 0)">0</span> <span class="text-xs text-amber-300 font-bold">د.ع</span>
                    </div>
                    <div class="mt-1.5 text-[10px] text-gray-300 flex items-center justify-between">
                        <span>(المبالغ + العمولات)</span>
                        <span class="badge bg-amber-400 text-gray-900 font-bold text-[10px] py-0 px-1.5">الصافي الكلي</span>
                    </div>
                </div>

            </div>

            <!-- Excel Spreadsheet Table (جدول الإدخال التفاعلي للشاشة) -->
            <div class="panel p-0 border border-gray-300 dark:border-white-dark/20 rounded-xl overflow-hidden shadow-sm">

                <div class="table-responsive max-h-[700px] overflow-auto">
                    <table class="table-striped table-hover w-full text-xs text-right border-collapse select-none">
                        <thead class="sticky top-0 bg-[#eaedf1] dark:bg-[#0e1726] z-10 border-b border-gray-300 dark:border-white-dark/20 text-gray-800 dark:text-gray-200 font-bold text-[11px] shadow-sm">
                            <tr>
                                <th class="w-10 text-center py-2.5 px-2 border-l border-gray-300 dark:border-white-dark/10">#</th>
                                <th class="w-32 text-center py-2.5 px-2 border-l border-gray-300 dark:border-white-dark/10">رقم الفاتورة</th>
                                <th class="w-36 py-2.5 px-3 border-l border-gray-300 dark:border-white-dark/10 text-center">العملة</th>
                                <th class="min-w-[140px] py-2.5 px-3 text-left border-l border-gray-300 dark:border-white-dark/10" dir="ltr">مبلغ الحركة</th>
                                <th class="min-w-[130px] py-2.5 px-3 text-left border-l border-gray-300 dark:border-white-dark/10" dir="ltr">سعر الصرف (د.ع)</th>
                                <th class="min-w-[160px] py-2.5 px-3 text-left text-cyan-800 dark:text-cyan-300 border-l border-gray-300 dark:border-white-dark/10 bg-cyan-50/40 dark:bg-cyan-950/20 font-black" dir="ltr">
                                    مبلغ عراقي (د.ع)
                                </th>
                                <th class="min-w-[150px] py-2.5 px-3 text-center border-l border-gray-300 dark:border-white-dark/10">
                                    العمولة (النسبة)
                                </th>
                                <th class="min-w-[150px] py-2.5 px-3 text-left text-purple-800 dark:text-purple-300 border-l border-gray-300 dark:border-white-dark/10 bg-purple-50/40 dark:bg-purple-950/20 font-black" dir="ltr">
                                    مبلغ العمولة عراقي
                                </th>
                                <th class="min-w-[170px] py-2.5 px-3 text-left text-emerald-800 dark:text-emerald-300 border-l border-gray-300 dark:border-white-dark/10 bg-emerald-50/50 dark:bg-emerald-950/30 font-black" dir="ltr">
                                    المجموع عراقي (د.ع)
                                </th>
                                <th class="min-w-[160px] py-2.5 px-3 border-l border-gray-300 dark:border-white-dark/10">البيان / ملاحظات</th>
                                <th class="w-12 text-center py-2.5 px-2">حذف</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200/80 dark:divide-white-dark/10 font-medium">
                            <template x-for="(item, index) in items" :key="index">
                                <tr class="group hover:bg-primary/5 transition-colors focus-within:bg-blue-50/30 dark:focus-within:bg-[#132238]">
                                    <!-- 1. Index -->
                                    <td class="text-center font-bold text-gray-500 py-1.5 px-2 border-l border-gray-200 dark:border-white-dark/10 bg-gray-50/40 dark:bg-[#0e1726]/40" x-text="index + 1"></td>

                                    <!-- 2. رقم الفاتورة (Invoice No - يدعم نصوص وأرقام) -->
                                    <td class="p-0 border-l border-gray-200 dark:border-white-dark/10 text-center">
                                        <input type="text"
                                               x-model="item.transaction_no"
                                               @input="onCellInput(item)"
                                               @keydown.enter.prevent="onEnterKey(index)"
                                               placeholder="رقم الفاتورة..."
                                               class="w-full py-2 px-2 text-xs text-center font-semibold bg-transparent border-0 focus:ring-1 focus:ring-primary focus:bg-white dark:focus:bg-[#0e1726] rounded-none outline-none">
                                    </td>

                                    <!-- 3. العملة (Currency Select) -->
                                    <td class="p-0 border-l border-gray-200 dark:border-white-dark/10">
                                        <select x-model="item.currency"
                                                @change="onCurrencySelectChange(item)"
                                                class="w-full py-2 px-2 text-xs font-bold bg-transparent border-0 focus:ring-1 focus:ring-primary focus:bg-white dark:focus:bg-[#0e1726] rounded-none outline-none cursor-pointer"
                                                :class="{
                                                    'text-blue-600 dark:text-blue-400': item.currency === 'USD',
                                                    'text-emerald-600 dark:text-emerald-400': item.currency === 'AED',
                                                    'text-amber-600 dark:text-amber-400': item.currency === 'RMB'
                                                }">
                                            <option value="USD">💵 دولار أمريكي ($ USD)</option>
                                            <option value="AED">🇦🇪 درهم إماراتي (AED)</option>
                                            <option value="RMB">🇨🇳 ريمبي صيني (¥ RMB)</option>
                                        </select>
                                    </td>

                                    <!-- 4. مبلغ الحركة (Amount) -->
                                    <td class="p-0 border-l border-gray-200 dark:border-white-dark/10" dir="ltr">
                                        <div class="flex items-center px-2">
                                            <span class="text-gray-400 font-bold text-xs select-none" x-text="getCurrencySymbol(item.currency)"></span>
                                            <input type="number" step="any"
                                                   x-model="item.amount"
                                                   @input="onRowValuesChanged(item)"
                                                   @keydown.enter.prevent="onEnterKey(index)"
                                                   placeholder="0.00"
                                                   class="w-full py-2 px-2 text-xs text-left font-bold bg-transparent border-0 focus:ring-1 focus:ring-primary focus:bg-white dark:focus:bg-[#0e1726] rounded-none outline-none">
                                        </div>
                                    </td>

                                    <!-- 5. سعر الصرف (Exchange Rate) -->
                                    <td class="p-0 border-l border-gray-200 dark:border-white-dark/10" dir="ltr">
                                        <input type="number" step="any"
                                               x-model="item.exchange_rate"
                                               @input="onRowValuesChanged(item)"
                                               @keydown.enter.prevent="onEnterKey(index)"
                                               placeholder="سعر الصرف..."
                                               class="w-full py-2 px-3 text-xs text-left font-semibold text-gray-700 dark:text-gray-300 bg-transparent border-0 focus:ring-1 focus:ring-primary focus:bg-white dark:focus:bg-[#0e1726] rounded-none outline-none">
                                    </td>

                                    <!-- 6. مبلغ عراقي (محسوب تلقائياً = المبلغ * سعر الصرف) -->
                                    <td class="py-2 px-3 border-l border-gray-200 dark:border-white-dark/10 bg-cyan-50/20 dark:bg-cyan-950/10 font-bold select-all" dir="ltr">
                                        <div class="flex items-center justify-between text-cyan-800 dark:text-cyan-300 font-extrabold text-xs">
                                            <span x-text="formatNumber(item.iqd_amount, 2)">0.00</span>
                                            <span class="text-[10px] text-gray-500 font-normal">د.ع</span>
                                        </div>
                                    </td>

                                    <!-- 7. العمولة (النسبة) مع خيار بالألف ‰ أو % بالمئة -->
                                    <td class="p-0 border-l border-gray-200 dark:border-white-dark/10">
                                        <div class="flex items-center gap-1 px-1">
                                            <input type="number" step="any"
                                                   x-model="item.commission_rate"
                                                   @input="onRowValuesChanged(item)"
                                                   @keydown.enter.prevent="onEnterKey(index)"
                                                   placeholder="1"
                                                   class="w-16 py-2 px-1 text-xs text-center font-bold text-purple-700 dark:text-purple-300 bg-transparent border-0 focus:ring-1 focus:ring-purple-500 focus:bg-white dark:focus:bg-[#0e1726] rounded-none outline-none">

                                            <!-- اختيار نوع النسبة: بالألف ‰ (الافتراضي) أو % بالمئة -->
                                            <select x-model="item.commission_type"
                                                    @change="onRowValuesChanged(item)"
                                                    class="py-1 px-1 text-[11px] font-bold bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 rounded border-0 outline-none cursor-pointer">
                                                <option value="per_thousand">‰ بالألف</option>
                                                <option value="percent">% بالمئة</option>
                                            </select>
                                        </div>
                                    </td>

                                    <!-- 8. مبلغ العمولة عراقي (محسوب تلقائياً) -->
                                    <td class="py-2 px-3 border-l border-gray-200 dark:border-white-dark/10 bg-purple-50/20 dark:bg-purple-950/10 select-all" dir="ltr">
                                        <div class="flex items-center justify-between font-extrabold text-purple-700 dark:text-purple-300 text-xs">
                                            <span x-text="formatNumber(item.commission_iqd, 2)">0.00</span>
                                            <span class="text-[10px] text-gray-500 font-normal">د.ع</span>
                                        </div>
                                    </td>

                                    <!-- 9. المجموع عراقي (المبلغ بعد سعر الصرف + العمولة) -->
                                    <td class="py-2 px-3 border-l border-gray-200 dark:border-white-dark/10 bg-emerald-50/25 dark:bg-emerald-950/20 select-all" dir="ltr">
                                        <div class="flex items-center justify-between font-black text-emerald-700 dark:text-emerald-300 text-xs">
                                            <span x-text="formatNumber(item.total_iqd, 2)">0.00</span>
                                            <span class="text-[10px] text-emerald-600 font-semibold">د.ع</span>
                                        </div>
                                    </td>

                                    <!-- 10. البيان / ملاحظات -->
                                    <td class="p-0 border-l border-gray-200 dark:border-white-dark/10">
                                        <input type="text"
                                               x-model="item.notes"
                                               @input="onCellInput(item)"
                                               @keydown.enter.prevent="onEnterKey(index)"
                                               placeholder="ملاحظات أو بيان الحركة..."
                                               class="w-full py-2 px-3 text-xs bg-transparent border-0 focus:ring-1 focus:ring-primary focus:bg-white dark:focus:bg-[#0e1726] rounded-none outline-none">
                                    </td>

                                    <!-- 11. حذف -->
                                    <td class="text-center py-2 px-1 whitespace-nowrap">
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

                        <!-- Table Footer: Totals Row (سطر المجاميع الإجمالية بالأسفل) -->
                        <tfoot class="bg-[#eaedf0] dark:bg-[#14233c] font-bold text-xs text-gray-800 dark:text-white-light border-t-2 border-gray-300 dark:border-white-dark/20 sticky bottom-0 z-10 shadow-md">
                            <tr>
                                <td class="text-center py-3 px-2 border-l border-gray-300 dark:border-white-dark/20">∑</td>
                                <td class="text-center py-3 px-2 border-l border-gray-300 dark:border-white-dark/20 text-gray-500" x-text="items.length + ' حركات'"></td>
                                <td class="py-3 px-3 border-l border-gray-300 dark:border-white-dark/20 font-black text-center">المجاميع الإجمالية</td>

                                <td class="py-3 px-3 text-left border-l border-gray-300 dark:border-white-dark/20 text-gray-600 dark:text-gray-300 text-[11px]" dir="ltr">
                                    <span>حسب العملة بالأعلى ☝️</span>
                                </td>

                                <td class="py-3 px-3 border-l border-gray-300 dark:border-white-dark/20 text-center text-gray-400">-</td>

                                <!-- Total IQD Amount -->
                                <td class="py-3 px-3 text-left border-l border-gray-300 dark:border-white-dark/20 font-black text-cyan-800 dark:text-cyan-300 text-sm" dir="ltr">
                                    <span x-text="formatNumber(totalIqdAmount, 2)">0.00</span> <span class="text-[10px] font-normal">د.ع</span>
                                </td>

                                <!-- Commission label -->
                                <td class="py-3 px-3 text-center border-l border-gray-300 dark:border-white-dark/20 text-gray-500 font-normal">إجمالي العمولات:</td>

                                <!-- Total Commission IQD -->
                                <td class="py-3 px-3 text-left border-l border-gray-300 dark:border-white-dark/20 font-black text-purple-700 dark:text-purple-300 text-sm" dir="ltr">
                                    <span x-text="formatNumber(totalCommissionIqd, 2)">0.00</span> <span class="text-[10px] font-normal">د.ع</span>
                                </td>

                                <!-- Grand Total IQD -->
                                <td class="py-3 px-3 text-left border-l border-gray-300 dark:border-white-dark/20 font-black text-emerald-700 dark:text-emerald-300 text-base" dir="ltr">
                                    <span x-text="formatNumber(grandTotalIqd, 2)">0.00</span> <span class="text-[10px] font-bold">د.ع</span>
                                </td>

                                <td class="py-3 px-3 text-center border-l border-gray-300 dark:border-white-dark/20 text-gray-500 text-[10px]">الصافي النهائي</td>
                                <td class="py-3 px-2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Bottom Control Bar -->
                <div class="p-3 bg-gray-50/70 dark:bg-[#121e32]/40 border-t border-gray-200/60 dark:border-white-dark/10 flex items-center justify-between">
                    <button type="button" @click="appendNewRow()" class="btn btn-outline-primary btn-sm flex items-center gap-1.5">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 4V20M20 12H4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        <span>إضافة حركة جديدة (أو اضغط Enter في آخر خلية)</span>
                    </button>

                    <div class="text-xs text-gray-500 flex items-center gap-4 font-semibold">
                        <span>مجموع العمولات: <strong class="text-purple-600" dir="ltr"><span x-text="formatNumber(totalCommissionIqd, 0)"></span> د.ع</strong></span>
                        <span>المجموع الكلي: <strong class="text-emerald-600" dir="ltr"><span x-text="formatNumber(grandTotalIqd, 0)"></span> د.ع</strong></span>
                    </div>
                </div>

            </div>

        </div>

        <!-- ======================================================== -->
        <!-- 🖨️ 2. قسم الطباعة الحقيقي المستقل (يظهر فقط أثناء أمر الطباعة A4 Landscape) -->
        <!-- ======================================================== -->
        <div id="realPrintSection" class="hidden">

            <!-- رأس مستند الطباعة الرسمي -->
            <div class="border-b-2 border-black pb-3 mb-3">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-xl font-black text-black tracking-tight" style="font-size: 19px; margin: 0;">
                            مجموعة الزبيدي للتجارة والشحن الدولي
                        </h1>
                        <h2 class="text-sm font-bold text-gray-800 mt-1" style="font-size: 14px; margin: 2px 0 0 0;" x-text="sheetTitle || 'جدول الحسابات وسعر الصرف والعمولات'">
                        </h2>
                    </div>
                    <div class="text-left text-xs font-semibold text-gray-700" style="font-size: 11px; line-height: 1.4;">
                        <p style="margin: 0;">تاريخ الكشف: <strong>{{ date('Y-m-d') }}</strong></p>
                        <p style="margin: 0;">وقت الطباعة: <strong>{{ date('h:i A') }}</strong></p>
                        <p style="margin: 0;">عدد الحركات: <strong x-text="printableItems.length"></strong></p>
                    </div>
                </div>
            </div>

            <!-- شريط المجاميع الصغيرة المخصصة للطباعة (حقول صغيرة متراصة وواضحة) -->
            <div class="grid grid-cols-6 gap-2 mb-3 text-center" style="margin-bottom: 10px;">

                <!-- 1. دولار -->
                <div class="mini-kpi-box rounded p-1.5" style="border: 1px solid #333; padding: 4px 6px; background: #fbfbfb;">
                    <div style="font-size: 10px; font-weight: bold; color: #555; margin-bottom: 2px;">مجموع الدولار</div>
                    <div style="font-size: 12px; font-weight: 900; color: #000;" dir="ltr">
                        $ <span x-text="formatNumber(totalUsd, 2)">0.00</span>
                    </div>
                </div>

                <!-- 2. إماراتي -->
                <div class="mini-kpi-box rounded p-1.5" style="border: 1px solid #333; padding: 4px 6px; background: #fbfbfb;">
                    <div style="font-size: 10px; font-weight: bold; color: #555; margin-bottom: 2px;">مجموع الإماراتي</div>
                    <div style="font-size: 12px; font-weight: 900; color: #000;" dir="ltr">
                        د.إ <span x-text="formatNumber(totalAed, 2)">0.00</span>
                    </div>
                </div>

                <!-- 3. ريمبي -->
                <div class="mini-kpi-box rounded p-1.5" style="border: 1px solid #333; padding: 4px 6px; background: #fbfbfb;">
                    <div style="font-size: 10px; font-weight: bold; color: #555; margin-bottom: 2px;">مجموع الريمبي</div>
                    <div style="font-size: 12px; font-weight: 900; color: #000;" dir="ltr">
                        ¥ <span x-text="formatNumber(totalRmb, 2)">0.00</span>
                    </div>
                </div>

                <!-- 4. عراقي -->
                <div class="mini-kpi-box rounded p-1.5" style="border: 1px solid #333; padding: 4px 6px; background: #fbfbfb;">
                    <div style="font-size: 10px; font-weight: bold; color: #555; margin-bottom: 2px;">مجموع العراقي</div>
                    <div style="font-size: 12px; font-weight: 900; color: #000;" dir="ltr">
                        <span x-text="formatNumber(totalIqdAmount, 0)">0</span> <span style="font-size: 9px; font-weight: normal;">د.ع</span>
                    </div>
                </div>

                <!-- 5. عمولات -->
                <div class="mini-kpi-box rounded p-1.5" style="border: 1px solid #333; padding: 4px 6px; background: #fbfbfb;">
                    <div style="font-size: 10px; font-weight: bold; color: #555; margin-bottom: 2px;">مجموع العمولات</div>
                    <div style="font-size: 12px; font-weight: 900; color: #000;" dir="ltr">
                        <span x-text="formatNumber(totalCommissionIqd, 0)">0</span> <span style="font-size: 9px; font-weight: normal;">د.ع</span>
                    </div>
                </div>

                <!-- 6. المجموع الكلي -->
                <div class="mini-kpi-box rounded p-1.5" style="border: 2px solid #000; padding: 4px 6px; background: #eee;">
                    <div style="font-size: 10px; font-weight: 900; color: #000; margin-bottom: 2px;">المجموع النهائي (الصافي)</div>
                    <div style="font-size: 13px; font-weight: 900; color: #000;" dir="ltr">
                        <span x-text="formatNumber(grandTotalIqd, 0)">0</span> <span style="font-size: 10px; font-weight: bold;">د.ع</span>
                    </div>
                </div>

            </div>

            <!-- جدول الطباعة الحقيقي (نصوص صافية وحدود دقيقة بدون أزرار أو حقول إدخال) -->
            <table style="width: 100%; border-collapse: collapse; font-size: 11px; text-align: right;">
                <thead>
                    <tr style="background-color: #f1f3f5; font-weight: bold;">
                        <th style="width: 4%; text-align: center; border: 1px solid #444; padding: 5px;">#</th>
                        <th style="width: 10%; text-align: center; border: 1px solid #444; padding: 5px;">رقم الفاتورة</th>
                        <th style="width: 12%; text-align: center; border: 1px solid #444; padding: 5px;">العملة</th>
                        <th style="width: 12%; text-align: left; border: 1px solid #444; padding: 5px;" dir="ltr">مبلغ الحركة</th>
                        <th style="width: 10%; text-align: left; border: 1px solid #444; padding: 5px;" dir="ltr">سعر الصرف</th>
                        <th style="width: 13%; text-align: left; border: 1px solid #444; padding: 5px;" dir="ltr">مبلغ عراقي (د.ع)</th>
                        <th style="width: 9%; text-align: center; border: 1px solid #444; padding: 5px;">العمولة</th>
                        <th style="width: 12%; text-align: left; border: 1px solid #444; padding: 5px;" dir="ltr">مبلغ العمولة عراقي</th>
                        <th style="width: 14%; text-align: left; border: 1px solid #444; padding: 5px; font-weight: 900;" dir="ltr">المجموع عراقي (د.ع)</th>
                        <th style="width: 15%; text-align: right; border: 1px solid #444; padding: 5px;">البيان / ملاحظات</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(item, pIndex) in printableItems" :key="'print-' + pIndex">
                        <tr style="page-break-inside: avoid;">
                            <td style="text-align: center; border: 1px solid #555; padding: 4px;" x-text="pIndex + 1"></td>
                            <td style="text-align: center; font-weight: bold; border: 1px solid #555; padding: 4px;" x-text="item.transaction_no || '-'"></td>
                            <td style="text-align: center; font-weight: 600; border: 1px solid #555; padding: 4px;" x-text="getPrintCurrencyLabel(item.currency)"></td>
                            <td style="text-align: left; font-weight: bold; border: 1px solid #555; padding: 4px;" dir="ltr">
                                <span x-text="getCurrencySymbol(item.currency)"></span> <span x-text="formatNumber(item.amount, 2)"></span>
                            </td>
                            <td style="text-align: left; border: 1px solid #555; padding: 4px;" dir="ltr" x-text="formatNumber(item.exchange_rate, 2)"></td>
                            <td style="text-align: left; font-weight: bold; border: 1px solid #555; padding: 4px;" dir="ltr" x-text="formatNumber(item.iqd_amount, 2)"></td>
                            <td style="text-align: center; border: 1px solid #555; padding: 4px;" x-text="item.commission_type === 'per_thousand' ? (item.commission_rate || 0) + ' ‰ بالألف' : (item.commission_rate || 0) + ' %'"></td>
                            <td style="text-align: left; font-weight: bold; border: 1px solid #555; padding: 4px;" dir="ltr" x-text="formatNumber(item.commission_iqd, 2)"></td>
                            <td style="text-align: left; font-weight: 900; border: 1px solid #555; padding: 4px; background-color: #fafafa;" dir="ltr" x-text="formatNumber(item.total_iqd, 2)"></td>
                            <td style="text-align: right; border: 1px solid #555; padding: 4px;" x-text="item.notes || '-'"></td>
                        </tr>
                    </template>
                </tbody>
                <tfoot>
                    <tr style="background-color: #eaedf0; font-weight: 900; border-top: 2px solid #000; border-bottom: 2px solid #000;">
                        <td colspan="3" style="text-align: center; border: 1px solid #444; padding: 5px; font-weight: bold;">
                            المجاميع الإجمالية (<span x-text="printableItems.length"></span> حركة)
                        </td>
                        <td colspan="2" style="text-align: center; border: 1px solid #444; padding: 5px; font-size: 10px; color: #555;">
                            حسب ملخص العملات بالأعلى ☝️
                        </td>
                        <td style="text-align: left; border: 1px solid #444; padding: 5px;" dir="ltr">
                            <span x-text="formatNumber(totalIqdAmount, 2)"></span> د.ع
                        </td>
                        <td style="text-align: center; border: 1px solid #444; padding: 5px; font-size: 10px;">إجمالي العمولات:</td>
                        <td style="text-align: left; border: 1px solid #444; padding: 5px;" dir="ltr">
                            <span x-text="formatNumber(totalCommissionIqd, 2)"></span> د.ع
                        </td>
                        <td style="text-align: left; border: 1px solid #444; padding: 5px; font-size: 13px; font-weight: 900;" dir="ltr">
                            <span x-text="formatNumber(grandTotalIqd, 2)"></span> د.ع
                        </td>
                        <td style="text-align: center; border: 1px solid #444; padding: 5px; font-size: 10px;">الصافي النهائي</td>
                    </tr>
                </tfoot>
            </table>

            <!-- تواقيع واعتماد الطباعة -->
            <div style="margin-top: 25px; padding-top: 10px; display: flex; justify-content: space-between; font-size: 11px; font-weight: bold;">
                <div style="text-align: center; width: 200px; border-top: 1px dashed #666; padding-top: 5px;">
                    توقيع المحاسب المختص
                </div>
                <div style="text-align: center; width: 200px; border-top: 1px dashed #666; padding-top: 5px;">
                    الختم والاعتماد
                </div>
            </div>

        </div>

    </div>

    <!-- Alpine.js Spreadsheet Engine -->
    <script>
        function pureExcelSpreadsheet(config) {
            return {
                csrfToken: config.csrfToken,
                sheetTitle: config.initialData.title || 'جدول الحسابات وسعر الصرف والعمولات',
                items: config.initialData.items || [],

                saveStatus: 'saved', // 'saved', 'saving'
                saveDebounceTimer: null,

                initSheet() {
                    // Recalculate all formulas on load
                    this.items.forEach(item => {
                        this.recomputeRow(item);
                    });
                },

                // حساب الصف الواحد لحظياً
                recomputeRow(item) {
                    const amount = parseFloat(item.amount) || 0;
                    const rate = parseFloat(item.exchange_rate) || 0;

                    // 1. مبلغ عراقي = مبلغ الحركة × سعر الصرف
                    item.iqd_amount = Math.round((amount * rate) * 100) / 100;

                    // 2. نسبة العمولة
                    const commRate = parseFloat(item.commission_rate) || 0;
                    const isPerThousand = (item.commission_type || 'per_thousand') === 'per_thousand';
                    const factor = isPerThousand ? (commRate / 1000) : (commRate / 100);

                    // 3. مبلغ العمولة عراقي = مبلغ عراقي × النسبة
                    item.commission_iqd = Math.round((item.iqd_amount * factor) * 100) / 100;

                    // 4. المجموع عراقي = المبلغ بعد سعر الصرف + العمولة
                    item.total_iqd = Math.round((item.iqd_amount + item.commission_iqd) * 100) / 100;
                },

                // Helper: رمز العملة
                getCurrencySymbol(currency) {
                    switch (currency) {
                        case 'USD': return '$';
                        case 'AED': return 'د.إ';
                        case 'RMB': return '¥';
                        default: return '';
                    }
                },

                getPrintCurrencyLabel(currency) {
                    switch (currency) {
                        case 'USD': return 'دولار أمريكي ($)';
                        case 'AED': return 'درهم إماراتي (AED)';
                        case 'RMB': return 'ريمبي صيني (¥)';
                        default: return currency || 'USD';
                    }
                },

                // الحركات الجاهزة للطباعة والتصدير (استثناء الأسطر الفارغة بالكامل)
                get printableItems() {
                    const filtered = this.items.filter(i => (parseFloat(i.amount) > 0 || parseFloat(i.exchange_rate) > 0 || (i.notes && i.notes.trim() !== '')));
                    return filtered.length > 0 ? filtered : this.items;
                },

                // عدد الحركات لكل عملة
                countCurrency(currency) {
                    return this.items.filter(i => (i.currency || 'USD') === currency && (parseFloat(i.amount) > 0)).length;
                },

                // 1. مجموع الدولار ($)
                get totalUsd() {
                    return this.items
                        .filter(i => (i.currency || 'USD') === 'USD')
                        .reduce((sum, item) => sum + (parseFloat(item.amount) || 0), 0);
                },

                // 2. مجموع الدرهم الإماراتي (AED)
                get totalAed() {
                    return this.items
                        .filter(i => i.currency === 'AED')
                        .reduce((sum, item) => sum + (parseFloat(item.amount) || 0), 0);
                },

                // 3. مجموع الريمبي الصيني (¥ RMB)
                get totalRmb() {
                    return this.items
                        .filter(i => i.currency === 'RMB')
                        .reduce((sum, item) => sum + (parseFloat(item.amount) || 0), 0);
                },

                // 4. مجموع مبالغ الصرف بالعراقي (د.ع) قبل العمولات
                get totalIqdAmount() {
                    return this.items.reduce((sum, item) => sum + (parseFloat(item.iqd_amount) || 0), 0);
                },

                // 5. مجموع العمولات بالعراقي (د.ع)
                get totalCommissionIqd() {
                    return this.items.reduce((sum, item) => sum + (parseFloat(item.commission_iqd) || 0), 0);
                },

                // 6. المجموع الكلي النهائي بالعراقي (د.ع) = المبالغ + العمولات
                get grandTotalIqd() {
                    return this.items.reduce((sum, item) => sum + (parseFloat(item.total_iqd) || 0), 0);
                },

                // عند تعديل أي قيمة حسابية في الصف
                onRowValuesChanged(item) {
                    this.recomputeRow(item);
                    this.triggerAutoSave();
                },

                // عند تغيير العملة
                onCurrencySelectChange(item) {
                    this.recomputeRow(item);
                    this.triggerAutoSave();
                },

                onCellInput(item) {
                    this.triggerAutoSave();
                },

                onSheetChanged() {
                    this.triggerAutoSave();
                },

                // أمر الطباعة النظيفة
                triggerPrint() {
                    window.print();
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
                            items: this.items
                        };

                        // 1. Save to local storage
                        localStorage.setItem('independent_excel_sheet_v2', JSON.stringify(payload));

                        // 2. Save directly to server
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
                        this.saveStatus = 'saved';
                    }
                },

                // إضافة سطر جديد
                appendNewRow() {
                    this.items.push({
                        id: Date.now(),
                        transaction_no: '',
                        currency: 'USD',
                        amount: '',
                        exchange_rate: '',
                        iqd_amount: 0,
                        commission_rate: 1,
                        commission_type: 'per_thousand',
                        commission_iqd: 0,
                        total_iqd: 0,
                        notes: ''
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
                                transaction_no: i + 1,
                                currency: 'USD',
                                amount: '',
                                exchange_rate: '',
                                iqd_amount: 0,
                                commission_rate: 1,
                                commission_type: 'per_thousand',
                                commission_iqd: 0,
                                total_iqd: 0,
                                notes: ''
                            });
                        }
                        this.triggerAutoSave();
                    }
                },

                // ========================================================
                // 📊 تصدير Excel حقيقي منسق ومنظم بالكامل (.xlsx أو .xls)
                // ========================================================
                exportToExcel() {
                    const rowsToExport = this.printableItems;
                    if (rowsToExport.length === 0) {
                        alert('لا توجد بيانات مدخلة لتصديرها.');
                        return;
                    }

                    const title = this.sheetTitle || 'جدول الحسابات وسعر الصرف والعمولات';
                    const dateStr = new Date().toLocaleDateString('en-CA');

                    // إذا كانت مكتبة SheetJS متوفرة: نقوم بإنشاء ملف .xlsx أصلي حقيقي
                    if (typeof XLSX !== 'undefined') {
                        const aoa = [];

                        // 1. ترويسة العنوان
                        aoa.push(['مجموعة الزبيدي للتجارة والشحن الدولي']);
                        aoa.push([title]);
                        aoa.push(['تاريخ التصدير:', dateStr]);
                        aoa.push([]);

                        // 2. كاردات ملخص المجاميع بالأعلى
                        aoa.push(['ملخص المجاميع العامة:']);
                        aoa.push([
                            'إجمالي الدولار ($)',
                            'إجمالي الإماراتي (AED)',
                            'إجمالي الريمبي (¥)',
                            'إجمالي العراقي (د.ع)',
                            'إجمالي العمولات (د.ع)',
                            'الصافي الكلي النهائي (د.ع)'
                        ]);
                        aoa.push([
                            this.totalUsd,
                            this.totalAed,
                            this.totalRmb,
                            this.totalIqdAmount,
                            this.totalCommissionIqd,
                            this.grandTotalIqd
                        ]);
                        aoa.push([]);

                        // 3. عناوين أعمدة الجدول
                        aoa.push([
                            '#',
                            'رقم الفاتورة',
                            'العملة',
                            'مبلغ الحركة',
                            'سعر الصرف (د.ع)',
                            'مبلغ عراقي (د.ع)',
                            'نسبة العمولة',
                            'مبلغ العمولة عراقي (د.ع)',
                            'المجموع عراقي (د.ع)',
                            'البيان / ملاحظات'
                        ]);

                        // 4. أسطر البيانات
                        rowsToExport.forEach((item, idx) => {
                            let currName = 'دولار أمريكي ($)';
                            if (item.currency === 'AED') currName = 'درهم إماراتي (AED)';
                            if (item.currency === 'RMB') currName = 'ريمبي صيني (¥)';

                            const commText = item.commission_type === 'per_thousand'
                                ? (item.commission_rate || 0) + ' بالألف ‰'
                                : (item.commission_rate || 0) + ' %';

                            aoa.push([
                                idx + 1,
                                item.transaction_no || '',
                                currName,
                                parseFloat(item.amount) || 0,
                                parseFloat(item.exchange_rate) || 0,
                                parseFloat(item.iqd_amount) || 0,
                                commText,
                                parseFloat(item.commission_iqd) || 0,
                                parseFloat(item.total_iqd) || 0,
                                item.notes || ''
                            ]);
                        });

                        // 5. سطر المجاميع الإجمالية
                        aoa.push([
                            'المجاميع الإجمالية',
                            '',
                            '',
                            '',
                            '',
                            this.totalIqdAmount,
                            '',
                            this.totalCommissionIqd,
                            this.grandTotalIqd,
                            'الصافي الكلي النهائي'
                        ]);

                        const ws = XLSX.utils.aoa_to_sheet(aoa);

                        // تحديد عرض الأعمدة (Column Widths) لضمان عدم اقتصاص النصوص
                        ws['!cols'] = [
                            { wch: 6 },  // #
                            { wch: 14 }, // رقم الحركة
                            { wch: 22 }, // العملة
                            { wch: 16 }, // مبلغ الحركة
                            { wch: 16 }, // سعر الصرف
                            { wch: 20 }, // مبلغ عراقي
                            { wch: 16 }, // نسبة العمولة
                            { wch: 22 }, // مبلغ العمولة عراقي
                            { wch: 22 }, // المجموع عراقي
                            { wch: 35 }  // البيان
                        ];

                        // تفعيل الاتجاه من اليمين إلى اليسار (RTL) في إكسل
                        ws['!views'] = [{ RTL: true }];

                        const wb = XLSX.utils.book_new();
                        XLSX.utils.book_append_sheet(wb, ws, 'كشف الحسابات');
                        const filename = (title).replace(/[\/\\?%*:|"<>]/g, '_') + '_' + dateStr + '.xlsx';
                        XLSX.writeFile(wb, filename);
                    } else {
                        // Fallback مباشر بصيغة Excel XML / HTML منسقة بالكامل
                        this.exportToExcelFallback(rowsToExport, title, dateStr);
                    }
                },

                // Fallback لتصدير Excel منسق بـ HTML/XML جاهز لإكسل مباشرة
                exportToExcelFallback(rowsToExport, title, dateStr) {
                    let html = `
                    <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
                    <head>
                        <meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8">
                        <style>
                            table { direction: rtl; border-collapse: collapse; font-family: Tahoma, Arial, sans-serif; font-size: 11pt; }
                            th { background-color: #2563eb; color: #ffffff; border: 0.5pt solid #000; font-weight: bold; padding: 6px; }
                            td { border: 0.5pt solid #cccccc; padding: 5px; text-align: right; }
                            .kpi-th { background-color: #1e293b; color: #ffffff; }
                            .kpi-td { background-color: #f8fafc; font-weight: bold; text-align: center; }
                            .num { mso-number-format:"\\#\\,\\#\\#0\\.00"; text-align: left; }
                            .total-row { background-color: #e2e8f0; font-weight: bold; border-top: 1.5pt solid #000; }
                        </style>
                    </head>
                    <body>
                        <table>
                            <tr><th colspan="10" style="font-size: 14pt; background-color: #0f172a;">مجموعة الزبيدي للتجارة والشحن الدولي</th></tr>
                            <tr><th colspan="10" style="font-size: 12pt; background-color: #1e293b;">${title} - تاريخ: ${dateStr}</th></tr>
                            <tr><td colspan="10"></td></tr>
                            <tr>
                                <th colspan="2" class="kpi-th">إجمالي الدولار</th>
                                <th colspan="2" class="kpi-th">إجمالي الإماراتي</th>
                                <th class="kpi-th">إجمالي الريمبي</th>
                                <th colspan="2" class="kpi-th">إجمالي العراقي</th>
                                <th class="kpi-th">إجمالي العمولات</th>
                                <th colspan="2" class="kpi-th" style="background-color: #047857;">المجموع الكلي النهائي</th>
                            </tr>
                            <tr>
                                <td colspan="2" class="kpi-td">$ ${this.formatNumber(this.totalUsd, 2)}</td>
                                <td colspan="2" class="kpi-td">AED ${this.formatNumber(this.totalAed, 2)}</td>
                                <td class="kpi-td">¥ ${this.formatNumber(this.totalRmb, 2)}</td>
                                <td colspan="2" class="kpi-td">${this.formatNumber(this.totalIqdAmount, 0)} د.ع</td>
                                <td class="kpi-td">${this.formatNumber(this.totalCommissionIqd, 0)} د.ع</td>
                                <td colspan="2" class="kpi-td" style="background-color: #d1fae5; color: #065f46; font-size: 12pt;">${this.formatNumber(this.grandTotalIqd, 0)} د.ع</td>
                            </tr>
                            <tr><td colspan="10"></td></tr>
                            <tr>
                                <th>#</th>
                                <th>رقم الحركة</th>
                                <th>العملة</th>
                                <th>مبلغ الحركة</th>
                                <th>سعر الصرف</th>
                                <th>مبلغ عراقي</th>
                                <th>نسبة العمولة</th>
                                <th>مبلغ العمولة عراقي</th>
                                <th>المجموع عراقي</th>
                                <th>البيان / ملاحظات</th>
                            </tr>`;

                    rowsToExport.forEach((item, idx) => {
                        let currName = 'دولار أمريكي ($)';
                        if (item.currency === 'AED') currName = 'درهم إماراتي (AED)';
                        if (item.currency === 'RMB') currName = 'ريمبي صيني (¥)';

                        const commText = item.commission_type === 'per_thousand'
                            ? (item.commission_rate || 0) + ' بالألف ‰'
                            : (item.commission_rate || 0) + ' %';

                        html += `
                        <tr>
                            <td style="text-align: center;">${idx + 1}</td>
                            <td style="text-align: center;">${item.transaction_no || (idx + 1)}</td>
                            <td>${currName}</td>
                            <td class="num">${parseFloat(item.amount) || 0}</td>
                            <td class="num">${parseFloat(item.exchange_rate) || 0}</td>
                            <td class="num">${parseFloat(item.iqd_amount) || 0}</td>
                            <td style="text-align: center;">${commText}</td>
                            <td class="num">${parseFloat(item.commission_iqd) || 0}</td>
                            <td class="num" style="font-weight: bold;">${parseFloat(item.total_iqd) || 0}</td>
                            <td>${item.notes || ''}</td>
                        </tr>`;
                    });

                    html += `
                            <tr class="total-row">
                                <td colspan="3" style="text-align: center;">المجاميع الإجمالية</td>
                                <td colspan="2" style="text-align: center; color: #555;">حسب الملخص أعلاه</td>
                                <td class="num">${this.totalIqdAmount.toFixed(2)}</td>
                                <td></td>
                                <td class="num">${this.totalCommissionIqd.toFixed(2)}</td>
                                <td class="num" style="font-size: 12pt; color: #047857;">${this.grandTotalIqd.toFixed(2)}</td>
                                <td>الصافي الكلي</td>
                            </tr>
                        </table>
                    </body>
                    </html>`;

                    const blob = new Blob([html], { type: 'application/vnd.ms-excel;charset=utf-8;' });
                    const url = URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    link.setAttribute('href', url);
                    link.setAttribute('download', title.replace(/[\/\\?%*:|"<>]/g, '_') + '_' + dateStr + '.xls');
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
