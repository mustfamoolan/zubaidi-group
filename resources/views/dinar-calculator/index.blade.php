<x-layout.custom-default>
    <!-- Include SheetJS for .xlsx export -->
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

    <!-- Print Stylesheet -->
    <style>
        @media print {
            header, footer, nav, aside, .toggle-sidebar,
            .main-container > aside, .main-content > header, .main-content > footer,
            .screen_loader, [x-data="scrollToTop"], .theme-customiser,
            .screen-section, .print\:hidden {
                display: none !important;
            }
            html, body, .main-container, .main-content, .dvanimation {
                margin: 0 !important; padding: 0 !important;
                background: #fff !important; color: #000 !important;
                width: 100% !important; min-height: auto !important;
            }
            @page { size: A4 landscape; margin: 6mm 5mm; }
            #dinarPrintSection {
                display: block !important;
                font-family: 'Segoe UI', Tahoma, Arial, sans-serif !important;
                direction: rtl !important;
            }
            #dinarPrintSection table { width: 100% !important; border-collapse: collapse !important; }
            #dinarPrintSection th, #dinarPrintSection td {
                border: 1px solid #333 !important;
                padding: 5px 6px !important;
                font-size: 11px !important;
                color: #000 !important;
            }
            #dinarPrintSection th { background-color: #f1f3f5 !important; font-weight: bold !important; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            #dinarPrintSection .mini-kpi { border: 1px solid #333 !important; background: #fafafa !important; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        }
    </style>

    <div x-data="dinarCalculator({
            initialData: {{ json_encode($data) }},
            csrfToken: '{{ csrf_token() }}'
         })"
         x-init="initSheet()"
         class="space-y-6">

        <!-- ======================================================== -->
        <!-- 🖥️ قسم الشاشة -->
        <!-- ======================================================== -->
        <div class="screen-section space-y-6 print:hidden">

            <!-- Top Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <ul class="flex space-x-2 rtl:space-x-reverse text-sm mb-2">
                        <li><a href="{{ url('/') }}" class="text-primary hover:underline">لوحة التحكم</a></li>
                        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                            <span class="text-black dark:text-white-light font-medium">حاسبة الصرف دينار</span>
                        </li>
                    </ul>
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-primary/10 text-primary rounded-xl">
                            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2Z" stroke="currentColor" stroke-width="1.8"/>
                                <path d="M8 8.5C8.5 7.5 10 7 12 7C14.2091 7 16 8.34315 16 10C16 11.6569 14.2091 12.5 12 12.5C9.79086 12.5 8 13.3431 8 15C8 16.6569 9.79086 18 12 18C14 18 15.5 17.5 16 16.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-3">
                                <input type="text"
                                       x-model="sheetTitle"
                                       @input="onSheetChanged()"
                                       placeholder="عنوان حاسبة الصرف دينار..."
                                       class="text-xl font-bold text-[#0e1726] dark:text-white-light bg-transparent border-0 border-b border-transparent hover:border-gray-300 focus:border-primary focus:ring-0 p-0 transition">
                                <div class="flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold select-none"
                                     :class="saveStatus === 'saving' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200' : 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200'">
                                    <span class="w-2 h-2 rounded-full" :class="saveStatus === 'saving' ? 'bg-amber-500 animate-ping' : 'bg-emerald-500'"></span>
                                    <span x-text="saveStatus === 'saving' ? 'جاري الحفظ...' : 'تم الحفظ تلقائياً ✓'"></span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                احتساب سعر الصرف، مبالغ العمولات والكمرك، ومجموع الدينار مضروباً بسعر صرف الدولة (درهم / ريمبي)
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex items-center flex-wrap gap-2">
                    <button type="button" @click="appendNewRow()" class="btn btn-primary btn-sm flex items-center gap-1.5 shadow-sm">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M12 4V20M20 12H4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                        إضافة حركة جديدة
                    </button>

                    <button type="button" @click="openArchiveModal()" class="btn btn-warning btn-sm flex items-center gap-1.5 shadow-sm text-black font-bold" title="أرشفة الكشف الحالي وتفريغ الجدول للبدء بيوم جديد">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="3" y="4" width="18" height="4" rx="1" stroke="currentColor" stroke-width="1.8"/>
                            <path d="M5 8V18C5 19.1046 5.89543 20 7 20H17C18.1046 20 19 19.1046 19 18V8" stroke="currentColor" stroke-width="1.8"/>
                            <path d="M10 12H14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>
                        أرشفة وحفظ الكشف
                    </button>

                    <a href="{{ route('calculator-archives.index', ['type' => 'dinar']) }}" class="btn btn-outline-info btn-sm flex items-center gap-1.5 shadow-sm" title="سجل الكشوفات المؤرشفة السابقة">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 6H20M4 12H20M4 18H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        سجل الأرشيف
                    </a>

                    <button type="button" @click="exportToExcel()" class="btn btn-outline-success btn-sm flex items-center gap-1.5 shadow-sm">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M4 16V17C4 18.8856 4 19.8284 4.58579 20.4142C5.17157 21 6.11438 21 8 21H16C17.8856 21 18.8284 21 19.4142 20.4142C20 19.8284 20 18.8856 20 17V16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path opacity="0.5" d="M12 3V15M12 15L8.5 11.5M12 15L15.5 11.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Excel (.xlsx)
                    </button>
                    <button type="button" @click="window.print()" class="btn btn-outline-secondary btn-sm flex items-center gap-1.5 shadow-sm">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M7 8V3H17V8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path opacity="0.5" d="M7 16H17V21H7V16Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M3 11C3 9.34315 4.34315 8 6 8H18C19.6569 8 21 9.34315 21 11V16C21 17.6569 19.6569 19 18 19H17V16H7V19H6C4.34315 19 3 17.6569 3 16V11Z" stroke="currentColor" stroke-width="1.5"/></svg>
                        طباعة
                    </button>
                    <button type="button" @click="clearSheetPrompt()" class="btn btn-outline-danger btn-sm flex items-center gap-1.5 shadow-sm">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M19 7L18.1327 19.1425C18.0579 20.1891 17.187 21 16.1378 21H7.86224C6.81296 21 5.94208 20.1891 5.86732 19.1425L5 7M10 11V17M14 11V17M15 7V4C15 3.44772 14.5523 3 14 3H10C9.44772 3 9 3.44772 9 4V7M4 7H20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        تفريغ
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="panel p-0 border border-gray-300 dark:border-white-dark/20 rounded-xl overflow-hidden shadow-sm">
                <div class="table-responsive max-h-[700px] overflow-auto">
                    <table class="w-full text-xs text-right border-collapse">
                        <thead class="sticky top-0 bg-[#eaedf1] dark:bg-[#0e1726] z-10 border-b border-gray-300 dark:border-white-dark/20 text-gray-800 dark:text-gray-200 font-bold text-[11px] shadow-sm">
                            <tr>
                                <th class="w-12 text-center py-2.5 px-2 border-l border-gray-300 dark:border-white-dark/10">#</th>
                                <th class="w-44 text-center py-2.5 px-3 border-l border-gray-300 dark:border-white-dark/10">العملة</th>
                                <th class="min-w-[140px] py-2.5 px-3 text-left border-l border-gray-300 dark:border-white-dark/10" dir="ltr">سعر الصرف</th>
                                <th class="min-w-[145px] py-2.5 px-3 text-left border-l border-gray-300 dark:border-white-dark/10 bg-cyan-50/50 dark:bg-cyan-950/20 text-cyan-800 dark:text-cyan-300 font-black" dir="ltr">مبلغ عراقي تلقائي</th>
                                <th class="min-w-[150px] py-2.5 px-3 text-center border-l border-gray-300 dark:border-white-dark/10">العمولة</th>
                                <th class="min-w-[130px] py-2.5 px-3 text-left border-l border-gray-300 dark:border-white-dark/10 bg-purple-50/50 dark:bg-purple-950/20 text-purple-800 dark:text-purple-300 font-black" dir="ltr">مبلغ العمولة</th>
                                <th class="min-w-[150px] py-2.5 px-3 text-center border-l border-gray-300 dark:border-white-dark/10">كمرك ضريبة</th>
                                <th class="min-w-[130px] py-2.5 px-3 text-left border-l border-gray-300 dark:border-white-dark/10 bg-orange-50/50 dark:bg-orange-950/20 text-orange-800 dark:text-orange-300 font-black" dir="ltr">مبلغ الكمرك</th>
                                <th class="min-w-[160px] py-2.5 px-3 text-left border-l border-gray-300 dark:border-white-dark/10 bg-emerald-50/60 dark:bg-emerald-950/30 text-emerald-800 dark:text-emerald-300 font-black" dir="ltr">
                                    <div>مجموع عراقي</div>
                                    <div class="text-[9px] font-normal text-gray-500">(عراقي + عمولة + كمرك)</div>
                                </th>
                                <th class="min-w-[160px] py-2.5 px-3 text-left border-l border-gray-300 dark:border-white-dark/10 bg-amber-50/60 dark:bg-amber-950/20 text-amber-800 dark:text-amber-300" dir="ltr">
                                    <div>سعر صرف البلد</div>
                                    <div class="text-[9px] font-normal text-gray-400">(للإماراتي والريمبي)</div>
                                </th>
                                <th class="min-w-[160px] py-2.5 px-3 text-left border-l border-gray-300 dark:border-white-dark/10 bg-blue-50/60 dark:bg-blue-950/30 text-blue-800 dark:text-blue-300 font-black" dir="ltr">
                                    <div>سعر صرف الدولة</div>
                                    <div class="text-[9px] font-normal text-blue-600 dark:text-blue-400">(النتيجة النهائية)</div>
                                </th>
                                <th class="w-14 text-center py-2.5 px-2">حذف</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200/80 dark:divide-white-dark/10 font-medium">
                            <template x-for="(item, index) in items" :key="index">
                                <tr class="group hover:bg-primary/5 transition-colors focus-within:bg-blue-50/30 dark:focus-within:bg-[#132238]">

                                    <!-- # (التسلسل) -->
                                    <td class="text-center font-bold text-gray-500 py-1.5 px-2 border-l border-gray-200 dark:border-white-dark/10 bg-gray-50/40 dark:bg-[#0e1726]/40" x-text="index + 1"></td>

                                    <!-- العملة -->
                                    <td class="p-0 border-l border-gray-200 dark:border-white-dark/10">
                                        <select x-model="item.currency"
                                                @change="onCurrencyChanged(item)"
                                                class="w-full py-2.5 px-3 text-xs font-bold bg-transparent border-0 focus:ring-1 focus:ring-primary focus:bg-white dark:focus:bg-[#0e1726] rounded-none outline-none cursor-pointer"
                                                :class="{
                                                    'text-blue-600 dark:text-blue-400': item.currency === 'USD',
                                                    'text-emerald-600 dark:text-emerald-400': item.currency === 'AED',
                                                    'text-amber-600 dark:text-amber-400': item.currency === 'RMB'
                                                }">
                                            <option value="USD">💵 دولار ($)</option>
                                            <option value="AED">🇦🇪 درهم إماراتي (AED)</option>
                                            <option value="RMB">🇨🇳 ريمبي صيني (¥)</option>
                                        </select>
                                    </td>

                                    <!-- سعر الصرف -->
                                    <td class="p-0 border-l border-gray-200 dark:border-white-dark/10" dir="ltr">
                                        <input type="number" step="any" x-model="item.exchange_rate"
                                               @input="onRowChanged(item)" @keydown.enter.prevent="onEnterKey(index)"
                                               placeholder="1530"
                                               class="w-full py-2.5 px-3 text-xs text-left font-bold text-gray-800 dark:text-gray-200 bg-transparent border-0 focus:ring-1 focus:ring-primary focus:bg-white dark:focus:bg-[#0e1726] rounded-none outline-none">
                                    </td>

                                    <!-- مبلغ عراقي تلقائي (= سعر الصرف) -->
                                    <td class="py-2.5 px-3 border-l border-gray-200 dark:border-white-dark/10 bg-cyan-50/20 dark:bg-cyan-950/10 select-all" dir="ltr">
                                        <div class="flex items-center justify-between font-extrabold text-cyan-800 dark:text-cyan-300 text-xs">
                                            <span x-text="formatNumber(item.iqd_base, 2)">0</span>
                                            <span class="text-[10px] text-gray-400 font-normal">د.ع</span>
                                        </div>
                                    </td>

                                    <!-- العمولة (نسبة + نوع) -->
                                    <td class="p-0 border-l border-gray-200 dark:border-white-dark/10">
                                        <div class="flex items-center gap-1 px-1.5">
                                            <input type="number" step="any" x-model="item.commission_rate"
                                                   @input="onRowChanged(item)" @keydown.enter.prevent="onEnterKey(index)"
                                                   placeholder="1"
                                                   class="w-16 py-2 px-1 text-xs text-center font-bold text-purple-700 dark:text-purple-300 bg-transparent border-0 focus:ring-1 focus:ring-purple-500 focus:bg-white dark:focus:bg-[#0e1726] rounded-none outline-none">
                                            <select x-model="item.commission_type" @change="onRowChanged(item)"
                                                    class="py-1 px-1 text-[11px] font-bold bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 rounded border-0 outline-none cursor-pointer">
                                                <option value="per_thousand">‰ بالألف</option>
                                                <option value="percent">% بالمئة</option>
                                            </select>
                                        </div>
                                    </td>

                                    <!-- مبلغ العمولة (محسوب) -->
                                    <td class="py-2.5 px-3 border-l border-gray-200 dark:border-white-dark/10 bg-purple-50/20 dark:bg-purple-950/10 select-all" dir="ltr">
                                        <div class="flex items-center justify-between font-extrabold text-purple-700 dark:text-purple-300 text-xs">
                                            <span x-text="formatNumber(item.commission_iqd, 2)">0</span>
                                            <span class="text-[10px] text-gray-400 font-normal">د.ع</span>
                                        </div>
                                    </td>

                                    <!-- كمرك ضريبة (نسبة + نوع) -->
                                    <td class="p-0 border-l border-gray-200 dark:border-white-dark/10">
                                        <div class="flex items-center gap-1 px-1.5">
                                            <input type="number" step="any" x-model="item.customs_rate"
                                                   @input="onRowChanged(item)" @keydown.enter.prevent="onEnterKey(index)"
                                                   placeholder="0"
                                                   class="w-16 py-2 px-1 text-xs text-center font-bold text-orange-700 dark:text-orange-300 bg-transparent border-0 focus:ring-1 focus:ring-orange-500 focus:bg-white dark:focus:bg-[#0e1726] rounded-none outline-none">
                                            <select x-model="item.customs_type" @change="onRowChanged(item)"
                                                    class="py-1 px-1 text-[11px] font-bold bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 rounded border-0 outline-none cursor-pointer">
                                                <option value="percent">% بالمئة</option>
                                                <option value="per_thousand">‰ بالألف</option>
                                            </select>
                                        </div>
                                    </td>

                                    <!-- مبلغ الكمرك (محسوب) -->
                                    <td class="py-2.5 px-3 border-l border-gray-200 dark:border-white-dark/10 bg-orange-50/20 dark:bg-orange-950/10 select-all" dir="ltr">
                                        <div class="flex items-center justify-between font-extrabold text-orange-700 dark:text-orange-300 text-xs">
                                            <span x-text="formatNumber(item.customs_iqd, 2)">0</span>
                                            <span class="text-[10px] text-gray-400 font-normal">د.ع</span>
                                        </div>
                                    </td>

                                    <!-- مجموع عراقي (عراقي + عمولة + كمرك) -->
                                    <td class="py-2.5 px-3 border-l border-gray-200 dark:border-white-dark/10 bg-emerald-50/30 dark:bg-emerald-950/20 select-all" dir="ltr">
                                        <div class="flex items-center justify-between font-black text-emerald-700 dark:text-emerald-300 text-xs">
                                            <span x-text="formatNumber(item.total_iqd, 2)">0</span>
                                            <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-bold">د.ع</span>
                                        </div>
                                    </td>

                                    <!-- سعر صرف البلد (للإماراتي والريمبي) -->
                                    <td class="p-0 border-l border-gray-200 dark:border-white-dark/10 bg-amber-50/10 dark:bg-amber-950/10">
                                        <template x-if="item.currency === 'AED' || item.currency === 'RMB'">
                                            <div class="flex items-center px-2">
                                                <input type="number" step="any" x-model="item.country_rate"
                                                       @input="onRowChanged(item)" @keydown.enter.prevent="onEnterKey(index)"
                                                       :placeholder="item.currency === 'AED' ? 'سعر الدرهم...' : 'سعر الريمبي...'"
                                                       class="w-full py-2 px-1 text-xs text-left font-bold text-amber-700 dark:text-amber-300 bg-transparent border-0 focus:ring-1 focus:ring-amber-500 focus:bg-white dark:focus:bg-[#0e1726] rounded-none outline-none">
                                            </div>
                                        </template>
                                        <template x-if="item.currency === 'USD'">
                                            <div class="py-2.5 px-3 text-center text-gray-300 dark:text-gray-700 text-[11px] select-none">— دولار أمريكي —</div>
                                        </template>
                                    </td>

                                    <!-- سعر صرف الدولة (المجموع عراقي × سعر البلد) -->
                                    <td class="py-2.5 px-3 border-l border-gray-200 dark:border-white-dark/10 bg-blue-50/30 dark:bg-blue-950/20 select-all" dir="ltr">
                                        <template x-if="item.currency === 'AED' || item.currency === 'RMB'">
                                            <div class="flex items-center justify-between font-black text-blue-700 dark:text-blue-300 text-xs">
                                                <span x-text="formatNumber(item.country_final_rate, 4)">0</span>
                                                <span class="text-[10px] text-blue-500 font-semibold" x-text="item.currency === 'AED' ? 'AED' : '¥'"></span>
                                            </div>
                                        </template>
                                        <template x-if="item.currency === 'USD'">
                                            <div class="flex items-center justify-between font-bold text-gray-700 dark:text-gray-300 text-xs">
                                                <span x-text="formatNumber(item.total_iqd, 2)">0</span>
                                                <span class="text-[10px] text-gray-400 font-normal">د.ع / $</span>
                                            </div>
                                        </template>
                                    </td>

                                    <!-- حذف -->
                                    <td class="text-center py-2 px-1">
                                        <button type="button" @click="removeRow(index)"
                                                class="w-6 h-6 rounded flex items-center justify-center text-gray-400 hover:text-danger hover:bg-danger/10 mx-auto transition">✕</button>
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

                                <!-- إجمالي مبالغ عراقي تلقائي -->
                                <td class="py-3 px-3 border-l border-gray-300 dark:border-white-dark/20 font-black text-cyan-800 dark:text-cyan-300 text-xs" dir="ltr">
                                    <span x-text="formatNumber(totalIqdBase, 2)"></span> <span class="text-[9px] font-normal">د.ع</span>
                                </td>

                                <td class="py-3 px-3 border-l border-gray-300 dark:border-white-dark/20 text-gray-500 text-center text-[10px]">إجمالي العمولات:</td>

                                <!-- إجمالي مبلغ العمولة -->
                                <td class="py-3 px-3 border-l border-gray-300 dark:border-white-dark/20 font-black text-purple-700 dark:text-purple-300 text-xs" dir="ltr">
                                    <span x-text="formatNumber(totalCommissionIqd, 2)"></span> <span class="text-[9px] font-normal">د.ع</span>
                                </td>

                                <td class="py-3 px-3 border-l border-gray-300 dark:border-white-dark/20 text-gray-500 text-center text-[10px]">إجمالي الكمرك:</td>

                                <!-- إجمالي مبلغ الكمرك -->
                                <td class="py-3 px-3 border-l border-gray-300 dark:border-white-dark/20 font-black text-orange-700 dark:text-orange-300 text-xs" dir="ltr">
                                    <span x-text="formatNumber(totalCustomsIqd, 2)"></span> <span class="text-[9px] font-normal">د.ع</span>
                                </td>

                                <!-- إجمالي مجموع عراقي -->
                                <td class="py-3 px-3 border-l border-gray-300 dark:border-white-dark/20 font-black text-emerald-700 dark:text-emerald-300 text-sm" dir="ltr">
                                    <span x-text="formatNumber(grandTotalIqd, 2)"></span> <span class="text-[9px] font-bold">د.ع</span>
                                </td>

                                <td class="py-3 px-3 border-l border-gray-300 dark:border-white-dark/20 text-center text-[10px] text-gray-500">—</td>

                                <!-- إجمالي سعر صرف الدولة -->
                                <td class="py-3 px-3 border-l border-gray-300 dark:border-white-dark/20 font-black text-blue-700 dark:text-blue-300 text-sm" dir="ltr">
                                    <span x-text="formatNumber(totalCountryFinalRate, 4)"></span>
                                </td>

                                <td class="py-3 px-2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Bottom bar -->
                <div class="p-3 bg-gray-50/70 dark:bg-[#121e32]/40 border-t border-gray-200/60 dark:border-white-dark/10 flex items-center justify-between">
                    <button type="button" @click="appendNewRow()" class="btn btn-outline-primary btn-sm flex items-center gap-1.5">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M12 4V20M20 12H4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                        إضافة حركة جديدة
                    </button>
                    <div class="text-xs text-gray-500 flex items-center gap-4 font-semibold">
                        <span>إجمالي العمولات: <strong class="text-purple-600" dir="ltr"><span x-text="formatNumber(totalCommissionIqd, 2)"></span> د.ع</strong></span>
                        <span>إجمالي الكمرك: <strong class="text-orange-600" dir="ltr"><span x-text="formatNumber(totalCustomsIqd, 2)"></span> د.ع</strong></span>
                        <span>مجموع عراقي: <strong class="text-emerald-600" dir="ltr"><span x-text="formatNumber(grandTotalIqd, 2)"></span> د.ع</strong></span>
                    </div>
                </div>
            </div>

        </div>

        <!-- ======================================================== -->
        <!-- 🖨️ قسم الطباعة الرسمي A4 Landscape -->
        <!-- ======================================================== -->
        <div id="dinarPrintSection" class="hidden">

            <!-- رأس المستند -->
            <div style="border-bottom: 2px solid #000; padding-bottom: 8px; margin-bottom: 8px; direction: rtl;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <div style="font-size: 18px; font-weight: 900; margin: 0;">مجموعة الزبيدي للتجارة والشحن الدولي</div>
                        <div style="font-size: 13px; font-weight: bold; margin: 3px 0 0 0;" x-text="sheetTitle || 'حاسبة الصرف دينار'"></div>
                    </div>
                    <div style="text-align: left; font-size: 11px; font-weight: 600;">
                        <div>تاريخ: <strong>{{ date('Y-m-d') }}</strong></div>
                        <div>الوقت: <strong>{{ date('h:i A') }}</strong></div>
                        <div>عدد الحركات: <strong x-text="printableItems.length"></strong></div>
                    </div>
                </div>
            </div>

            <!-- بطاقات ملخص صغيرة للطباعة -->
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 6px; margin-bottom: 10px; direction: rtl;">
                <div class="mini-kpi" style="border: 1px solid #333; padding: 4px 7px; background: #fafafa; border-radius: 4px; text-align: center;">
                    <div style="font-size: 10px; color: #666; margin-bottom: 2px;">إجمالي الأساسي (عراقي)</div>
                    <div style="font-size: 12px; font-weight: 900;" dir="ltr"><span x-text="formatNumber(totalIqdBase, 2)"></span> <span style="font-size:9px;">د.ع</span></div>
                </div>
                <div class="mini-kpi" style="border: 1px solid #333; padding: 4px 7px; background: #fafafa; border-radius: 4px; text-align: center;">
                    <div style="font-size: 10px; color: #666; margin-bottom: 2px;">إجمالي العمولات</div>
                    <div style="font-size: 12px; font-weight: 900;" dir="ltr"><span x-text="formatNumber(totalCommissionIqd, 2)"></span> <span style="font-size:9px;">د.ع</span></div>
                </div>
                <div class="mini-kpi" style="border: 1px solid #333; padding: 4px 7px; background: #fafafa; border-radius: 4px; text-align: center;">
                    <div style="font-size: 10px; color: #666; margin-bottom: 2px;">إجمالي الكمرك والضريبة</div>
                    <div style="font-size: 12px; font-weight: 900;" dir="ltr"><span x-text="formatNumber(totalCustomsIqd, 2)"></span> <span style="font-size:9px;">د.ع</span></div>
                </div>
                <div class="mini-kpi" style="border: 2px solid #000; padding: 4px 7px; background: #e8f5e9; border-radius: 4px; text-align: center;">
                    <div style="font-size: 10px; color: #333; font-weight: bold; margin-bottom: 2px;">إجمالي المجموع عراقي</div>
                    <div style="font-size: 13px; font-weight: 900;" dir="ltr"><span x-text="formatNumber(grandTotalIqd, 2)"></span> <span style="font-size:10px; font-weight: bold;">د.ع</span></div>
                </div>
            </div>

            <!-- جدول الطباعة -->
            <table style="width: 100%; border-collapse: collapse; font-size: 10.5px; text-align: right; direction: rtl;">
                <thead>
                    <tr style="background-color: #f1f3f5; font-weight: bold;">
                        <th style="width: 4%; text-align: center; border: 1px solid #444; padding: 5px;">#</th>
                        <th style="width: 12%; text-align: center; border: 1px solid #444; padding: 5px;">العملة</th>
                        <th style="width: 11%; text-align: left; border: 1px solid #444; padding: 5px;" dir="ltr">سعر الصرف</th>
                        <th style="width: 12%; text-align: left; border: 1px solid #444; padding: 5px;" dir="ltr">مبلغ عراقي</th>
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
                    <template x-for="(item, pIdx) in printableItems" :key="'p' + pIdx">
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
                        <td colspan="2" style="text-align: center; border: 1px solid #444; padding: 5px;">المجاميع (<span x-text="printableItems.length"></span> حركة)</td>
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

            <!-- توقيعات -->
            <div style="margin-top: 25px; display: flex; justify-content: space-between; font-size: 11px; font-weight: bold;">
                <div style="text-align: center; width: 180px; border-top: 1px dashed #666; padding-top: 5px;">توقيع المحاسب</div>
                <div style="text-align: center; width: 180px; border-top: 1px dashed #666; padding-top: 5px;">الختم والاعتماد</div>
            </div>

        </div>

        <!-- ======================================================== -->
        <!-- 📦 مودال أرشفة الكشف الحالي وتفريغ الجدول -->
        <!-- ======================================================== -->
        <div x-show="isArchiveModalOpen"
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="bg-white dark:bg-[#0e1726] rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-gray-100 dark:border-white-dark/10"
                 @click.outside="if(!isArchiving) isArchiveModalOpen = false">

                <!-- رأس المودال -->
                <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-white-dark/10 mb-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="3" y="4" width="18" height="4" rx="1" stroke="currentColor" stroke-width="1.8"/>
                                <path d="M5 8V18C5 19.1046 5.89543 20 7 20H17C18.1046 20 19 19.1046 19 18V8" stroke="currentColor" stroke-width="1.8"/>
                                <path d="M10 12H14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900 dark:text-white">أرشفة وحفظ حاسبة الصرف دينار</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">حفظ نسخة نهائية دائمة وتفريغ الجدول ليوم جديد</p>
                        </div>
                    </div>
                    <button type="button" @click="if(!isArchiving) isArchiveModalOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M6 18L18 6M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    </button>
                </div>

                <!-- معاينة سريعة لأرقام الكشف -->
                <div class="grid grid-cols-3 gap-2 p-3 bg-gray-50 dark:bg-[#121e32]/60 rounded-xl border border-gray-100 dark:border-white-dark/10 mb-5 text-center">
                    <div>
                        <div class="text-[11px] text-gray-500">عدد الحركات</div>
                        <div class="text-sm font-extrabold text-gray-800 dark:text-gray-200" x-text="printableItems.length + ' حركات'"></div>
                    </div>
                    <div>
                        <div class="text-[11px] text-gray-500">مجموع عراقي</div>
                        <div class="text-sm font-extrabold text-emerald-600 dark:text-emerald-400" dir="ltr"><span x-text="formatNumber(grandTotalIqd, 2)"></span> د.ع</div>
                    </div>
                    <div>
                        <div class="text-[11px] text-gray-500">سعر صرف الدولة</div>
                        <div class="text-sm font-extrabold text-blue-600 dark:text-blue-400" dir="ltr"><span x-text="formatNumber(totalCountryFinalRate, 2)"></span></div>
                    </div>
                </div>

                <!-- حقول الإدخال -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">عنوان الكشف في الأرشيف <span class="text-danger">*</span></label>
                        <input type="text" x-model="archiveTitle" placeholder="مثال: حاسبة صرف دينار - الأحد"
                               class="form-input text-xs w-full font-semibold focus:border-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">تاريخ الكشف <span class="text-danger">*</span></label>
                        <input type="date" x-model="archiveDate"
                               class="form-input text-xs w-full focus:border-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">ملاحظات أو بيان توضيحي (اختياري)</label>
                        <textarea x-model="archiveNotes" rows="2" placeholder="أي تفاصيل أو ملاحظات تخص هذا اليوم..."
                                  class="form-textarea text-xs w-full focus:border-amber-500"></textarea>
                    </div>
                </div>

                <!-- تنبيه للمستخدم -->
                <div class="mt-4 p-3 rounded-lg bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-900/40 text-[11px] text-amber-800 dark:text-amber-200 flex items-start gap-2">
                    <svg class="w-4 h-4 mt-0.5 shrink-0" viewBox="0 0 24 24" fill="none"><path d="M12 9V14M12 17.5V18M12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                    <span>سيتم حفظ نسخة مطابقة ثابتة غير قابلة للتعديل في الأرشيف (مع إمكانية عرضها وطباعتها وتصديرها Excel بأي وقت)، وسيتم تفريغ هذا الجدول للبدء باليوم التالي.</span>
                </div>

                <!-- أزرار الإجراء -->
                <div class="flex items-center justify-end gap-2.5 mt-6 pt-4 border-t border-gray-100 dark:border-white-dark/10">
                    <button type="button" @click="if(!isArchiving) isArchiveModalOpen = false" class="btn btn-outline-secondary btn-sm" :disabled="isArchiving">
                        إلغاء
                    </button>
                    <button type="button" @click="submitArchive()" class="btn btn-warning btn-sm flex items-center gap-2 text-black font-extrabold shadow-md" :disabled="isArchiving">
                        <template x-if="isArchiving">
                            <span class="w-4 h-4 border-2 border-black border-t-transparent rounded-full animate-spin"></span>
                        </template>
                        <span x-text="isArchiving ? 'جاري الأرشفة والتفريغ...' : 'تأكيد الأرشفة والتفريغ الآن'"></span>
                    </button>
                </div>

            </div>
        </div>

    </div>

    <script>
        function dinarCalculator(config) {
            return {
                csrfToken: config.csrfToken,
                sheetTitle: config.initialData.title || 'حاسبة الصرف دينار',
                items: config.initialData.items || [],
                saveStatus: 'saved',
                saveDebounceTimer: null,

                // حالة الأرشفة
                isArchiveModalOpen: false,
                isArchiving: false,
                archiveTitle: '',
                archiveDate: new Date().toLocaleDateString('en-CA'),
                archiveNotes: '',

                initSheet() {
                    this.items.forEach(item => this.recomputeRow(item));
                },

                // ============================================================
                // المعادلات الحسابية الأساسية لكل صف:
                // 1. مبلغ عراقي تلقائي = سعر الصرف
                // 2. مبلغ العمولة = مبلغ عراقي × نسبة العمولة
                // 3. مبلغ الكمرك = مبلغ عراقي × نسبة الكمرك
                // 4. مجموع عراقي = مبلغ عراقي + مبلغ العمولة + مبلغ الكمرك
                // 5. سعر صرف الدولة = مجموع عراقي × سعر صرف البلد (للدرهم والريمبي)
                // ============================================================
                recomputeRow(item) {
                    const rate        = parseFloat(item.exchange_rate) || 0;
                    const countryRate = parseFloat(item.country_rate) || 0;

                    // 1. مبلغ عراقي تلقائي (هو الأساس المعادل لسعر الصرف المدخل)
                    item.iqd_base = Math.round(rate * 100) / 100;

                    // 2. العمولة
                    const commRate = parseFloat(item.commission_rate) || 0;
                    const commFactor = (item.commission_type === 'per_thousand')
                        ? commRate / 1000 : commRate / 100;
                    item.commission_iqd = Math.round(item.iqd_base * commFactor * 100) / 100;

                    // 3. الكمرك والضريبة
                    const custRate = parseFloat(item.customs_rate) || 0;
                    const custFactor = (item.customs_type === 'per_thousand')
                        ? custRate / 1000 : custRate / 100;
                    item.customs_iqd = Math.round(item.iqd_base * custFactor * 100) / 100;

                    // 4. مجموع عراقي = مبلغ عراقي تلقائي + مبلغ العمولة + مبلغ الكمرك
                    item.total_iqd = Math.round((item.iqd_base + item.commission_iqd + item.customs_iqd) * 100) / 100;

                    // 5. سعر صرف الدولة (المجموع عراقي × سعر البلد)
                    if (item.currency === 'USD') {
                        item.country_final_rate = item.total_iqd;
                    } else {
                        if (countryRate > 0) {
                            item.country_final_rate = Math.round((item.total_iqd * countryRate) * 10000) / 10000;
                        } else {
                            item.country_final_rate = 0;
                        }
                    }
                },

                getPrintCurrencyLabel(currency) {
                    return { USD: 'دولار ($)', AED: 'درهم (AED)', RMB: 'ريمبي (¥)' }[currency] || currency;
                },

                // الحركات الفعلية للطباعة والتصدير
                get printableItems() {
                    const f = this.items.filter(i => parseFloat(i.exchange_rate) > 0);
                    return f.length > 0 ? f : this.items;
                },

                // ===== المجاميع =====
                get totalIqdBase() {
                    return this.items.reduce((s, i) => s + (parseFloat(i.iqd_base) || 0), 0);
                },
                get totalCommissionIqd() {
                    return this.items.reduce((s, i) => s + (parseFloat(i.commission_iqd) || 0), 0);
                },
                get totalCustomsIqd() {
                    return this.items.reduce((s, i) => s + (parseFloat(i.customs_iqd) || 0), 0);
                },
                get grandTotalIqd() {
                    return this.items.reduce((s, i) => s + (parseFloat(i.total_iqd) || 0), 0);
                },
                get totalCountryFinalRate() {
                    return this.items.reduce((s, i) => s + (parseFloat(i.country_final_rate) || 0), 0);
                },

                // ===== أحداث التغيير =====
                onRowChanged(item) {
                    this.recomputeRow(item);
                    this.triggerAutoSave();
                },
                onCurrencyChanged(item) {
                    if (item.currency === 'USD') {
                        item.country_rate = '';
                    }
                    this.recomputeRow(item);
                    this.triggerAutoSave();
                },
                onCellInput(item) { this.triggerAutoSave(); },
                onSheetChanged() { this.triggerAutoSave(); },

                // ===== Auto-Save =====
                triggerAutoSave() {
                    this.saveStatus = 'saving';
                    if (this.saveDebounceTimer) clearTimeout(this.saveDebounceTimer);
                    this.saveDebounceTimer = setTimeout(() => this.executeSave(), 500);
                },

                async executeSave() {
                    try {
                        const payload = { title: this.sheetTitle, items: this.items };
                        localStorage.setItem('dinar_calc_sheet_v3', JSON.stringify(payload));
                        await fetch('{{ url("/dinar-calculator/save") }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrfToken, 'Accept': 'application/json' },
                            body: JSON.stringify(payload)
                        });
                        this.saveStatus = 'saved';
                    } catch (e) {
                        this.saveStatus = 'saved';
                    }
                },

                // ===== إدارة الأسطر =====
                appendNewRow() {
                    this.items.push({
                        id: Date.now(),
                        currency: 'USD', exchange_rate: '',
                        iqd_base: 0, commission_rate: 1, commission_type: 'per_thousand',
                        commission_iqd: 0, customs_rate: 0, customs_type: 'percent',
                        customs_iqd: 0, total_iqd: 0, country_rate: '',
                        country_op: 'mul', country_final_rate: 0
                    });
                    this.triggerAutoSave();
                },

                onEnterKey(index) {
                    if (index === this.items.length - 1) this.appendNewRow();
                },

                removeRow(index) {
                    this.items.splice(index, 1);
                    if (this.items.length === 0) this.appendNewRow();
                    this.triggerAutoSave();
                },

                clearSheetPrompt() {
                    if (confirm('هل أنت متأكد من رغبتك بتفريغ الجدول؟')) {
                        this.items = [];
                        for (let i = 0; i < 5; i++) {
                            this.items.push({
                                id: i + 1, currency: 'USD',
                                exchange_rate: '',
                                iqd_base: 0, commission_rate: 1, commission_type: 'per_thousand',
                                commission_iqd: 0, customs_rate: 0, customs_type: 'percent',
                                customs_iqd: 0, total_iqd: 0, country_rate: '',
                                country_op: 'mul', country_final_rate: 0
                            });
                        }
                        this.triggerAutoSave();
                    }
                },

                // ========================================================
                // 📦 أرشفة الكشف الحالي وتفريغ الجدول
                // ========================================================
                openArchiveModal() {
                    const valid = this.printableItems;
                    if (!valid.length) {
                        alert('لا توجد بيانات صالحة للأرشفة في الجدول.');
                        return;
                    }
                    this.archiveTitle = (this.sheetTitle || 'حاسبة الصرف دينار') + ' - ' + new Date().toLocaleDateString('en-CA');
                    this.archiveDate = new Date().toLocaleDateString('en-CA');
                    this.archiveNotes = '';
                    this.isArchiveModalOpen = true;
                },

                async submitArchive() {
                    const valid = this.printableItems;
                    if (!valid.length) {
                        alert('لا توجد بيانات صالحة للأرشفة.');
                        return;
                    }
                    if (!this.archiveTitle.trim()) {
                        alert('يرجى كتابة عنوان للكشف.');
                        return;
                    }

                    this.isArchiving = true;

                    const payload = {
                        title: this.archiveTitle.trim(),
                        archive_date: this.archiveDate,
                        notes: this.archiveNotes,
                        items: this.items,
                        totals: {
                            total_iqd_base: this.totalIqdBase,
                            total_commission_iqd: this.totalCommissionIqd,
                            total_customs_iqd: this.totalCustomsIqd,
                            grand_total_iqd: this.grandTotalIqd,
                            total_country_final_rate: this.totalCountryFinalRate,
                        }
                    };

                    try {
                        const res = await fetch('{{ route('dinar-calculator.archive') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': this.csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(payload)
                        });

                        const data = await res.json();
                        if (data.success) {
                            this.isArchiveModalOpen = false;
                            this.isArchiving = false;
                            // انتقال مباشر لصفحة الكشف المؤرشف الثابت
                            if (data.show_url) {
                                window.location.href = data.show_url;
                            } else {
                                window.location.href = '{{ route('calculator-archives.index', ['type' => 'dinar']) }}';
                            }
                        } else {
                            alert(data.message || 'تعذرت أرشفة الكشف.');
                            this.isArchiving = false;
                        }
                    } catch (err) {
                        console.error('Archive error:', err);
                        alert('حدث خطأ في الاتصال بالخادم أثناء الأرشفة.');
                        this.isArchiving = false;
                    }
                },

                // ===== تصدير Excel (.xlsx) =====
                exportToExcel() {
                    const rows = this.printableItems;
                    if (!rows.length) { alert('لا توجد بيانات.'); return; }

                    const title = this.sheetTitle || 'حاسبة الصرف دينار';
                    const dateStr = new Date().toLocaleDateString('en-CA');

                    if (typeof XLSX !== 'undefined') {
                        const aoa = [
                            ['مجموعة الزبيدي للتجارة والشحن الدولي'],
                            [title + ' — تاريخ التصدير: ' + dateStr],
                            [],
                            ['إجمالي مبالغ عراقي', 'إجمالي العمولات', 'إجمالي الكمرك والضريبة', 'إجمالي المجموع عراقي', 'إجمالي سعر صرف الدولة'],
                            [this.totalIqdBase, this.totalCommissionIqd, this.totalCustomsIqd, this.grandTotalIqd, this.totalCountryFinalRate],
                            [],
                            ['#', 'العملة', 'سعر الصرف', 'مبلغ عراقي تلقائي', 'العمولة', 'مبلغ العمولة', 'كمرك ضريبة', 'مبلغ الكمرك', 'مجموع عراقي', 'سعر صرف البلد', 'سعر صرف الدولة']
                        ];

                        rows.forEach((item, idx) => {
                            let currName = { USD: 'دولار أمريكي ($)', AED: 'درهم إماراتي (AED)', RMB: 'ريمبي صيني (¥)' }[item.currency] || item.currency;
                            const commTxt = item.commission_type === 'per_thousand' ? (item.commission_rate || 0) + '‰' : (item.commission_rate || 0) + '%';
                            const custTxt = item.customs_type === 'per_thousand' ? (item.customs_rate || 0) + '‰' : (item.customs_rate || 0) + '%';
                            const cRate = (item.currency === 'USD') ? '—' : (parseFloat(item.country_rate) || '');
                            const finalCountry = (item.currency === 'USD') ? parseFloat(item.total_iqd) || 0 : parseFloat(item.country_final_rate) || 0;

                            aoa.push([
                                idx + 1, currName,
                                parseFloat(item.exchange_rate) || 0,
                                parseFloat(item.iqd_base) || 0,
                                commTxt, parseFloat(item.commission_iqd) || 0,
                                custTxt, parseFloat(item.customs_iqd) || 0,
                                parseFloat(item.total_iqd) || 0,
                                cRate,
                                finalCountry
                            ]);
                        });

                        aoa.push([]);
                        aoa.push(['المجاميع', '', '', this.totalIqdBase, '', this.totalCommissionIqd, '', this.totalCustomsIqd, this.grandTotalIqd, '', this.totalCountryFinalRate]);

                        const ws = XLSX.utils.aoa_to_sheet(aoa);
                        ws['!cols'] = [{wch:5},{wch:18},{wch:14},{wch:16},{wch:12},{wch:16},{wch:14},{wch:16},{wch:18},{wch:16},{wch:18}];
                        ws['!views'] = [{ RTL: true }];
                        const wb = XLSX.utils.book_new();
                        XLSX.utils.book_append_sheet(wb, ws, 'حاسبة الصرف دينار');
                        XLSX.writeFile(wb, title.replace(/[\/\\?%*:|"<>]/g, '_') + '_' + dateStr + '.xlsx');
                    } else {
                        alert('مكتبة تصدير Excel غير محملة. تحقق من اتصال الإنترنت.');
                    }
                },

                formatNumber(val, decimals = 2) {
                    const num = Number(val);
                    if (isNaN(num)) return '0';
                    return num.toLocaleString('en-US', { minimumFractionDigits: decimals, maximumFractionDigits: decimals });
                }
            };
        }
    </script>
</x-layout.custom-default>
