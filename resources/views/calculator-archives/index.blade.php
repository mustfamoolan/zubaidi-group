<x-layout.custom-default>
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse text-sm mb-4">
            <li><a href="{{ url('/') }}" class="text-primary hover:underline">لوحة التحكم</a></li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span class="text-black dark:text-white-light font-medium">أرشيف الكشوفات والحسابات</span>
            </li>
        </ul>

        <!-- Success notification if redirected with session message -->
        @if(session('success'))
            <div class="mb-4 flex items-center p-3.5 rounded text-emerald-700 bg-emerald-100 dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" class="shrink-0 ltr:mr-2 rtl:ml-2" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M8.5 12.5L10.5 14.5L15.5 9.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Header Panel with Tabs -->
        <div class="panel mb-5 p-4 sm:p-5">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-gray-200 dark:border-white-dark/10">
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-primary/10 text-primary rounded-xl">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 7C4 5.89543 4.89543 5 6 5H18C19.1046 5 20 5.89543 20 7V9C20 9.55228 19.5523 10 19 10H5C4.44772 10 4 9.55228 4 9V7Z" stroke="currentColor" stroke-width="1.8"/>
                            <path opacity="0.5" d="M6 10V18C6 19.1046 6.89543 20 8 20H16C17.1046 20 18 19.1046 18 18V10" stroke="currentColor" stroke-width="1.8"/>
                            <path d="M10 14H14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-black text-[#0e1726] dark:text-white-light">
                            أرشيف كشوفات الحسابات وصرف العملات
                        </h1>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            سجل الكشوفات المؤرشفة يومياً - مراجعة كاملة، عرض التفاصيل بنفس التصميم، والطباعة الرسمية A4 وتصدير Excel بأي وقت.
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    @if($type === 'exchange')
                        <a href="{{ url('/exchange-calculator') }}" class="btn btn-primary btn-sm flex items-center gap-1.5 shadow-sm">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 4V20M20 12H4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            <span>الانتقال لحاسبة صرف العملات النشطة</span>
                        </a>
                    @else
                        <a href="{{ url('/dinar-calculator') }}" class="btn btn-primary btn-sm flex items-center gap-1.5 shadow-sm">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 4V20M20 12H4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            <span>الانتقال لحاسبة صرف الدينار النشطة</span>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Tab Switcher -->
            <div class="flex items-center gap-2 pt-4">
                <a href="{{ route('calculator-archives.index', ['type' => 'exchange']) }}"
                   class="px-4 py-2.5 rounded-lg text-sm font-bold flex items-center gap-2 transition {{ $type === 'exchange' ? 'bg-primary text-white shadow-sm' : 'bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="3" y="3" width="18" height="18" rx="3" stroke="currentColor" stroke-width="1.8"/>
                        <path opacity="0.5" d="M3 9H21" stroke="currentColor" stroke-width="1.8"/>
                    </svg>
                    <span>أرشيف حاسبة صرف العملات</span>
                    <span class="badge {{ $type === 'exchange' ? 'bg-white text-primary' : 'bg-gray-300 text-gray-800 dark:bg-gray-700 dark:text-white' }} text-[11px] py-0 px-2 rounded-full font-bold">
                        {{ $exchangeCount }}
                    </span>
                </a>

                <a href="{{ route('calculator-archives.index', ['type' => 'dinar']) }}"
                   class="px-4 py-2.5 rounded-lg text-sm font-bold flex items-center gap-2 transition {{ $type === 'dinar' ? 'bg-primary text-white shadow-sm' : 'bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M12 7V17M9 10H15M9 14H15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                    <span>أرشيف حاسبة صرف الدينار</span>
                    <span class="badge {{ $type === 'dinar' ? 'bg-white text-primary' : 'bg-gray-300 text-gray-800 dark:bg-gray-700 dark:text-white' }} text-[11px] py-0 px-2 rounded-full font-bold">
                        {{ $dinarCount }}
                    </span>
                </a>
            </div>
        </div>

        <!-- Filters Box -->
        <div class="panel mb-5 p-4">
            <form method="GET" action="{{ route('calculator-archives.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 items-end">
                <input type="hidden" name="type" value="{{ $type }}">

                <div>
                    <label class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1 block">البحث بالعنوان أو الملاحظات</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="ابحث باسم الكشف..." class="form-input text-xs">
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1 block">من تاريخ</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-input text-xs">
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1 block">إلى تاريخ</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-input text-xs">
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="btn btn-primary btn-sm flex-1">
                        تصفية
                    </button>
                    @if(request('search') || request('from_date') || request('to_date'))
                        <a href="{{ route('calculator-archives.index', ['type' => $type]) }}" class="btn btn-outline-danger btn-sm">
                            إلغاء
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Archives Table Panel -->
        <div class="panel p-0 border border-gray-300 dark:border-white-dark/20 rounded-xl overflow-hidden shadow-sm">
            @if($archives->count() > 0)
                <div class="table-responsive">
                    <table class="table-striped table-hover w-full text-xs text-right border-collapse">
                        <thead class="bg-[#eaedf1] dark:bg-[#0e1726] border-b border-gray-300 dark:border-white-dark/20 text-gray-800 dark:text-gray-200 font-bold text-[11px]">
                            <tr>
                                <th class="w-12 text-center py-3 px-2">#</th>
                                <th class="w-32 py-3 px-3">تاريخ الأرشفة</th>
                                <th class="min-w-[200px] py-3 px-3">عنوان الكشف المؤرشف</th>
                                <th class="w-24 text-center py-3 px-2">عدد الحركات</th>
                                <th class="min-w-[260px] py-3 px-3">الملخص المالي الرئيسي</th>
                                <th class="w-32 py-3 px-3">أرشف بواسطة</th>
                                <th class="w-32 text-center py-3 px-3">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-white-dark/10 font-medium">
                            @foreach($archives as $index => $archive)
                                <tr class="group hover:bg-primary/5 transition-colors">
                                    <td class="text-center font-bold text-gray-500 py-3 px-2">
                                        {{ $archives->firstItem() + $index }}
                                    </td>

                                    <!-- التاريخ -->
                                    <td class="py-3 px-3 whitespace-nowrap">
                                        <div class="flex items-center gap-1.5 font-bold text-gray-800 dark:text-gray-200">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" class="text-gray-400" xmlns="http://www.w3.org/2000/svg">
                                                <rect x="3" y="4" width="18" height="18" rx="2" stroke="currentColor" stroke-width="1.5"/>
                                                <path opacity="0.5" d="M3 10H21" stroke="currentColor" stroke-width="1.5"/>
                                                <path d="M16 2V6M8 2V6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                            </svg>
                                            <span>{{ $archive->archive_date->format('Y-m-d') }}</span>
                                        </div>
                                    </td>

                                    <!-- العنوان -->
                                    <td class="py-3 px-3">
                                        <a href="{{ route('calculator-archives.show', $archive->id) }}" class="font-bold text-primary hover:underline text-sm block">
                                            {{ $archive->title }}
                                        </a>
                                        @if($archive->notes)
                                            <p class="text-[11px] text-gray-500 truncate max-w-xs mt-0.5">{{ $archive->notes }}</p>
                                        @endif
                                    </td>

                                    <!-- عدد الحركات -->
                                    <td class="py-3 px-2 text-center whitespace-nowrap">
                                        <span class="badge bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-200 font-bold text-xs py-0.5 px-2">
                                            {{ $archive->rows_count }} حركات
                                        </span>
                                    </td>

                                    <!-- الملخص المالي -->
                                    <td class="py-3 px-3">
                                        @php
                                            $totals = $archive->totals ?? [];
                                            $parseNum = function($v) {
                                                return floatval(str_replace(',', '', (string)($v ?? 0)));
                                            };
                                        @endphp

                                        @if($archive->type === 'exchange')
                                            <div class="flex items-center flex-wrap gap-2 text-[11px]" dir="ltr">
                                                @if(!empty($totals['total_usd']))
                                                    <span class="bg-blue-50 dark:bg-blue-950/30 text-blue-700 dark:text-blue-300 px-2 py-0.5 rounded font-bold">
                                                        $ {{ number_format($parseNum($totals['total_usd']), 2) }}
                                                    </span>
                                                @endif
                                                @if(!empty($totals['total_aed']))
                                                    <span class="bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-300 px-2 py-0.5 rounded font-bold">
                                                        AED {{ number_format($parseNum($totals['total_aed']), 2) }}
                                                    </span>
                                                @endif
                                                @if(!empty($totals['total_rmb']))
                                                    <span class="bg-amber-50 dark:bg-amber-950/30 text-amber-700 dark:text-amber-300 px-2 py-0.5 rounded font-bold">
                                                        ¥ {{ number_format($parseNum($totals['total_rmb']), 2) }}
                                                    </span>
                                                @endif
                                                @if(!empty($totals['grand_total_iqd']))
                                                    <span class="bg-purple-100 dark:bg-purple-950/50 text-purple-900 dark:text-purple-200 px-2 py-0.5 rounded font-black text-xs">
                                                        {{ number_format($parseNum($totals['grand_total_iqd']), 0) }} د.ع
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <div class="flex items-center flex-wrap gap-2 text-[11px]" dir="ltr">
                                                @if(!empty($totals['grand_total_iqd']))
                                                    <span class="bg-emerald-100 dark:bg-emerald-950/50 text-emerald-900 dark:text-emerald-200 px-2 py-0.5 rounded font-black text-xs">
                                                        الصافي: {{ number_format($parseNum($totals['grand_total_iqd']), 2) }} د.ع
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    </td>

                                    <!-- المستخدم -->
                                    <td class="py-3 px-3 text-gray-600 dark:text-gray-400 text-xs whitespace-nowrap">
                                        {{ $archive->user?->name ?? 'المستخدم' }}
                                    </td>

                                    <!-- الإجراءات -->
                                    <td class="py-3 px-3 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <a href="{{ route('calculator-archives.show', $archive->id) }}"
                                               class="btn btn-outline-primary btn-sm py-1 px-2.5 flex items-center gap-1 shadow-sm"
                                               title="عرض وطباعة الكشف المؤرشف">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M1 12C1 12 5 4 12 4C19 4 23 12 23 12C23 12 19 20 12 20C5 20 1 12 1 12Z" stroke="currentColor" stroke-width="1.8"/>
                                                    <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.8"/>
                                                </svg>
                                                <span>عرض</span>
                                            </a>

                                            <form action="{{ route('calculator-archives.destroy', $archive->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد تماماً من رغبتك بحذف هذا الكشف المؤرشف نهائياً؟');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm py-1 px-2 text-danger hover:bg-danger hover:text-white" title="حذف الكشف من الأرشيف">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M19 7L18.1327 19.1425C18.0579 20.1891 17.187 21 16.1378 21H7.86224C6.81296 21 5.94208 20.1891 5.86732 19.1425L5 7M10 11V17M14 11V17M15 7V4C15 3.44772 14.5523 3 14 3H10C9.44772 3 9 3.44772 9 4V7M4 7H20" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($archives->hasPages())
                    <div class="p-4 border-t border-gray-200 dark:border-white-dark/10">
                        {{ $archives->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="text-center py-16 px-4">
                    <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-400 flex items-center justify-center mx-auto mb-4">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 7C4 5.89543 4.89543 5 6 5H18C19.1046 5 20 5.89543 20 7V9C20 9.55228 19.5523 10 19 10H5C4.44772 10 4 9.55228 4 9V7Z" stroke="currentColor" stroke-width="1.5"/>
                            <path opacity="0.5" d="M6 10V18C6 19.1046 6.89543 20 8 20H16C17.1046 20 18 19.1046 18 18V10" stroke="currentColor" stroke-width="1.5"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-700 dark:text-gray-300">لا توجد كشوفات مؤرشفة حتى الآن</h3>
                    <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto">
                        عندما تنتهي من إدخال حسابات اليوم في الحاسبة، اضغط على زر <strong>«أرشفة وحفظ الكشف»</strong> لتفريغ الجدول لليوم التالي وحفظ نسخة رسمية هنا بشكل دائم.
                    </p>
                    <div class="mt-4">
                        @if($type === 'exchange')
                            <a href="{{ url('/exchange-calculator') }}" class="btn btn-primary btn-sm">الانتقال لحاسبة صرف العملات</a>
                        @else
                            <a href="{{ url('/dinar-calculator') }}" class="btn btn-primary btn-sm">الانتقال لحاسبة صرف الدينار</a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layout.custom-default>
