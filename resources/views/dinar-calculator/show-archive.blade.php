<x-layout.custom-default>
    <!-- SheetJS (XLSX) Library for Genuine Excel Export -->
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

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

    <div x-data="archivedDinarSheet(@js([
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
                    <a href="{{ route('calculator-archives.index', ['type' => 'dinar']) }}" class="text-primary hover:underline">أرشيف الكشوفات</a>
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
                                <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8"/>
                                <path d="M12 7V17M9 10H15M9 14H15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h1 class="text-xl font-black text-gray-900 dark:text-white">
                                    {{ $archive->title }}
                                </h1>
                                <span class="badge bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-200 text-xs font-bold py-0.5 px-2.5 rounded-full">
                                    كشف دينار مؤرشف للقراءة فقط
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
                        <a href="{{ route('calculator-archives.index', ['type' => 'dinar']) }}" class="btn btn-outline-dark btn-sm flex items-center gap-1.5 shadow-sm">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15 19L8 12L15 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span>رجوع للأرشيف</span>
                        </a>

                        <a href="{{ url('/dinar-calculator') }}" class="btn btn-outline-primary btn-sm flex items-center gap-1.5 shadow-sm">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 4V20M20 12H4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            <span>حاسبة الدينار النشطة</span>
                        </a>

                        <button type="button" @click="exportToExcel()" class="btn btn-outline-success btn-sm flex items-center gap-1.5 shadow-sm">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 16V17C4 18.8856 4 19.8284 4.58579 20.4142C5.17157 21 6.11438 21 8 21H16C17.8856 21 18.8284 21 19.4142 20.4142C20 19.8284 20 18.8856 20 17V16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                <path opacity="0.5" d="M12 3V15M12 15L8.5 11.5M12 15L15.5 11.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span>Excel (.xlsx)</span>
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

            <!-- Table (عرض صافي بدون حقول إدخال) -->
            <div class="panel p-0 border border-gray-300 dark:border-white-dark/20 rounded-xl overflow-hidden shadow-sm mb-5">
                <div class="table-responsive max-h-[700px] overflow-auto">
                    <table class="w-full text-xs text-right border-collapse select-none">
                        <thead class="sticky top-0 bg-[#eaedf1] dark:bg-[#0e1726] z-10 border-b border-gray-300 dark:border-white-dark/20 text-gray-800 dark:text-gray-200 font-bold text-[11px] shadow-sm">
                            <tr>
                                <th class="w-12 text-center py-2.5 px-2 border-l border-gray-300 dark:border-white-dark/10">#</th>
                                <th class="w-36 text-center py-2.5 px-3 border-l border-gray-300 dark:border-white-dark/10">العملة</th>
                                <th class="min-w-[130px] py-2.5 px-3 text-left border-l border-gray-300 dark:border-white-dark/10" dir="ltr">سعر الصرف</th>
                                <th class="min-w-[140px] py-2.5 px-3 text-left border-l border-gray-300 dark:border-white-dark/10 bg-cyan-50/50 dark:bg-cyan-950/20 text-cyan-800 dark:text-cyan-300 font-black" dir="ltr">مبلغ عراقي تلقائي</th>
                                <th class="min-w-[130px] py-2.5 px-3 text-center border-l border-gray-300 dark:border-white-dark/10">العمولة</th>
                                <th class="min-w-[130px] py-2.5 px-3 text-left border-l border-gray-300 dark:border-white-dark/10 bg-purple-50/50 dark:bg-purple-950/20 text-purple-800 dark:text-purple-300 font-black" dir="ltr">مبلغ العمولة</th>
                                <th class="min-w-[130px] py-2.5 px-3 text-center border-l border-gray-300 dark:border-white-dark/10">كمرك ضريبة</th>
                                <th class="min-w-[130px] py-2.5 px-3 text-left border-l border-gray-300 dark:border-white-dark/10 bg-orange-50/50 dark:bg-orange-950/20 text-orange-800 dark:text-orange-300 font-black" dir="ltr">مبلغ الكمرك</th>
                                <th class="min-w-[150px] py-2.5 px-3 text-left border-l border-gray-300 dark:border-white-dark/10 bg-emerald-50/60 dark:bg-emerald-950/30 text-emerald-800 dark:text-emerald-300 font-black" dir="ltr">
                                    <div>مجموع عراقي</div>
                                    <div class="text-[9px] font-normal text-gray-500">(عراقي + عمولة + كمرك)</div>
                                </th>
                                <th class="min-w-[150px] py-2.5 px-3 text-left border-l border-gray-300 dark:border-white-dark/10 bg-amber-50/60 dark:bg-amber-950/20 text-amber-800 dark:text-amber-300" dir="ltr">
                                    <div>سعر صرف البلد</div>
                                    <div class="text-[9px] font-normal text-gray-400">(للإماراتي والريمبي)</div>
                                </th>
                                <th class="min-w-[150px] py-2.5 px-3 text-left bg-blue-50/60 dark:bg-blue-950/30 text-blue-800 dark:text-blue-300 font-black" dir="ltr">
                                    <div>سعر صرف الدولة</div>
                                    <div class="text-[9px] font-normal text-blue-600 dark:text-blue-400">(النتيجة النهائية)</div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200/80 dark:divide-white-dark/10 font-medium">
                            <template x-for="(item, index) in items" :key="index">
                                <tr class="group hover:bg-primary/5 transition-colors">
                                    <!-- 1. # -->
                                    <td class="text-center font-bold text-gray-500 py-2.5 px-2 border-l border-gray-200 dark:border-white-dark/10 bg-gray-50/40 dark:bg-[#0e1726]/40" x-text="index + 1"></td>

                                    <!-- 2. العملة -->
                                    <td class="py-2.5 px-3 border-l border-gray-200 dark:border-white-dark/10 text-center font-bold"
                                        :class="{
                                            'text-blue-600 dark:text-blue-400': item.currency === 'USD',
                                            'text-emerald-600 dark:text-emerald-400': item.currency === 'AED',
                                            'text-amber-600 dark:text-amber-400': item.currency === 'RMB'
                                        }"
                                        x-text="getPrintCurrencyLabel(item.currency)">
                                    </td>

                                    <!-- 3. سعر الصرف -->
                                    <td class="py-2.5 px-3 border-l border-gray-200 dark:border-white-dark/10 text-left font-bold text-gray-800 dark:text-gray-200" dir="ltr" x-text="formatNumber(item.exchange_rate, 2)"></td>

                                    <!-- 4. مبلغ عراقي تلقائي -->
                                    <td class="py-2.5 px-3 border-l border-gray-200 dark:border-white-dark/10 bg-cyan-50/20 dark:bg-cyan-950/10 text-left font-extrabold text-cyan-800 dark:text-cyan-300" dir="ltr">
                                        <div class="flex items-center justify-between">
                                            <span x-text="formatNumber(item.iqd_base, 2)"></span>
                                            <span class="text-[10px] text-gray-400 font-normal">د.ع</span>
                                        </div>
                                    </td>

                                    <!-- 5. العمولة -->
                                    <td class="py-2.5 px-3 border-l border-gray-200 dark:border-white-dark/10 text-center font-bold text-purple-700 dark:text-purple-300" x-text="item.commission_type === 'per_thousand' ? (item.commission_rate || 0) + ' ‰' : (item.commission_rate || 0) + ' %'"></td>

                                    <!-- 6. مبلغ العمولة -->
                                    <td class="py-2.5 px-3 border-l border-gray-200 dark:border-white-dark/10 bg-purple-50/20 dark:bg-purple-950/10 text-left font-extrabold text-purple-700 dark:text-purple-300" dir="ltr">
                                        <div class="flex items-center justify-between">
                                            <span x-text="formatNumber(item.commission_iqd, 2)"></span>
                                            <span class="text-[10px] text-gray-400 font-normal">د.ع</span>
                                        </div>
                                    </td>

                                    <!-- 7. كمرك ضريبة -->
                                    <td class="py-2.5 px-3 border-l border-gray-200 dark:border-white-dark/10 text-center font-bold text-orange-700 dark:text-orange-300" x-text="item.customs_type === 'per_thousand' ? (item.customs_rate || 0) + ' ‰' : (item.customs_rate || 0) + ' %'"></td>

                                    <!-- 8. مبلغ الكمرك -->
                                    <td class="py-2.5 px-3 border-l border-gray-200 dark:border-white-dark/10 bg-orange-50/20 dark:bg-orange-950/10 text-left font-extrabold text-orange-700 dark:text-orange-300" dir="ltr">
                                        <div class="flex items-center justify-between">
                                            <span x-text="formatNumber(item.customs_iqd, 2)"></span>
                                            <span class="text-[10px] text-gray-400 font-normal">د.ع</span>
                                        </div>
                                    </td>

                                    <!-- 9. مجموع عراقي -->
                                    <td class="py-2.5 px-3 border-l border-gray-200 dark:border-white-dark/10 bg-emerald-50/30 dark:bg-emerald-950/20 text-left font-black text-emerald-700 dark:text-emerald-300" dir="ltr">
                                        <div class="flex items-center justify-between">
                                            <span x-text="formatNumber(item.total_iqd, 2)"></span>
                                            <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-bold">د.ع</span>
                                        </div>
                                    </td>

                                    <!-- 10. سعر صرف البلد -->
                                    <td class="py-2.5 px-3 border-l border-gray-200 dark:border-white-dark/10 text-left font-bold text-amber-700 dark:text-amber-300" dir="ltr">
                                        <span x-text="(item.currency === 'AED' || item.currency === 'RMB') ? formatNumber(item.country_rate, 4) : '—'"></span>
                                    </td>

                                    <!-- 11. سعر صرف الدولة -->
                                    <td class="py-2.5 px-3 bg-blue-50/30 dark:bg-blue-950/20 text-left font-black text-blue-700 dark:text-blue-300" dir="ltr">
                                        <span x-text="(item.currency === 'AED' || item.currency === 'RMB') ? formatNumber(item.country_final_rate, 4) : formatNumber(item.total_iqd, 2)"></span>
                                    </td>
                                </tr>
                            </template>
                        </tbody>

                        <!-- Footer: المجاميع -->
                        <tfoot class="bg-[#eaedf0] dark:bg-[#14233c] font-bold text-xs text-gray-800 dark:text-white-light border-t-2 border-gray-300 dark:border-white-dark/20 sticky bottom-0 z-10 shadow-md">
                            <tr>
                                <td class="text-center py-3 px-2 border-l border-gray-300 dark:border-white-dark/20">∑</td>
                                <td class="py-3 px-3 border-l border-gray-300 dark:border-white-dark/20 text-center font-black">
                                    المجاميع (<span x-text="items.length"></span>)
                                </td>
                                <td class="py-3 px-3 border-l border-gray-300 dark:border-white-dark/20 text-gray-500 text-center text-[11px]">—</td>

                                <td class="py-3 px-3 border-l border-gray-300 dark:border-white-dark/20 font-black text-cyan-800 dark:text-cyan-300 text-xs" dir="ltr">
                                    <span x-text="formatNumber(totalIqdBase, 2)"></span> <span class="text-[9px] font-normal">د.ع</span>
                                </td>

                                <td class="py-3 px-3 border-l border-gray-300 dark:border-white-dark/20 text-gray-500 text-center text-[10px]">إجمالي العمولات:</td>

                                <td class="py-3 px-3 border-l border-gray-300 dark:border-white-dark/20 font-black text-purple-700 dark:text-purple-300 text-xs" dir="ltr">
                                    <span x-text="formatNumber(totalCommissionIqd, 2)"></span> <span class="text-[9px] font-normal">د.ع</span>
                                </td>

                                <td class="py-3 px-3 border-l border-gray-300 dark:border-white-dark/20 text-gray-500 text-center text-[10px]">إجمالي الكمرك:</td>

                                <td class="py-3 px-3 border-l border-gray-300 dark:border-white-dark/20 font-black text-orange-700 dark:text-orange-300 text-xs" dir="ltr">
                                    <span x-text="formatNumber(totalCustomsIqd, 2)"></span> <span class="text-[9px] font-normal">د.ع</span>
                                </td>

                                <td class="py-3 px-3 border-l border-gray-300 dark:border-white-dark/20 font-black text-emerald-700 dark:text-emerald-300 text-sm" dir="ltr">
                                    <span x-text="formatNumber(grandTotalIqd, 2)"></span> <span class="text-[9px] font-bold">د.ع</span>
                                </td>

                                <td class="py-3 px-3 border-l border-gray-300 dark:border-white-dark/20 text-center text-[10px] text-gray-500">—</td>

                                <td class="py-3 px-3 font-black text-blue-700 dark:text-blue-300 text-sm" dir="ltr">
                                    <span x-text="formatNumber(totalCountryFinalRate, 4)"></span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="p-3 bg-gray-50/70 dark:bg-[#121e32]/40 border-t border-gray-200/60 dark:border-white-dark/10 flex items-center justify-between">
                    <span class="text-xs text-gray-500 font-semibold">
                        كشف دينار مؤرشف برقم #{{ $archive->id }} بتاريخ {{ $archive->archive_date->format('Y-m-d') }}
                    </span>
                    <div class="text-xs text-gray-500 flex items-center gap-4 font-semibold">
                        <span>إجمالي العمولات: <strong class="text-purple-600" dir="ltr"><span x-text="formatNumber(totalCommissionIqd, 0)"></span> د.ع</strong></span>
                        <span>إجمالي الكمرك: <strong class="text-orange-600" dir="ltr"><span x-text="formatNumber(totalCustomsIqd, 0)"></span> د.ع</strong></span>
                        <span>المجموع الكلي: <strong class="text-emerald-600" dir="ltr"><span x-text="formatNumber(grandTotalIqd, 0)"></span> د.ع</strong></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- 🖨️ قسم الطباعة الرسمي A4 Landscape -->
        <!-- ======================================================== -->
        <div id="realPrintSection" class="hidden">
            <div style="border-bottom: 2px solid #000; padding-bottom: 8px; margin-bottom: 10px; direction: rtl;">
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

            <!-- جدول الطباعة -->
            <table style="width: 100%; border-collapse: collapse; font-size: 10.5px; text-align: right; direction: rtl;">
                <thead>
                    <tr style="background-color: #f1f3f5; font-weight: bold;">
                        <th style="width: 4%; text-align: center; border: 1px solid #444; padding: 5px;">#</th>
                        <th style="width: 13%; text-align: center; border: 1px solid #444; padding: 5px;">العملة</th>
                        <th style="width: 11%; text-align: left; border: 1px solid #444; padding: 5px;" dir="ltr">سعر الصرف</th>
                        <th style="width: 13%; text-align: left; border: 1px solid #444; padding: 5px;" dir="ltr">مبلغ عراقي</th>
                        <th style="width: 8%; text-align: center; border: 1px solid #444; padding: 5px;">العمولة</th>
                        <th style="width: 10%; text-align: left; border: 1px solid #444; padding: 5px;" dir="ltr">مبلغ العمولة</th>
                        <th style="width: 8%; text-align: center; border: 1px solid #444; padding: 5px;">كمرك ضريبة</th>
                        <th style="width: 10%; text-align: left; border: 1px solid #444; padding: 5px;" dir="ltr">مبلغ الكمرك</th>
                        <th style="width: 12%; text-align: left; border: 1px solid #444; padding: 5px; font-weight: 900;" dir="ltr">مجموع عراقي</th>
                        <th style="width: 10%; text-align: left; border: 1px solid #444; padding: 5px;" dir="ltr">سعر البلد</th>
                        <th style="width: 13%; text-align: left; border: 1px solid #444; padding: 5px; font-weight: 900;" dir="ltr">سعر صرف الدولة</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(item, pIdx) in items" :key="'p' + pIdx">
                        <tr>
                            <td style="text-align: center; border: 1px solid #555; padding: 4px;" x-text="pIdx + 1"></td>
                            <td style="text-align: center; border: 1px solid #555; padding: 4px;" x-text="getPrintCurrencyLabel(item.currency)"></td>
                            <td style="text-align: left; border: 1px solid #555; padding: 4px;" dir="ltr" x-text="formatNumber(item.exchange_rate, 2)"></td>
                            <td style="text-align: left; border: 1px solid #555; padding: 4px; font-weight: bold;" dir="ltr" x-text="formatNumber(item.iqd_base, 2)"></td>
                            <td style="text-align: center; border: 1px solid #555; padding: 4px;" x-text="item.commission_type === 'per_thousand' ? (item.commission_rate || 0) + '‰' : (item.commission_rate || 0) + '%'"></td>
                            <td style="text-align: left; border: 1px solid #555; padding: 4px;" dir="ltr" x-text="formatNumber(item.commission_iqd, 2)"></td>
                            <td style="text-align: center; border: 1px solid #555; padding: 4px;" x-text="item.customs_type === 'per_thousand' ? (item.customs_rate || 0) + '‰' : (item.customs_rate || 0) + '%'"></td>
                            <td style="text-align: left; border: 1px solid #555; padding: 4px;" dir="ltr" x-text="formatNumber(item.customs_iqd, 2)"></td>
                            <td style="text-align: left; border: 1px solid #555; padding: 4px; font-weight: 900; background: #fafafa;" dir="ltr" x-text="formatNumber(item.total_iqd, 2)"></td>
                            <td style="text-align: left; border: 1px solid #555; padding: 4px;" dir="ltr">
                                <span x-text="(item.currency === 'AED' || item.currency === 'RMB') ? formatNumber(item.country_rate, 4) : '—'"></span>
                            </td>
                            <td style="text-align: left; border: 1px solid #555; padding: 4px; font-weight: 900; background: #f0f7ff;" dir="ltr">
                                <span x-text="(item.currency === 'AED' || item.currency === 'RMB') ? formatNumber(item.country_final_rate, 4) : formatNumber(item.total_iqd, 2)"></span>
                            </td>
                        </tr>
                    </template>
                </tbody>
                <tfoot>
                    <tr style="background-color: #e8edf0; font-weight: 900; border-top: 2px solid #000;">
                        <td colspan="2" style="text-align: center; border: 1px solid #444; padding: 5px;">المجاميع (<span x-text="items.length"></span> حركة)</td>
                        <td style="text-align: center; border: 1px solid #444; padding: 5px;">—</td>
                        <td style="text-align: left; border: 1px solid #444; padding: 5px;" dir="ltr"><span x-text="formatNumber(totalIqdBase, 2)"></span> د.ع</td>
                        <td style="text-align: center; border: 1px solid #444; padding: 5px; font-size: 9px;">عمولات:</td>
                        <td style="text-align: left; border: 1px solid #444; padding: 5px;" dir="ltr"><span x-text="formatNumber(totalCommissionIqd, 2)"></span> د.ع</td>
                        <td style="text-align: center; border: 1px solid #444; padding: 5px; font-size: 9px;">كمرك:</td>
                        <td style="text-align: left; border: 1px solid #444; padding: 5px;" dir="ltr"><span x-text="formatNumber(totalCustomsIqd, 2)"></span> د.ع</td>
                        <td style="text-align: left; border: 1px solid #444; padding: 5px; font-size: 12px; font-weight: 900;" dir="ltr"><span x-text="formatNumber(grandTotalIqd, 2)"></span> د.ع</td>
                        <td style="text-align: center; border: 1px solid #444; padding: 5px;">—</td>
                        <td style="text-align: left; border: 1px solid #444; padding: 5px; font-size: 12px; font-weight: 900;" dir="ltr"><span x-text="formatNumber(totalCountryFinalRate, 4)"></span></td>
                    </tr>
                </tfoot>
            </table>

            <div style="margin-top: 25px; display: flex; justify-content: space-between; font-size: 11px; font-weight: bold;">
                <div style="text-align: center; width: 180px; border-top: 1px dashed #666; padding-top: 5px;">توقيع المحاسب</div>
                <div style="text-align: center; width: 180px; border-top: 1px dashed #666; padding-top: 5px;">الختم والاعتماد</div>
            </div>
        </div>

    </div>

    <script>
        function archivedDinarSheet(config) {
            return {
                sheetTitle: config.title || 'حاسبة الصرف دينار',
                archiveDate: config.date || '',
                items: config.items || [],

                triggerPrint() {
                    window.print();
                },

                getPrintCurrencyLabel(c) {
                    switch (c) {
                        case 'USD': return 'دولار ($)';
                        case 'AED': return 'درهم (AED)';
                        case 'RMB': return 'ريمبي (¥)';
                        default: return c || 'USD';
                    }
                },

                parseCleanNumber(val) {
                    if (val === null || val === undefined || val === '') return 0;
                    const clean = String(val).replace(/,/g, '').trim();
                    const n = parseFloat(clean);
                    return isNaN(n) ? 0 : n;
                },

                get totalIqdBase() {
                    return this.items.reduce((s, it) => s + (this.parseCleanNumber(it.iqd_base) || 0), 0);
                },

                get totalCommissionIqd() {
                    return this.items.reduce((s, it) => s + (this.parseCleanNumber(it.commission_iqd) || 0), 0);
                },

                get totalCustomsIqd() {
                    return this.items.reduce((s, it) => s + (this.parseCleanNumber(it.customs_iqd) || 0), 0);
                },

                get grandTotalIqd() {
                    return this.items.reduce((s, it) => s + (this.parseCleanNumber(it.total_iqd) || 0), 0);
                },

                get totalCountryFinalRate() {
                    return this.items.reduce((s, it) => {
                        const val = (it.currency === 'AED' || it.currency === 'RMB')
                            ? (this.parseCleanNumber(it.country_final_rate) || 0)
                            : (this.parseCleanNumber(it.total_iqd) || 0);
                        return s + val;
                    }, 0);
                },

                formatNumber(val, decimals = 2) {
                    if (val === null || val === undefined || val === '') return '0';
                    const clean = String(val).replace(/,/g, '').trim();
                    const num = Number(clean);
                    if (isNaN(num)) return '0';
                    const dec = (decimals !== null && decimals !== undefined) ? decimals : 2;
                    return num.toLocaleString('en-US', {
                        minimumFractionDigits: dec,
                        maximumFractionDigits: dec
                    });
                },

                exportToExcel() {
                    if (this.items.length === 0) {
                        alert('لا توجد بيانات مدخلة.');
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

                        aoa.push([
                            '#',
                            'العملة',
                            'سعر الصرف',
                            'مبلغ عراقي تلقائي (د.ع)',
                            'العمولة',
                            'مبلغ العمولة (د.ع)',
                            'كمرك ضريبة',
                            'مبلغ الكمرك (د.ع)',
                            'مجموع عراقي (د.ع)',
                            'سعر صرف البلد',
                            'سعر صرف الدولة (النهائي)'
                        ]);

                        this.items.forEach((it, idx) => {
                            let curr = 'دولار ($)';
                            if (it.currency === 'AED') curr = 'درهم إماراتي (AED)';
                            if (it.currency === 'RMB') curr = 'ريمبي صيني (¥)';

                            const comm = it.commission_type === 'per_thousand'
                                ? (it.commission_rate || 0) + ' ‰'
                                : (it.commission_rate || 0) + ' %';

                            const cust = it.customs_type === 'per_thousand'
                                ? (it.customs_rate || 0) + ' ‰'
                                : (it.customs_rate || 0) + ' %';

                            const countryFinal = (it.currency === 'AED' || it.currency === 'RMB')
                                ? (this.parseCleanNumber(it.country_final_rate) || 0)
                                : (this.parseCleanNumber(it.total_iqd) || 0);

                            aoa.push([
                                idx + 1,
                                curr,
                                this.parseCleanNumber(it.exchange_rate) || 0,
                                this.parseCleanNumber(it.iqd_base) || 0,
                                comm,
                                this.parseCleanNumber(it.commission_iqd) || 0,
                                cust,
                                this.parseCleanNumber(it.customs_iqd) || 0,
                                this.parseCleanNumber(it.total_iqd) || 0,
                                (it.currency === 'AED' || it.currency === 'RMB') ? (this.parseCleanNumber(it.country_rate) || 0) : '-',
                                countryFinal
                            ]);
                        });

                        aoa.push([
                            'المجاميع',
                            '',
                            '',
                            this.totalIqdBase,
                            '',
                            this.totalCommissionIqd,
                            '',
                            this.totalCustomsIqd,
                            this.grandTotalIqd,
                            '',
                            this.totalCountryFinalRate
                        ]);

                        const ws = XLSX.utils.aoa_to_sheet(aoa);
                        ws['!cols'] = [
                            { wch: 6 },
                            { wch: 22 },
                            { wch: 14 },
                            { wch: 18 },
                            { wch: 14 },
                            { wch: 16 },
                            { wch: 14 },
                            { wch: 16 },
                            { wch: 18 },
                            { wch: 16 },
                            { wch: 22 }
                        ];
                        ws['!views'] = [{ RTL: true }];

                        const wb = XLSX.utils.book_new();
                        XLSX.utils.book_append_sheet(wb, ws, 'كشف الدينار المؤرشف');
                        XLSX.writeFile(wb, title.replace(/[\/\\?%*:|"<>]/g, '_') + '_' + dateStr + '.xlsx');
                    }
                }
            };
        }
    </script>
</x-layout.custom-default>
