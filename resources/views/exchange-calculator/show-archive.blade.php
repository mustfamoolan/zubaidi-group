<x-layout.custom-default>
    <!-- SheetJS (XLSX) Library for Genuine Excel Export -->
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

    <!-- CSS خاص بالطباعة النظيفة A4 Landscape -->
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #realPrintSection, #realPrintSection * {
                visibility: visible;
            }
            #realPrintSection {
                position: absolute;
                left: 0;
                top: 0;
                width: 100% !important;
                margin: 0 !important;
                padding: 10px 15px !important;
                background: #ffffff !important;
                color: #000000 !important;
                display: block !important;
            }
            @page {
                size: A4 landscape;
                margin: 6mm 8mm;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>

    <div x-data="archivedExchangeSheet(@js([
        'title' => $archive->title,
        'date' => $archive->archive_date->format('Y-m-d'),
        'items' => $archive->items ?? [],
        'totals' => $archive->totals ?? [],
        'notes' => $archive->notes ?? ''
    ]))">

        <!-- Breadcrumbs & Notice -->
        <div class="no-print">
            <ul class="flex space-x-2 rtl:space-x-reverse text-sm mb-3">
                <li><a href="{{ url('/') }}" class="text-primary hover:underline">لوحة التحكم</a></li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                    <a href="{{ route('calculator-archives.index', ['type' => 'exchange']) }}" class="text-primary hover:underline">أرشيف الكشوفات</a>
                </li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                    <span class="text-black dark:text-white-light font-medium">{{ $archive->title }}</span>
                </li>
            </ul>

            <!-- Archive Banner Header -->
            <div class="panel mb-4 p-4 bg-gradient-to-r from-amber-500/10 via-primary/5 to-transparent border border-amber-500/30 dark:border-amber-500/20 rounded-xl">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-amber-500/20 text-amber-700 dark:text-amber-400 flex items-center justify-center shrink-0">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 7C4 5.89543 4.89543 5 6 5H18C19.1046 5 20 5.89543 20 7V9C20 9.55228 19.5523 10 19 10H5C4.44772 10 4 9.55228 4 9V7Z" stroke="currentColor" stroke-width="1.8"/>
                                <path opacity="0.5" d="M6 10V18C6 19.1046 6.89543 20 8 20H16C17.1046 20 18 19.1046 18 18V10" stroke="currentColor" stroke-width="1.8"/>
                                <path d="M10 14H14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h1 class="text-xl font-black text-gray-900 dark:text-white">
                                    {{ $archive->title }}
                                </h1>
                                <span class="badge bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-200 text-xs font-bold py-0.5 px-2.5 rounded-full">
                                    كشف مؤرشف للقراءة فقط
                                </span>
                            </div>
                            <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400 mt-1">
                                <span>تاريخ الأرشفة: <strong class="text-gray-700 dark:text-gray-200">{{ $archive->archive_date->format('Y-m-d') }}</strong></span>
                                <span>•</span>
                                <span>عدد الحركات: <strong class="text-gray-700 dark:text-gray-200">{{ $archive->rows_count }} حركة</strong></span>
                                <span>•</span>
                                <span>المستخدم: <strong class="text-gray-700 dark:text-gray-200">{{ $archive->user?->name ?? 'المسؤول' }}</strong></span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center flex-wrap gap-2">
                        <a href="{{ route('calculator-archives.index', ['type' => 'exchange']) }}" class="btn btn-outline-dark btn-sm flex items-center gap-1.5 shadow-sm">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15 19L8 12L15 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span>رجوع للأرشيف</span>
                        </a>

                        <a href="{{ url('/exchange-calculator') }}" class="btn btn-outline-primary btn-sm flex items-center gap-1.5 shadow-sm">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 4V20M20 12H4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            <span>الحاسبة النشطة</span>
                        </a>

                        <button type="button" @click="exportToExcel()" class="btn btn-outline-success btn-sm flex items-center gap-1.5 shadow-sm">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 16.004V17C4 18.8856 4 19.8284 4.58579 20.4142C5.17157 21 6.11438 21 8 21H16C17.8856 21 18.8284 21 19.4142 20.4142C20 19.8284 20 18.8856 20 17V16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                <path opacity="0.5" d="M12 3V15M12 15L8.5 11.5M12 15L15.5 11.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span>تصدير Excel (.xlsx)</span>
                        </button>

                        <button type="button" @click="triggerPrint()" class="btn btn-outline-secondary btn-sm flex items-center gap-1.5 shadow-sm">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7 8V3H17V8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                <path opacity="0.5" d="M7 16H17V21H7V16Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                <path d="M3 11C3 9.34315 4.34315 8 6 8H18C19.6569 8 21 9.34315 21 11V16C21 17.6569 19.6569 19 18 19H17V16H7V19H6C4.34315 19 3 17.6569 3 16V11Z" stroke="currentColor" stroke-width="1.5"/>
                            </svg>
                            <span>طباعة</span>
                        </button>

                        <form action="{{ route('calculator-archives.destroy', $archive->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من رغبتك بحذف هذا الكشف نهائياً من الأرشيف؟');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm flex items-center gap-1.5 shadow-sm">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M19 7L18.1327 19.1425C18.0579 20.1891 17.187 21 16.1378 21H7.86224C6.81296 21 5.94208 20.1891 5.86732 19.1425L5 7M10 11V17M14 11V17M15 7V4C15 3.44772 14.5523 3 14 3H10C9.44772 3 9 3.44772 9 4V7M4 7H20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span>حذف</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- KPI Summary Cards (نفس التصميم والألوان الأصلية بالضبط) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-3.5 mb-5">
                <!-- 1. دولار -->
                <div class="panel p-3.5 bg-gradient-to-br from-blue-50 to-indigo-50/50 dark:from-blue-950/20 dark:to-indigo-950/20 border border-blue-200/70 dark:border-blue-900/40 rounded-xl relative overflow-hidden shadow-sm">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-bold text-blue-700 dark:text-blue-300">مجموع الدولار</span>
                        <span class="w-7 h-7 rounded-lg bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-xs">$</span>
                    </div>
                    <div class="text-xl font-extrabold text-blue-800 dark:text-blue-200 tracking-tight" dir="ltr">
                        $ <span x-text="formatNumber(totalUsd, 2)">0.00</span>
                    </div>
                    <div class="mt-1.5 text-[10px] text-blue-600/80 dark:text-blue-300/70 flex items-center justify-between">
                        <span>دولار أمريكي (USD)</span>
                    </div>
                </div>

                <!-- 2. درهم إماراتي -->
                <div class="panel p-3.5 bg-gradient-to-br from-emerald-50 to-teal-50/50 dark:from-emerald-950/20 dark:to-teal-950/20 border border-emerald-200/70 dark:border-emerald-900/40 rounded-xl relative overflow-hidden shadow-sm">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-bold text-emerald-700 dark:text-emerald-300">مجموع الإماراتي</span>
                        <span class="w-7 h-7 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold text-[11px]">د.إ</span>
                    </div>
                    <div class="text-xl font-extrabold text-emerald-800 dark:text-emerald-200 tracking-tight" dir="ltr">
                        د.إ <span x-text="formatNumber(totalAed, 2)">0.00</span>
                    </div>
                    <div class="mt-1.5 text-[10px] text-emerald-600/80 dark:text-emerald-300/70 flex items-center justify-between">
                        <span>درهم إماراتي (AED)</span>
                    </div>
                </div>

                <!-- 3. ريمبي -->
                <div class="panel p-3.5 bg-gradient-to-br from-amber-50 to-orange-50/50 dark:from-amber-950/20 dark:to-orange-950/20 border border-amber-200/70 dark:border-amber-900/40 rounded-xl relative overflow-hidden shadow-sm">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-bold text-amber-700 dark:text-amber-300">مجموع الريمبي</span>
                        <span class="w-7 h-7 rounded-lg bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold text-xs">¥</span>
                    </div>
                    <div class="text-xl font-extrabold text-amber-800 dark:text-amber-200 tracking-tight" dir="ltr">
                        ¥ <span x-text="formatNumber(totalRmb, 2)">0.00</span>
                    </div>
                    <div class="mt-1.5 text-[10px] text-amber-600/80 dark:text-amber-300/70 flex items-center justify-between">
                        <span>يوان صيني (RMB)</span>
                    </div>
                </div>

                <!-- 4. عراقي (مبالغ الصرف) -->
                <div class="panel p-3.5 bg-gradient-to-br from-cyan-50 to-sky-50/50 dark:from-cyan-950/20 dark:to-sky-950/20 border border-cyan-200/70 dark:border-cyan-900/40 rounded-xl relative overflow-hidden shadow-sm">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-bold text-cyan-700 dark:text-cyan-300">مجموع العراقي</span>
                        <span class="w-7 h-7 rounded-lg bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 flex items-center justify-center font-bold text-[10px]">د.ع</span>
                    </div>
                    <div class="text-xl font-extrabold text-cyan-800 dark:text-cyan-200 tracking-tight" dir="ltr">
                        <span x-text="formatNumber(totalIqdAmount, 0)">0</span>
                    </div>
                    <div class="mt-1.5 text-[10px] text-cyan-600/80 dark:text-cyan-300/70 flex items-center justify-between">
                        <span>مبالغ الصرف (د.ع)</span>
                    </div>
                </div>

                <!-- 5. عمولات -->
                <div class="panel p-3.5 bg-gradient-to-br from-purple-50 to-pink-50/50 dark:from-purple-950/20 dark:to-pink-950/20 border border-purple-200/70 dark:border-purple-900/40 rounded-xl relative overflow-hidden shadow-sm">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-bold text-purple-700 dark:text-purple-300">مجموع العمولات</span>
                        <span class="w-7 h-7 rounded-lg bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center font-bold text-[11px]">%</span>
                    </div>
                    <div class="text-xl font-extrabold text-purple-800 dark:text-purple-200 tracking-tight" dir="ltr">
                        <span x-text="formatNumber(totalCommissionIqd, 0)">0</span> <span class="text-xs font-medium">د.ع</span>
                    </div>
                    <div class="mt-1.5 text-[10px] text-purple-600/80 dark:text-purple-300/70 flex items-center justify-between">
                        <span>إجمالي العمولات المحسوبة</span>
                    </div>
                </div>

                <!-- 6. المجموع الكلي النهائي بالعراقي -->
                <div class="panel p-3.5 bg-gradient-to-br from-emerald-100/80 to-teal-100/60 dark:from-emerald-950/40 dark:to-teal-950/30 border-2 border-emerald-500/40 dark:border-emerald-600/50 rounded-xl relative overflow-hidden shadow-sm">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-extrabold text-emerald-900 dark:text-emerald-300">المجموع الكلي عراقي</span>
                        <span class="w-7 h-7 rounded-lg bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 flex items-center justify-center font-black text-xs">∑</span>
                    </div>
                    <div class="text-xl font-black text-emerald-950 dark:text-white tracking-tight" dir="ltr">
                        <span x-text="formatNumber(grandTotalIqd, 0)">0</span> <span class="text-xs text-emerald-700 dark:text-emerald-300 font-bold">د.ع</span>
                    </div>
                    <div class="mt-1.5 text-[10px] text-emerald-900/80 dark:text-emerald-300/80 flex items-center justify-between">
                        <span>(المبالغ + العمولات + سويفت)</span>
                        <span class="badge bg-emerald-600 text-white dark:bg-emerald-500 dark:text-gray-900 font-bold text-[10px] py-0 px-1.5">الصافي الكلي</span>
                    </div>
                </div>
            </div>

            <!-- Table Panel (عرض صافي بدون حقول إدخال) -->
            <div class="panel p-0 border border-gray-300 dark:border-white-dark/20 rounded-xl overflow-hidden shadow-sm">
                <div class="table-responsive max-h-[700px] overflow-auto">
                    <table class="table-striped table-hover w-full text-xs text-right border-collapse select-none">
                        <thead class="sticky top-0 bg-[#eaedf1] dark:bg-[#0e1726] z-10 border-b border-gray-300 dark:border-white-dark/20 text-gray-800 dark:text-gray-200 font-bold text-[11px] shadow-sm">
                            <tr>
                                <th class="w-8 text-center py-2 px-1 border-l border-gray-300 dark:border-white-dark/10">#</th>
                                <th class="w-28 text-center py-2 px-1 border-l border-gray-300 dark:border-white-dark/10">رقم الفاتورة</th>
                                <th class="w-28 py-2 px-1 border-l border-gray-300 dark:border-white-dark/10 text-center">العملة</th>
                                <th class="min-w-[110px] py-2 px-2 text-left border-l border-gray-300 dark:border-white-dark/10" dir="ltr">مبلغ الحركة</th>
                                <th class="min-w-[105px] py-2 px-2 text-left border-l border-gray-300 dark:border-white-dark/10" dir="ltr">سعر الصرف (د.ع)</th>
                                <th class="min-w-[125px] py-2 px-2 text-left text-cyan-800 dark:text-cyan-300 border-l border-gray-300 dark:border-white-dark/10 bg-cyan-50/40 dark:bg-cyan-950/20 font-black" dir="ltr">
                                    مبلغ عراقي (د.ع)
                                </th>
                                <th class="min-w-[120px] py-2 px-1.5 text-center border-l border-gray-300 dark:border-white-dark/10">العمولة (النسبة)</th>
                                <th class="min-w-[115px] py-2 px-2 text-left text-purple-800 dark:text-purple-300 border-l border-gray-300 dark:border-white-dark/10 bg-purple-50/40 dark:bg-purple-950/20 font-black" dir="ltr">مبلغ العمولة عراقي</th>
                                <th class="min-w-[110px] py-2 px-2 text-left text-indigo-800 dark:text-indigo-300 border-l border-gray-300 dark:border-white-dark/10 bg-indigo-50/50 dark:bg-indigo-950/20 font-black" dir="ltr">عمولة سويفت</th>
                                <th class="min-w-[130px] py-2 px-2 text-left text-emerald-800 dark:text-emerald-300 border-l border-gray-300 dark:border-white-dark/10 bg-emerald-50/50 dark:bg-emerald-950/30 font-black" dir="ltr">المجموع عراقي (د.ع)</th>
                                <th class="min-w-[120px] py-2 px-2">البيان / ملاحظات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200/80 dark:divide-white-dark/10 font-medium">
                            <template x-for="(item, index) in items" :key="index">
                                <tr class="group hover:bg-primary/5 transition-colors">
                                    <!-- 1. التسلسل -->
                                    <td class="text-center font-bold text-gray-500 py-2 px-1 border-l border-gray-200 dark:border-white-dark/10 bg-gray-50/40 dark:bg-[#0e1726]/40" x-text="index + 1"></td>

                                    <!-- 2. رقم الفاتورة -->
                                    <td class="py-2 px-2 border-l border-gray-200 dark:border-white-dark/10 text-center font-bold text-gray-800 dark:text-gray-200" x-text="item.transaction_no || '-'"></td>

                                    <!-- 3. العملة -->
                                    <td class="py-2 px-2 border-l border-gray-200 dark:border-white-dark/10 text-center font-bold"
                                        :class="{
                                            'text-blue-600 dark:text-blue-400': item.currency === 'USD',
                                            'text-emerald-600 dark:text-emerald-400': item.currency === 'AED',
                                            'text-amber-600 dark:text-amber-400': item.currency === 'RMB'
                                        }"
                                        x-text="getPrintCurrencyLabel(item.currency)">
                                    </td>

                                    <!-- 4. مبلغ الحركة -->
                                    <td class="py-2 px-2 border-l border-gray-200 dark:border-white-dark/10 text-left font-bold" dir="ltr">
                                        <span class="text-gray-400 text-xs mr-1" x-text="getCurrencySymbol(item.currency)"></span>
                                        <span x-text="formatNumber(item.amount, 2)"></span>
                                    </td>

                                    <!-- 5. سعر الصرف -->
                                    <td class="py-2 px-2 border-l border-gray-200 dark:border-white-dark/10 text-left font-semibold text-gray-700 dark:text-gray-300" dir="ltr" x-text="formatNumber(item.exchange_rate, 2)"></td>

                                    <!-- 6. مبلغ عراقي -->
                                    <td class="py-2 px-2 border-l border-gray-200 dark:border-white-dark/10 bg-cyan-50/20 dark:bg-cyan-950/10 text-left font-bold text-cyan-800 dark:text-cyan-300" dir="ltr">
                                        <div class="flex items-center justify-between">
                                            <span x-text="formatNumber(item.iqd_amount, 2)"></span>
                                            <span class="text-[10px] text-gray-500 font-normal">د.ع</span>
                                        </div>
                                    </td>

                                    <!-- 7. العمولة -->
                                    <td class="py-2 px-2 border-l border-gray-200 dark:border-white-dark/10 text-center font-bold text-purple-700 dark:text-purple-300" x-text="item.commission_type === 'per_thousand' ? (item.commission_rate || 0) + ' ‰ بالألف' : (item.commission_rate || 0) + ' % بالمئة'"></td>

                                    <!-- 8. مبلغ العمولة عراقي -->
                                    <td class="py-2 px-2 border-l border-gray-200 dark:border-white-dark/10 bg-purple-50/20 dark:bg-purple-950/10 text-left font-extrabold text-purple-700 dark:text-purple-300" dir="ltr">
                                        <div class="flex items-center justify-between">
                                            <span x-text="formatNumber(item.commission_iqd, 2)"></span>
                                            <span class="text-[10px] text-gray-500 font-normal">د.ع</span>
                                        </div>
                                    </td>

                                    <!-- 9. عمولة سويفت -->
                                    <td class="py-2 px-2 border-l border-gray-200 dark:border-white-dark/10 bg-indigo-50/20 dark:bg-indigo-950/10 text-left font-bold text-indigo-700 dark:text-indigo-300" dir="ltr">
                                        <div class="flex items-center justify-between">
                                            <span x-text="formatNumber(item.swift_commission, 2)"></span>
                                            <span class="text-[10px] text-gray-500 font-normal">د.ع</span>
                                        </div>
                                    </td>

                                    <!-- 10. المجموع عراقي -->
                                    <td class="py-2 px-2 border-l border-gray-200 dark:border-white-dark/10 bg-emerald-50/25 dark:bg-emerald-950/20 text-left font-black text-emerald-700 dark:text-emerald-300" dir="ltr">
                                        <div class="flex items-center justify-between">
                                            <span x-text="formatNumber(item.total_iqd, 2)"></span>
                                            <span class="text-[10px] text-emerald-600 font-semibold">د.ع</span>
                                        </div>
                                    </td>

                                    <!-- 11. البيان / ملاحظات -->
                                    <td class="py-2 px-2 text-gray-700 dark:text-gray-300" x-text="item.notes || '-'"></td>
                                </tr>
                            </template>
                        </tbody>

                        <!-- Footer: المجاميع الإجمالية -->
                        <tfoot class="bg-[#eaedf0] dark:bg-[#14233c] font-bold text-xs text-gray-800 dark:text-white-light border-t-2 border-gray-300 dark:border-white-dark/20 sticky bottom-0 z-10 shadow-md">
                            <tr>
                                <td class="text-center py-2.5 px-1 border-l border-gray-300 dark:border-white-dark/20">∑</td>
                                <td class="text-center py-2.5 px-1 border-l border-gray-300 dark:border-white-dark/20 text-gray-500" x-text="items.length + ' حركات'"></td>
                                <td class="py-2.5 px-1 border-l border-gray-300 dark:border-white-dark/20 font-black text-center">المجاميع</td>

                                <td class="py-2.5 px-2 text-left border-l border-gray-300 dark:border-white-dark/20 text-gray-600 dark:text-gray-300 text-[10px]" dir="ltr">
                                    <span>حسب العملة بالأعلى</span>
                                </td>

                                <td class="py-2.5 px-1 border-l border-gray-300 dark:border-white-dark/20 text-center text-gray-400">-</td>

                                <!-- Total IQD Amount -->
                                <td class="py-2.5 px-2 text-left border-l border-gray-300 dark:border-white-dark/20 font-black text-cyan-800 dark:text-cyan-300 text-xs" dir="ltr">
                                    <span x-text="formatNumber(totalIqdAmount, 2)">0.00</span> <span class="text-[9px] font-normal">د.ع</span>
                                </td>

                                <td class="py-2.5 px-1 border-l border-gray-300 dark:border-white-dark/20 text-center text-gray-500 font-normal text-[10px]">إجمالي العمولات:</td>

                                <!-- Total Commission IQD -->
                                <td class="py-2.5 px-2 text-left border-l border-gray-300 dark:border-white-dark/20 font-black text-purple-700 dark:text-purple-300 text-xs" dir="ltr">
                                    <span x-text="formatNumber(totalCommissionIqd, 2)">0.00</span> <span class="text-[9px] font-normal">د.ع</span>
                                </td>

                                <!-- Total Swift Commission -->
                                <td class="py-2.5 px-2 text-left border-l border-gray-300 dark:border-white-dark/20 font-black text-indigo-700 dark:text-indigo-300 text-xs" dir="ltr">
                                    <span x-text="formatNumber(totalSwiftCommission, 2)">0.00</span> <span class="text-[9px] font-normal">د.ع</span>
                                </td>

                                <!-- Grand Total IQD -->
                                <td class="py-2.5 px-2 text-left border-l border-gray-300 dark:border-white-dark/20 font-black text-emerald-700 dark:text-emerald-300 text-sm" dir="ltr">
                                    <span x-text="formatNumber(grandTotalIqd, 2)">0.00</span> <span class="text-[10px] font-bold">د.ع</span>
                                </td>

                                <td class="py-2.5 px-2 text-center text-gray-500 text-[10px]">الصافي النهائي</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- شريط سفلي -->
                <div class="p-3 bg-gray-50/70 dark:bg-[#121e32]/40 border-t border-gray-200/60 dark:border-white-dark/10 flex items-center justify-between">
                    <span class="text-xs text-gray-500 font-semibold">
                        كشف مؤرشف مسجل برقم #{{ $archive->id }} بتاريخ {{ $archive->archive_date->format('Y-m-d') }}
                    </span>

                    <div class="text-xs text-gray-500 flex items-center gap-4 font-semibold">
                        <span>مجموع العمولات: <strong class="text-purple-600" dir="ltr"><span x-text="formatNumber(totalCommissionIqd, 0)"></span> د.ع</strong></span>
                        <span>عمولة سويفت: <strong class="text-indigo-600" dir="ltr"><span x-text="formatNumber(totalSwiftCommission, 0)"></span> د.ع</strong></span>
                        <span>المجموع الكلي: <strong class="text-emerald-600" dir="ltr"><span x-text="formatNumber(grandTotalIqd, 0)"></span> د.ع</strong></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- 🖨️ قسم الطباعة الرسمي A4 Landscape للنسخة المؤرشفة -->
        <!-- ======================================================== -->
        <div id="realPrintSection" class="hidden">
            <!-- ترويسة الطباعة -->
            <div style="border-bottom: 2px solid #000; padding-bottom: 8px; margin-bottom: 8px; direction: rtl;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <h1 style="font-size: 18px; font-weight: 900; margin: 0; color: #000;">
                            مجموعة الزبيدي للتجارة والشحن الدولي
                        </h1>
                        <h2 style="font-size: 13px; font-weight: bold; margin: 3px 0 0 0; color: #333;" x-text="sheetTitle">
                        </h2>
                    </div>
                    <div style="text-align: left; font-size: 11px; font-weight: 600; line-height: 1.4;">
                        <div>تاريخ الكشف: <strong>{{ $archive->archive_date->format('Y-m-d') }}</strong></div>
                        <div>تاريخ الطباعة: <strong>{{ date('Y-m-d h:i A') }}</strong></div>
                        <div>عدد الحركات: <strong x-text="items.length"></strong></div>
                    </div>
                </div>
            </div>

            <!-- ملخص المجاميع للطباعة -->
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 6px; margin-bottom: 10px; direction: rtl;">
                <div style="border: 1px solid #333; padding: 4px 8px; background: #fafafa; border-radius: 4px; text-align: center;">
                    <div style="font-size: 10px; color: #555; margin-bottom: 2px;">مجموع الدولار (USD)</div>
                    <div style="font-size: 12px; font-weight: 900; color: #000;" dir="ltr">
                        $ <span x-text="formatNumber(totalUsd, 2)">0.00</span>
                    </div>
                </div>

                <div style="border: 1px solid #333; padding: 4px 8px; background: #fafafa; border-radius: 4px; text-align: center;">
                    <div style="font-size: 10px; color: #555; margin-bottom: 2px;">مجموع الإماراتي (AED)</div>
                    <div style="font-size: 12px; font-weight: 900; color: #000;" dir="ltr">
                        د.إ <span x-text="formatNumber(totalAed, 2)">0.00</span>
                    </div>
                </div>

                <div style="border: 1px solid #333; padding: 4px 8px; background: #fafafa; border-radius: 4px; text-align: center;">
                    <div style="font-size: 10px; color: #555; margin-bottom: 2px;">مجموع الريمبي (RMB)</div>
                    <div style="font-size: 12px; font-weight: 900; color: #000;" dir="ltr">
                        ¥ <span x-text="formatNumber(totalRmb, 2)">0.00</span>
                    </div>
                </div>

                <div style="border: 1px solid #333; padding: 4px 8px; background: #fafafa; border-radius: 4px; text-align: center;">
                    <div style="font-size: 10px; color: #555; margin-bottom: 2px;">مجموع مبالغ الصرف (عراقي)</div>
                    <div style="font-size: 12px; font-weight: 900; color: #000;" dir="ltr">
                        <span x-text="formatNumber(totalIqdAmount, 0)">0</span> <span style="font-size: 9px; font-weight: normal;">د.ع</span>
                    </div>
                </div>

                <div style="border: 1px solid #333; padding: 4px 8px; background: #fafafa; border-radius: 4px; text-align: center;">
                    <div style="font-size: 10px; color: #555; margin-bottom: 2px;">مجموع العمولات (عراقي)</div>
                    <div style="font-size: 12px; font-weight: 900; color: #000;" dir="ltr">
                        <span x-text="formatNumber(totalCommissionIqd, 0)">0</span> <span style="font-size: 9px; font-weight: normal;">د.ع</span>
                    </div>
                </div>

                <div style="border: 2px solid #000; padding: 4px 8px; background: #e8f5e9; border-radius: 4px; text-align: center;">
                    <div style="font-size: 10px; color: #111; font-weight: bold; margin-bottom: 2px;">المجموع النهائي (الصافي)</div>
                    <div style="font-size: 13px; font-weight: 900; color: #000;" dir="ltr">
                        <span x-text="formatNumber(grandTotalIqd, 0)">0</span> <span style="font-size: 10px; font-weight: bold;">د.ع</span>
                    </div>
                </div>
            </div>

            <!-- جدول الطباعة -->
            <table style="width: 100%; border-collapse: collapse; font-size: 10px; text-align: right; direction: rtl;">
                <thead>
                    <tr style="background-color: #f1f3f5; font-weight: bold;">
                        <th style="width: 3.5%; text-align: center; border: 1px solid #444; padding: 4px;">#</th>
                        <th style="width: 9%; text-align: center; border: 1px solid #444; padding: 4px;">رقم الفاتورة</th>
                        <th style="width: 10%; text-align: center; border: 1px solid #444; padding: 4px;">العملة</th>
                        <th style="width: 11%; text-align: left; border: 1px solid #444; padding: 4px;" dir="ltr">مبلغ الحركة</th>
                        <th style="width: 9%; text-align: left; border: 1px solid #444; padding: 4px;" dir="ltr">سعر الصرف</th>
                        <th style="width: 11%; text-align: left; border: 1px solid #444; padding: 4px;" dir="ltr">مبلغ عراقي (د.ع)</th>
                        <th style="width: 8%; text-align: center; border: 1px solid #444; padding: 4px;">العمولة</th>
                        <th style="width: 10%; text-align: left; border: 1px solid #444; padding: 4px;" dir="ltr">مبلغ العمولة عراقي</th>
                        <th style="width: 9.5%; text-align: left; border: 1px solid #444; padding: 4px;" dir="ltr">عمولة سويفت</th>
                        <th style="width: 12%; text-align: left; border: 1px solid #444; padding: 4px; font-weight: 900;" dir="ltr">المجموع عراقي (د.ع)</th>
                        <th style="width: 7%; text-align: right; border: 1px solid #444; padding: 4px;">البيان</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(item, pIndex) in items" :key="'p-' + pIndex">
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
                            <td style="text-align: left; font-weight: bold; border: 1px solid #555; padding: 4px;" dir="ltr" x-text="formatNumber(item.swift_commission, 2)"></td>
                            <td style="text-align: left; font-weight: 900; border: 1px solid #555; padding: 4px; background-color: #fafafa;" dir="ltr" x-text="formatNumber(item.total_iqd, 2)"></td>
                            <td style="text-align: right; border: 1px solid #555; padding: 4px;" x-text="item.notes || '-'"></td>
                        </tr>
                    </template>
                </tbody>
                <tfoot>
                    <tr style="background-color: #eaedf0; font-weight: 900; border-top: 2px solid #000; border-bottom: 2px solid #000;">
                        <td colspan="3" style="text-align: center; border: 1px solid #444; padding: 4px; font-weight: bold;">
                            المجاميع الإجمالية (<span x-text="items.length"></span> حركة)
                        </td>
                        <td colspan="2" style="text-align: center; border: 1px solid #444; padding: 4px; font-size: 9px; color: #555;">
                            حسب ملخص العملات أعلاه
                        </td>
                        <td style="text-align: left; border: 1px solid #444; padding: 4px;" dir="ltr">
                            <span x-text="formatNumber(totalIqdAmount, 2)"></span> د.ع
                        </td>
                        <td style="text-align: center; border: 1px solid #444; padding: 4px; font-size: 9px;">إجمالي العمولات:</td>
                        <td style="text-align: left; border: 1px solid #444; padding: 4px;" dir="ltr">
                            <span x-text="formatNumber(totalCommissionIqd, 2)"></span> د.ع
                        </td>
                        <td style="text-align: left; border: 1px solid #444; padding: 4px;" dir="ltr">
                            <span x-text="formatNumber(totalSwiftCommission, 2)"></span> د.ع
                        </td>
                        <td style="text-align: left; border: 1px solid #444; padding: 4px; font-size: 11px; font-weight: 900;" dir="ltr">
                            <span x-text="formatNumber(grandTotalIqd, 2)"></span> د.ع
                        </td>
                        <td style="text-align: center; border: 1px solid #444; padding: 4px; font-size: 9px;">الصافي النهائي</td>
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

    <script>
        function archivedExchangeSheet(config) {
            return {
                sheetTitle: config.title || 'كشف حسابات صرف العملات',
                archiveDate: config.date || '',
                items: config.items || [],

                triggerPrint() {
                    window.print();
                },

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
                        case 'USD': return 'دولار أمريكي ($ USD)';
                        case 'AED': return 'درهم إماراتي (AED)';
                        case 'RMB': return 'ريمبي صيني (¥ RMB)';
                        default: return currency || 'USD';
                    }
                },

                parseCleanNumber(val) {
                    if (val === null || val === undefined || val === '') return 0;
                    const clean = String(val).replace(/,/g, '').trim();
                    const num = parseFloat(clean);
                    return isNaN(num) ? 0 : num;
                },

                get totalUsd() {
                    return this.items.filter(i => (i.currency || 'USD') === 'USD')
                        .reduce((sum, item) => sum + this.parseCleanNumber(item.amount), 0);
                },

                get totalAed() {
                    return this.items.filter(i => i.currency === 'AED')
                        .reduce((sum, item) => sum + this.parseCleanNumber(item.amount), 0);
                },

                get totalRmb() {
                    return this.items.filter(i => i.currency === 'RMB')
                        .reduce((sum, item) => sum + this.parseCleanNumber(item.amount), 0);
                },

                get totalIqdAmount() {
                    return this.items.reduce((sum, item) => sum + this.parseCleanNumber(item.iqd_amount), 0);
                },

                get totalCommissionIqd() {
                    return this.items.reduce((sum, item) => sum + this.parseCleanNumber(item.commission_iqd), 0);
                },

                get totalSwiftCommission() {
                    return this.items.reduce((sum, item) => sum + this.parseCleanNumber(item.swift_commission), 0);
                },

                get grandTotalIqd() {
                    return this.items.reduce((sum, item) => sum + this.parseCleanNumber(item.total_iqd), 0);
                },

                formatNumber(val, decimals = null) {
                    if (val === null || val === undefined || val === '') return '0';
                    const clean = String(val).replace(/,/g, '').trim();
                    const num = parseFloat(clean);
                    if (isNaN(num)) return '0';

                    if (decimals !== null) {
                        return num.toLocaleString('en-US', {
                            minimumFractionDigits: decimals,
                            maximumFractionDigits: decimals
                        });
                    }

                    const parts = clean.split('.');
                    const dec = parts.length > 1 ? Math.min(parts[1].length, 4) : 0;
                    return num.toLocaleString('en-US', {
                        minimumFractionDigits: dec,
                        maximumFractionDigits: dec
                    });
                },

                exportToExcel() {
                    const rowsToExport = this.items;
                    if (rowsToExport.length === 0) {
                        alert('لا توجد بيانات مدخلة لتصديرها.');
                        return;
                    }

                    const title = this.sheetTitle;
                    const dateStr = this.archiveDate || new Date().toLocaleDateString('en-CA');

                    if (typeof XLSX !== 'undefined') {
                        const aoa = [];
                        aoa.push(['مجموعة الزبيدي للتجارة والشحن الدولي']);
                        aoa.push([title]);
                        aoa.push(['تاريخ الكشف المؤرشف:', dateStr]);
                        aoa.push([]);

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

                        aoa.push([
                            '#',
                            'رقم الفاتورة',
                            'العملة',
                            'مبلغ الحركة',
                            'سعر الصرف (د.ع)',
                            'مبلغ عراقي (د.ع)',
                            'نسبة العمولة',
                            'مبلغ العمولة عراقي (د.ع)',
                            'عمولة سويفت (د.ع)',
                            'المجموع عراقي (د.ع)',
                            'البيان / ملاحظات'
                        ]);

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
                                this.parseCleanNumber(item.amount),
                                this.parseCleanNumber(item.exchange_rate),
                                this.parseCleanNumber(item.iqd_amount),
                                commText,
                                this.parseCleanNumber(item.commission_iqd),
                                this.parseCleanNumber(item.swift_commission),
                                this.parseCleanNumber(item.total_iqd),
                                item.notes || ''
                            ]);
                        });

                        aoa.push([
                            'المجاميع الإجمالية',
                            '',
                            '',
                            '',
                            '',
                            this.totalIqdAmount,
                            '',
                            this.totalCommissionIqd,
                            this.totalSwiftCommission,
                            this.grandTotalIqd,
                            'الصافي الكلي النهائي'
                        ]);

                        const ws = XLSX.utils.aoa_to_sheet(aoa);
                        ws['!cols'] = [
                            { wch: 6 },
                            { wch: 14 },
                            { wch: 22 },
                            { wch: 16 },
                            { wch: 16 },
                            { wch: 20 },
                            { wch: 16 },
                            { wch: 20 },
                            { wch: 18 },
                            { wch: 22 },
                            { wch: 35 }
                        ];
                        ws['!views'] = [{ RTL: true }];

                        const wb = XLSX.utils.book_new();
                        XLSX.utils.book_append_sheet(wb, ws, 'كشف الحسابات المؤرشف');
                        const filename = (title).replace(/[\/\\?%*:|"<>]/g, '_') + '_' + dateStr + '.xlsx';
                        XLSX.writeFile(wb, filename);
                    }
                }
            };
        }
    </script>
</x-layout.custom-default>
