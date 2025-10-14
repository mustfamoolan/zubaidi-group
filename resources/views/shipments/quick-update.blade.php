<x-layout.company :company="$company">
    <div x-data="quickUpdate">
        <ul class="flex space-x-2 rtl:space-x-reverse mb-5">
            <li>
                <a href="{{ route('companies.show', $company) }}" class="text-primary hover:underline">{{ $company->name }}</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>التحديث السريع للشحنات</span>
            </li>
        </ul>

        <div class="panel">
            <div class="flex items-center justify-between mb-5">
                <h5 class="font-semibold text-lg dark:text-white-light">التحديث السريع لحالات الشحنات</h5>
                <span class="badge bg-primary">{{ $shipments->count() }} شحنة</span>
            </div>

            <!-- رسالة النجاح -->
            <div x-show="successMessage"
                 x-transition
                 class="alert alert-success mb-4"
                 x-text="successMessage">
            </div>

            <!-- Tabs -->
            <div class="mb-5">
                <ul class="flex flex-wrap border-b border-white-light dark:border-[#191e3a]">
                    <li class="tab">
                        <a href="javascript:;"
                           class="p-5 py-3 -mb-[1px] flex items-center relative before:transition-all before:duration-700 hover:text-secondary before:absolute before:w-0 before:bottom-0 before:left-0 before:right-0 before:m-auto before:h-[1px] before:bg-secondary hover:before:w-full"
                           :class="{'text-secondary !border-white-light dark:!border-[#191e3a] border-b-[3px] !border-secondary': activeTab === 'shipping'}"
                           @click="activeTab = 'shipping'">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ltr:mr-2 rtl:ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                            حالة الشحن
                        </a>
                    </li>
                    <li class="tab">
                        <a href="javascript:;"
                           class="p-5 py-3 -mb-[1px] flex items-center relative before:transition-all before:duration-700 hover:text-secondary before:absolute before:w-0 before:bottom-0 before:left-0 before:right-0 before:m-auto before:h-[1px] before:bg-secondary hover:before:w-full"
                           :class="{'text-secondary !border-white-light dark:!border-[#191e3a] border-b-[3px] !border-secondary': activeTab === 'received'}"
                           @click="activeTab = 'received'">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ltr:mr-2 rtl:ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            حالة الاستلام
                        </a>
                    </li>
                    <li class="tab">
                        <a href="javascript:;"
                           class="p-5 py-3 -mb-[1px] flex items-center relative before:transition-all before:duration-700 hover:text-secondary before:absolute before:w-0 before:bottom-0 before:left-0 before:right-0 before:m-auto before:h-[1px] before:bg-secondary hover:before:w-full"
                           :class="{'text-secondary !border-white-light dark:!border-[#191e3a] border-b-[3px] !border-secondary': activeTab === 'entry'}"
                           @click="activeTab = 'entry'">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ltr:mr-2 rtl:ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            حالة الدخول
                        </a>
                    </li>
                    <li class="tab">
                        <a href="javascript:;"
                           class="p-5 py-3 -mb-[1px] flex items-center relative before:transition-all before:duration-700 hover:text-secondary before:absolute before:w-0 before:bottom-0 before:left-0 before:right-0 before:m-auto before:h-[1px] before:bg-secondary hover:before:w-full"
                           :class="{'text-secondary !border-white-light dark:!border-[#191e3a] border-b-[3px] !border-secondary': activeTab === 'permit'}"
                           @click="activeTab = 'permit'">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ltr:mr-2 rtl:ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            تصريح الدخول
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Tab Content: حالة الشحن -->
            <div x-show="activeTab === 'shipping'" x-transition>
                <div class="table-responsive">
                    <table class="table-hover">
                        <thead>
                            <tr>
                                <th>رقم الحاوية</th>
                                <th>رقم البوليصة</th>
                                <th>تاريخ الشحن</th>
                                <th>الحالة الحالية</th>
                                <th class="text-center">تحديث الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($shipments as $shipment)
                                <tr>
                                    <td>
                                        <a href="{{ route('companies.shipments.show', [$company, $shipment]) }}" class="text-primary hover:underline font-semibold">
                                            {{ $shipment->container_number }}
                                        </a>
                                    </td>
                                    <td>{{ $shipment->policy_number }}</td>
                                    <td>{{ $shipment->shipping_date->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge {{ $shipment->status === 'shipped' ? 'badge-outline-success' : 'badge-outline-warning' }}">
                                            {{ $shipment->status === 'shipped' ? 'مشحون' : 'غير مشحون' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="flex gap-2 justify-center">
                                            <button type="button"
                                                    class="btn btn-sm {{ $shipment->status === 'shipped' ? 'btn-success' : 'btn-outline-success' }}"
                                                    @click="updateStatus({{ $shipment->id }}, 'shipping', 'shipped')"
                                                    :disabled="loading">
                                                ✓ مشحون
                                            </button>
                                            <button type="button"
                                                    class="btn btn-sm {{ $shipment->status === 'not_shipped' ? 'btn-warning' : 'btn-outline-warning' }}"
                                                    @click="updateStatus({{ $shipment->id }}, 'shipping', 'not_shipped')"
                                                    :disabled="loading">
                                                ✗ غير مشحون
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-8">لا توجد شحنات</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab Content: حالة الاستلام -->
            <div x-show="activeTab === 'received'" x-transition>
                <div class="table-responsive">
                    <table class="table-hover">
                        <thead>
                            <tr>
                                <th>رقم الحاوية</th>
                                <th>رقم البوليصة</th>
                                <th>تاريخ الشحن</th>
                                <th>الحالة الحالية</th>
                                <th>آخر تحديث</th>
                                <th class="text-center">تحديث الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($shipments as $shipment)
                                <tr>
                                    <td>
                                        <a href="{{ route('companies.shipments.show', [$company, $shipment]) }}" class="text-primary hover:underline font-semibold">
                                            {{ $shipment->container_number }}
                                        </a>
                                    </td>
                                    <td>{{ $shipment->policy_number }}</td>
                                    <td>{{ $shipment->shipping_date->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge {{ $shipment->received_status === 'received' ? 'badge-outline-success' : 'badge-outline-danger' }}">
                                            {{ $shipment->received_status === 'received' ? 'مستلمة' : 'غير مستلمة' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($shipment->received_date)
                                            <span class="text-xs">{{ $shipment->received_date->format('d M Y') }}</span>
                                        @else
                                            <span class="text-xs text-white-dark">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="flex gap-2 justify-center">
                                            <button type="button"
                                                    class="btn btn-sm {{ $shipment->received_status === 'received' ? 'btn-success' : 'btn-outline-success' }}"
                                                    @click="updateStatus({{ $shipment->id }}, 'received', 'received')"
                                                    :disabled="loading">
                                                ✓ مستلمة
                                            </button>
                                            <button type="button"
                                                    class="btn btn-sm {{ $shipment->received_status === 'not_received' ? 'btn-danger' : 'btn-outline-danger' }}"
                                                    @click="updateStatus({{ $shipment->id }}, 'received', 'not_received')"
                                                    :disabled="loading">
                                                ✗ غير مستلمة
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-8">لا توجد شحنات</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab Content: حالة الدخول -->
            <div x-show="activeTab === 'entry'" x-transition>
                <div class="table-responsive">
                    <table class="table-hover">
                        <thead>
                            <tr>
                                <th>رقم الحاوية</th>
                                <th>رقم البوليصة</th>
                                <th>تاريخ الشحن</th>
                                <th>الحالة الحالية</th>
                                <th>آخر تحديث</th>
                                <th class="text-center">تحديث الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($shipments as $shipment)
                                <tr>
                                    <td>
                                        <a href="{{ route('companies.shipments.show', [$company, $shipment]) }}" class="text-primary hover:underline font-semibold">
                                            {{ $shipment->container_number }}
                                        </a>
                                    </td>
                                    <td>{{ $shipment->policy_number }}</td>
                                    <td>{{ $shipment->shipping_date->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge {{ $shipment->entry_status === 'entered' ? 'badge-outline-success' : 'badge-outline-danger' }}">
                                            {{ $shipment->entry_status === 'entered' ? 'داخلة' : 'غير داخلة' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($shipment->entry_date)
                                            <span class="text-xs">{{ $shipment->entry_date->format('d M Y') }}</span>
                                        @else
                                            <span class="text-xs text-white-dark">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="flex gap-2 justify-center">
                                            <button type="button"
                                                    class="btn btn-sm {{ $shipment->entry_status === 'entered' ? 'btn-success' : 'btn-outline-success' }}"
                                                    @click="updateStatus({{ $shipment->id }}, 'entry', 'entered')"
                                                    :disabled="loading">
                                                ✓ داخلة
                                            </button>
                                            <button type="button"
                                                    class="btn btn-sm {{ $shipment->entry_status === 'not_entered' ? 'btn-danger' : 'btn-outline-danger' }}"
                                                    @click="updateStatus({{ $shipment->id }}, 'entry', 'not_entered')"
                                                    :disabled="loading">
                                                ✗ غير داخلة
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-8">لا توجد شحنات</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab Content: تصريح الدخول -->
            <div x-show="activeTab === 'permit'" x-transition>
                <div class="table-responsive">
                    <table class="table-hover">
                        <thead>
                            <tr>
                                <th>رقم الحاوية</th>
                                <th>رقم البوليصة</th>
                                <th>تاريخ الشحن</th>
                                <th>الحالة الحالية</th>
                                <th>آخر تحديث</th>
                                <th class="text-center">تحديث الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($shipments as $shipment)
                                <tr>
                                    <td>
                                        <a href="{{ route('companies.shipments.show', [$company, $shipment]) }}" class="text-primary hover:underline font-semibold">
                                            {{ $shipment->container_number }}
                                        </a>
                                    </td>
                                    <td>{{ $shipment->policy_number }}</td>
                                    <td>{{ $shipment->shipping_date->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge {{ $shipment->entry_permit_status === 'received' ? 'badge-outline-success' : 'badge-outline-warning' }}">
                                            {{ $shipment->entry_permit_status === 'received' ? 'مستلم' : 'غير مستلم' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($shipment->entry_permit_date)
                                            <span class="text-xs">{{ $shipment->entry_permit_date->format('d M Y') }}</span>
                                        @else
                                            <span class="text-xs text-white-dark">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="flex gap-2 justify-center">
                                            <button type="button"
                                                    class="btn btn-sm {{ $shipment->entry_permit_status === 'received' ? 'btn-success' : 'btn-outline-success' }}"
                                                    @click="updateStatus({{ $shipment->id }}, 'permit', 'received')"
                                                    :disabled="loading">
                                                ✓ مستلم
                                            </button>
                                            <button type="button"
                                                    class="btn btn-sm {{ $shipment->entry_permit_status === 'not_received' ? 'btn-warning' : 'btn-outline-warning' }}"
                                                    @click="updateStatus({{ $shipment->id }}, 'permit', 'not_received')"
                                                    :disabled="loading">
                                                ✗ غير مستلم
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-8">لا توجد شحنات</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('quickUpdate', () => ({
                activeTab: 'shipping',
                loading: false,
                successMessage: '',

                async updateStatus(shipmentId, type, status) {
                    this.loading = true;
                    this.successMessage = '';

                    const routes = {
                        shipping: '{{ route("companies.shipments.quick-update-shipping", [$company, ":id"]) }}',
                        received: '{{ route("companies.shipments.quick-update-received", [$company, ":id"]) }}',
                        entry: '{{ route("companies.shipments.quick-update-entry", [$company, ":id"]) }}',
                        permit: '{{ route("companies.shipments.quick-update-entry-permit", [$company, ":id"]) }}'
                    };

                    const statusFields = {
                        shipping: 'status',
                        received: 'received_status',
                        entry: 'entry_status',
                        permit: 'entry_permit_status'
                    };

                    const url = routes[type].replace(':id', shipmentId);

                    // الحصول على CSRF token بطريقة آمنة
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (!csrfToken) {
                        console.error('CSRF token not found');
                        alert('خطأ: لم يتم العثور على رمز الأمان. يرجى إعادة تحميل الصفحة.');
                        this.loading = false;
                        return;
                    }

                    try {
                        const response = await fetch(url, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken.content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                [statusFields[type]]: status
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.successMessage = data.message;
                            setTimeout(() => {
                                this.successMessage = '';
                            }, 3000);

                            // إعادة تحميل الصفحة لتحديث البيانات
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            alert(data.message || 'حدث خطأ أثناء التحديث');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('حدث خطأ أثناء التحديث. يرجى المحاولة مرة أخرى.');
                    } finally {
                        this.loading = false;
                    }
                }
            }));
        });
    </script>
</x-layout.company>

