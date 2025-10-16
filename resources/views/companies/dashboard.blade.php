<x-layout.company :company="$company">
    <script defer src="/assets/js/apexcharts.js"></script>

    <div x-data="companyDashboard">
        <ul class="flex space-x-2 rtl:space-x-reverse mb-5">
            <li>
                <a href="{{ route('companies.index') }}" class="text-primary hover:underline">الشركات</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>{{ $company->name }}</span>
            </li>
        </ul>

        <!-- الصف الأول: الإحصائيات الرئيسية -->
        <div class="grid xl:grid-cols-4 gap-6 mb-6">
            <!-- إجمالي الرصيد -->
            <div class="panel h-full">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">إجمالي الرصيد</h5>
                </div>
                <div class="flex items-center">
                    <div class="w-16 h-16 text-primary bg-primary-light dark:bg-primary dark:text-primary-light rounded-full grid place-content-center">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 6V18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M15 9.5C15 8.11929 13.6569 7 12 7C10.3431 7 9 8.11929 9 9.5C9 10.8807 10.3431 12 12 12C13.6569 12 15 13.1193 15 14.5C15 15.8807 13.6569 17 12 17C10.3431 17 9 15.8807 9 14.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="ltr:ml-4 rtl:mr-4">
                        <p class="text-xl font-bold text-primary">{{ number_format($totalBalance, 0) }}</p>
                        <p class="text-xs text-white-dark">دينار عراقي</p>
                    </div>
                </div>
            </div>

            <!-- الفواتير -->
            <div class="panel h-full">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">الفواتير</h5>
                </div>
                <div class="flex items-center">
                    <div class="w-16 h-16 text-success bg-success-light dark:bg-success dark:text-success-light rounded-full grid place-content-center">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3.74157 18.5545C4.94119 20 7.17389 20 11.6393 20H12.3605C16.8259 20 19.0586 20 20.2582 18.5545M3.74157 18.5545C2.54194 17.1091 2.9534 14.9146 3.77633 10.5257C4.36155 7.40452 4.65416 5.84393 5.76506 4.92196M3.74157 18.5545C3.74156 18.5545 3.74157 18.5545 3.74157 18.5545ZM20.2582 18.5545C21.4578 17.1091 21.0464 14.9146 20.2235 10.5257C19.6382 7.40452 19.3456 5.84393 18.2347 4.92196M20.2582 18.5545C20.2582 18.5545 20.2582 18.5545 20.2582 18.5545ZM18.2347 4.92196C17.1238 4 15.5361 4 12.3605 4H11.6393C8.46374 4 6.87596 4 5.76506 4.92196M18.2347 4.92196C18.2347 4.92196 18.2347 4.92196 18.2347 4.92196ZM5.76506 4.92196C5.76506 4.92196 5.76506 4.92196 5.76506 4.92196Z" stroke="currentColor" stroke-width="1.5"/>
                        </svg>
                    </div>
                    <div class="ltr:ml-4 rtl:mr-4">
                        <p class="text-xl font-bold">{{ $totalInvoices }}</p>
                        <p class="text-xs text-white-dark">{{ $paidInvoices }} مدفوعة</p>
                    </div>
                </div>
            </div>

            <!-- الشحنات -->
            <div class="panel h-full">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">الشحنات</h5>
                </div>
                <div class="flex items-center">
                    <div class="w-16 h-16 text-warning bg-warning-light dark:bg-warning dark:text-warning-light rounded-full grid place-content-center">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20.5 7.27783L12 12.0001M12 12.0001L3.49997 7.27783M12 12.0001L12 21.5001" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            <path opacity="0.5" d="M21 16.0586V7.94153C21 7.59889 21 7.42757 20.9495 7.27477C20.9049 7.13959 20.8318 7.01551 20.7354 6.91082C20.6263 6.79248 20.4766 6.70928 20.177 6.54288L12.777 2.43177C12.4934 2.27421 12.3516 2.19543 12.2015 2.16454C12.0685 2.13721 11.9315 2.13721 11.7986 2.16454C11.6484 2.19543 11.5066 2.27421 11.223 2.43177L3.82297 6.54288C3.52345 6.70928 3.37369 6.79248 3.26463 6.91082C3.16816 7.01551 3.09515 7.13959 3.05048 7.27477C3 7.42757 3 7.59889 3 7.94153V16.0586C3 16.4012 3 16.5725 3.05048 16.7253C3.09515 16.8605 3.16816 16.9846 3.26463 17.0893C3.37369 17.2076 3.52345 17.2908 3.82297 17.4572L11.223 21.5683C11.5066 21.7259 11.6484 21.8047 11.7986 21.8356C11.9315 21.8629 12.0685 21.8629 12.2015 21.8356C12.3516 21.8047 12.4934 21.7259 12.777 21.5683L20.177 17.4572C20.4766 17.2908 20.6263 17.2076 20.7354 17.0893C20.8318 16.9846 20.9049 16.8605 20.9495 16.7253C21 16.5725 21 16.4012 21 16.0586Z" stroke="currentColor" stroke-width="1.5"/>
                        </svg>
                    </div>
                    <div class="ltr:ml-4 rtl:mr-4">
                        <p class="text-xl font-bold">{{ $totalShipments }}</p>
                        <p class="text-xs text-white-dark">{{ $shippedCount }} مشحونة</p>
                    </div>
                </div>
            </div>

            <!-- الإشعارات -->
            <div class="panel h-full">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">الإشعارات</h5>
                </div>
                <div class="flex items-center">
                    <div class="w-16 h-16 text-danger bg-danger-light dark:bg-danger dark:text-danger-light rounded-full grid place-content-center">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18.7491 9.70957V9.00497C18.7491 5.13623 15.7274 2 12 2C8.27256 2 5.25087 5.13623 5.25087 9.00497V9.70957C5.25087 10.5552 5.00972 11.3818 4.5578 12.0854L3.45036 13.8095C2.43882 15.3843 3.21105 17.5249 4.97036 18.0229C9.57274 19.3257 14.4273 19.3257 19.0296 18.0229C20.789 17.5249 21.5612 15.3843 20.5496 13.8095L19.4422 12.0854C18.9903 11.3818 18.7491 10.5552 18.7491 9.70957Z" stroke="currentColor" stroke-width="1.5"/>
                            <path opacity="0.5" d="M7.5 19C8.15503 20.7478 9.92246 22 12 22C14.0775 22 15.845 20.7478 16.5 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="ltr:ml-4 rtl:mr-4">
                        <p class="text-xl font-bold text-danger">{{ $unreadNotificationsCount }}</p>
                        <p class="text-xs text-white-dark">إشعار جديد</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- الصف الثاني: Charts -->
        <div class="grid xl:grid-cols-3 gap-6 mb-6">
            <!-- Chart: حالات الشحنات -->
            <div class="panel h-full">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">حالات الشحنات</h5>
                </div>
                <div class="overflow-hidden">
                    <div x-ref="shipmentStatusChart" class="bg-white dark:bg-black rounded-lg">
                        <div class="min-h-[325px] grid place-content-center bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08]">
                            <span class="animate-spin border-2 border-black dark:border-white !border-l-transparent rounded-full w-5 h-5 inline-flex"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart: توزيع المصارف -->
            <div class="panel h-full">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">توزيع المصارف</h5>
                </div>
                <div class="overflow-hidden">
                    <div x-ref="banksChart" class="bg-white dark:bg-black rounded-lg">
                        <div class="min-h-[325px] grid place-content-center bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08]">
                            <span class="animate-spin border-2 border-black dark:border-white !border-l-transparent rounded-full w-5 h-5 inline-flex"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart: الفواتير -->
            <div class="panel h-full">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">حالة الفواتير</h5>
                </div>
                <div class="overflow-hidden">
                    <div x-ref="invoicesChart" class="bg-white dark:bg-black rounded-lg">
                        <div class="min-h-[325px] grid place-content-center bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08]">
                            <span class="animate-spin border-2 border-black dark:border-white !border-l-transparent rounded-full w-5 h-5 inline-flex"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- الصف الثالث: الأنشطة والإشعارات -->
        <div class="grid lg:grid-cols-2 gap-6 mb-6">
            <!-- آخر الإشعارات -->
            <div class="panel h-full">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">آخر الإشعارات</h5>
                    <a href="{{ route('notifications.index') }}" class="text-primary hover:underline text-sm">عرض الكل</a>
                </div>
                <div class="space-y-4">
                    @forelse($recentNotifications->take(5) as $notification)
                        <div class="flex items-start gap-3 p-3 rounded-lg {{ $notification->read_at ? 'bg-white-light/50 dark:bg-[#1b2e4b]/50' : 'bg-primary-light dark:bg-primary/20' }}">
                            <div class="w-10 h-10 rounded-full {{ $notification->read_at ? 'bg-white-dark/20' : 'bg-primary' }} grid place-content-center flex-shrink-0">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="{{ $notification->read_at ? 'text-white-dark' : 'text-white' }}">
                                    <path d="M18.7491 9.70957V9.00497C18.7491 5.13623 15.7274 2 12 2C8.27256 2 5.25087 5.13623 5.25087 9.00497V9.70957C5.25087 10.5552 5.00972 11.3818 4.5578 12.0854L3.45036 13.8095C2.43882 15.3843 3.21105 17.5249 4.97036 18.0229C9.57274 19.3257 14.4273 19.3257 19.0296 18.0229C20.789 17.5249 21.5612 15.3843 20.5496 13.8095L19.4422 12.0854C18.9903 11.3818 18.7491 10.5552 18.7491 9.70957Z" stroke="currentColor" stroke-width="1.5"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm {{ $notification->read_at ? 'text-white-dark' : 'font-semibold' }}">{{ $notification->message }}</p>
                                <p class="text-xs text-white-dark mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-white-dark">
                            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="mx-auto mb-3 opacity-30">
                                <path d="M18.7491 9.70957V9.00497C18.7491 5.13623 15.7274 2 12 2C8.27256 2 5.25087 5.13623 5.25087 9.00497V9.70957C5.25087 10.5552 5.00972 11.3818 4.5578 12.0854L3.45036 13.8095C2.43882 15.3843 3.21105 17.5249 4.97036 18.0229C9.57274 19.3257 14.4273 19.3257 19.0296 18.0229C20.789 17.5249 21.5612 15.3843 20.5496 13.8095L19.4422 12.0854C18.9903 11.3818 18.7491 10.5552 18.7491 9.70957Z" stroke="currentColor" stroke-width="1.5"/>
                            </svg>
                            <p>لا توجد إشعارات</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- الإجراءات السريعة -->
            <div class="panel h-full">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">إجراءات سريعة</h5>
                </div>
                <div class="space-y-3">
                    <a href="{{ route('companies.banks.create', $company) }}" class="flex items-center gap-3 p-4 rounded-lg bg-primary-light dark:bg-primary/20 hover:bg-primary hover:text-white transition-all group">
                        <div class="w-10 h-10 rounded-full bg-primary text-white grid place-content-center">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 4V20M20 12H4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h6 class="font-semibold">إضافة مصرف جديد</h6>
                            <p class="text-xs opacity-70">إنشاء حساب مصرفي جديد</p>
                        </div>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="opacity-50 group-hover:opacity-100">
                            <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>

                    <a href="{{ route('companies.invoices.index', $company) }}?open_modal=true" class="flex items-center gap-3 p-4 rounded-lg bg-success-light dark:bg-success/20 hover:bg-success hover:text-white transition-all group">
                        <div class="w-10 h-10 rounded-full bg-success text-white grid place-content-center">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 4V20M20 12H4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h6 class="font-semibold">إنشاء فاتورة جديدة</h6>
                            <p class="text-xs opacity-70">إضافة فاتورة للشركة</p>
                        </div>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="opacity-50 group-hover:opacity-100">
                            <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>

                    <a href="{{ route('companies.shipments.create', $company) }}" class="flex items-center gap-3 p-4 rounded-lg bg-warning-light dark:bg-warning/20 hover:bg-warning hover:text-white transition-all group">
                        <div class="w-10 h-10 rounded-full bg-warning text-white grid place-content-center">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 4V20M20 12H4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h6 class="font-semibold">إضافة شحنة جديدة</h6>
                            <p class="text-xs opacity-70">تسجيل شحنة جديدة</p>
                        </div>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="opacity-50 group-hover:opacity-100">
                            <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>

                    <a href="{{ route('companies.shipments.quick-update', $company) }}" class="flex items-center gap-3 p-4 rounded-lg bg-info-light dark:bg-info/20 hover:bg-info hover:text-white transition-all group">
                        <div class="w-10 h-10 rounded-full bg-info text-white grid place-content-center">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" fill="currentColor"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h6 class="font-semibold">التحديث السريع ⚡</h6>
                            <p class="text-xs opacity-70">تحديث حالات الشحنات</p>
                        </div>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="opacity-50 group-hover:opacity-100">
                            <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('companyDashboard', () => ({
                init() {
                    this.initCharts();
                },

                initCharts() {
                    // انتظر تحميل ApexCharts
                    if (typeof ApexCharts === 'undefined') {
                        setTimeout(() => this.initCharts(), 100);
                        return;
                    }

                    // Chart 1: حالات الشحنات (Donut)
                    const shipmentData = {
                        shipped: {{ $shippedCount }},
                        notShipped: {{ $notShippedCount }},
                        received: {{ $company->shipments->where('received_status', 'received')->count() }},
                        notReceived: {{ $company->shipments->where('received_status', 'not_received')->count() }},
                        entered: {{ $company->shipments->where('entry_status', 'entered')->count() }},
                        notEntered: {{ $company->shipments->where('entry_status', 'not_entered')->count() }},
                    };

                    const shipmentStatusChart = {
                        series: [
                            shipmentData.shipped,
                            shipmentData.notShipped,
                            shipmentData.received,
                            shipmentData.entered
                        ],
                        chart: {
                            type: 'donut',
                            height: 325,
                            fontFamily: 'Nunito, sans-serif',
                        },
                        labels: ['مشحون', 'غير مشحون', 'مستلمة', 'داخلة'],
                        colors: ['#00ab55', '#e7515a', '#4361ee', '#805dca'],
                        legend: {
                            position: 'bottom',
                        },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '65%',
                                    labels: {
                                        show: true,
                                        name: {
                                            fontSize: '16px',
                                        },
                                        value: {
                                            fontSize: '24px',
                                            fontWeight: 600,
                                        },
                                        total: {
                                            show: true,
                                            label: 'الإجمالي',
                                            formatter: function (w) {
                                                return {{ $totalShipments }};
                                            },
                                        },
                                    },
                                },
                            },
                        },
                    };

                    // إخفاء الـ loader ورسم الـ chart
                    this.$refs.shipmentStatusChart.innerHTML = '';
                    new ApexCharts(this.$refs.shipmentStatusChart, shipmentStatusChart).render();

                    // Chart 2: توزيع المصارف (Bar)
                    const banksData = @json($company->banks->map(function($bank) {
                        return [
                            'name' => $bank->name,
                            'balance' => $bank->current_balance
                        ];
                    }));

                    const banksChart = {
                        series: [{
                            name: 'الرصيد',
                            data: banksData.map(b => b.balance)
                        }],
                        chart: {
                            type: 'bar',
                            height: 325,
                            fontFamily: 'Nunito, sans-serif',
                            toolbar: {
                                show: false,
                            },
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: '55%',
                                borderRadius: 8,
                            },
                        },
                        dataLabels: {
                            enabled: false,
                        },
                        colors: ['#4361ee'],
                        xaxis: {
                            categories: banksData.map(b => b.name),
                        },
                        yaxis: {
                            labels: {
                                formatter: function (val) {
                                    return val.toLocaleString();
                                },
                            },
                        },
                        grid: {
                            borderColor: '#e0e6ed',
                        },
                        tooltip: {
                            y: {
                                formatter: function (val) {
                                    return val.toLocaleString() + ' دينار';
                                },
                            },
                        },
                    };

                    // إخفاء الـ loader ورسم الـ chart
                    this.$refs.banksChart.innerHTML = '';
                    new ApexCharts(this.$refs.banksChart, banksChart).render();

                    // Chart 3: الفواتير (Pie)
                    const invoicesChart = {
                        series: [{{ $paidInvoices }}, {{ $unpaidInvoices }}],
                        chart: {
                            type: 'pie',
                            height: 325,
                            fontFamily: 'Nunito, sans-serif',
                        },
                        labels: ['مدفوعة', 'غير مدفوعة'],
                        colors: ['#00ab55', '#e7515a'],
                        legend: {
                            position: 'bottom',
                        },
                        responsive: [{
                            breakpoint: 480,
                            options: {
                                chart: {
                                    width: 300
                                },
                            }
                        }]
                    };

                    // إخفاء الـ loader ورسم الـ chart
                    this.$refs.invoicesChart.innerHTML = '';
                    new ApexCharts(this.$refs.invoicesChart, invoicesChart).render();
                }
            }));
        });
    </script>
</x-layout.company>
