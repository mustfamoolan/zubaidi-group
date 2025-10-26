<x-layout.company :company="$company">
    <div class="space-y-6" x-data>
        <!-- الأزرار العلوية -->
        <div class="flex items-center justify-between flex-wrap gap-4">
            <h2 class="text-xl font-semibold">تفاصيل الشحنة: {{ $shipment->container_number }}</h2>
            <div class="flex gap-2">
                <a href="{{ route('companies.shipments.index', $company) }}" class="btn btn-outline-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ltr:mr-2 rtl:ml-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    العودة للقائمة
                </a>
                <a href="{{ route('companies.shipments.edit', [$company, $shipment]) }}" class="btn btn-warning">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ltr:mr-2 rtl:ml-2" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                    تعديل
                </a>
                <form action="{{ route('companies.shipments.destroy', [$company, $shipment]) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه الشحنة؟')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ltr:mr-2 rtl:ml-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        حذف
                    </button>
                </form>
            </div>
        </div>

        <!-- معلومات الشحنة الأساسية -->
        <div class="panel">
            <div class="mb-5">
                <h5 class="font-semibold text-lg dark:text-white-light">المعلومات الأساسية</h5>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="border border-[#e0e6ed] dark:border-[#1b2e4b] rounded p-4">
                    <h6 class="text-[#3b3f5c] dark:text-white-light font-semibold text-sm mb-1">رقم الحاوية</h6>
                    <p class="text-lg font-bold">{{ $shipment->container_number }}</p>
                </div>

                <div class="border border-[#e0e6ed] dark:border-[#1b2e4b] rounded p-4">
                    <h6 class="text-[#3b3f5c] dark:text-white-light font-semibold text-sm mb-1">رقم البوليصة</h6>
                    <p class="text-lg font-bold">{{ $shipment->policy_number }}</p>
                </div>

                <div class="border border-[#e0e6ed] dark:border-[#1b2e4b] rounded p-4">
                    <h6 class="text-[#3b3f5c] dark:text-white-light font-semibold text-sm mb-1">حالة الشحن</h6>
                    <p>
                        @if($shipment->status === 'shipped')
                            <span class="badge bg-success">مشحون</span>
                        @else
                            <span class="badge bg-warning">غير مشحون</span>
                        @endif
                    </p>
                </div>

                <div class="border border-[#e0e6ed] dark:border-[#1b2e4b] rounded p-4">
                    <h6 class="text-[#3b3f5c] dark:text-white-light font-semibold text-sm mb-1">تاريخ الشحن</h6>
                    <p class="text-lg font-bold">{{ $shipment->shipping_date->format('d M Y') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                <div class="border border-[#e0e6ed] dark:border-[#1b2e4b] rounded p-4">
                    <h6 class="text-[#3b3f5c] dark:text-white-light font-semibold text-sm mb-1">الوزن</h6>
                    <p class="text-lg font-bold">{{ $shipment->weight ? number_format($shipment->weight, 2) . ' كغ' : '-' }}</p>
                </div>

                <div class="border border-[#e0e6ed] dark:border-[#1b2e4b] rounded p-4">
                    <h6 class="text-[#3b3f5c] dark:text-white-light font-semibold text-sm mb-1">حجم الحاوية</h6>
                    <p class="text-lg font-bold">{{ $shipment->container_size ?? '-' }}</p>
                </div>

                <div class="border border-[#e0e6ed] dark:border-[#1b2e4b] rounded p-4">
                    <h6 class="text-[#3b3f5c] dark:text-white-light font-semibold text-sm mb-1">عدد الكراتين</h6>
                    <p class="text-lg font-bold">{{ $shipment->carton_count ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- حالات الشحنة -->
        <div class="panel">
            <div class="mb-5">
                <h5 class="font-semibold text-lg dark:text-white-light">حالات الشحنة</h5>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- حالة الاستلام -->
                <div class="border border-[#e0e6ed] dark:border-[#1b2e4b] rounded p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h6 class="font-semibold">حالة الاستلام</h6>
                        @if($shipment->received_status === 'received')
                            <span class="badge bg-success">مستلمة</span>
                        @else
                            <span class="badge bg-danger">غير مستلمة</span>
                        @endif
                    </div>

                    @if($shipment->received_date)
                        <p class="text-sm text-white-dark mb-3">تاريخ التحديث: {{ $shipment->received_date->format('d M Y') }}</p>
                    @endif

                    <button type="button" class="btn btn-sm btn-primary w-full" @click="$dispatch('open-modal', 'received-status-modal')">
                        تحديث حالة الاستلام
                    </button>
                </div>

                <!-- حالة الدخول -->
                <div class="border border-[#e0e6ed] dark:border-[#1b2e4b] rounded p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h6 class="font-semibold">حالة الدخول</h6>
                        @if($shipment->entry_status === 'entered')
                            <span class="badge bg-success">داخلة</span>
                        @else
                            <span class="badge bg-danger">غير داخلة</span>
                        @endif
                    </div>

                    @if($shipment->entry_date)
                        <p class="text-sm text-white-dark mb-3">تاريخ التحديث: {{ $shipment->entry_date->format('d M Y') }}</p>
                    @endif

                    <button type="button" class="btn btn-sm btn-primary w-full" @click="$dispatch('open-modal', 'entry-status-modal')">
                        تحديث حالة الدخول
                    </button>
                </div>

                <!-- حالة أوراق الدخول -->
                <div class="border border-[#e0e6ed] dark:border-[#1b2e4b] rounded p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h6 class="font-semibold">حالة أوراق الدخول</h6>
                        @if($shipment->entry_permit_status === 'received')
                            <span class="badge bg-success">مستلم</span>
                        @else
                            <span class="badge bg-warning">غير مستلم</span>
                        @endif
                    </div>

                    @if($shipment->entry_permit_date)
                        <p class="text-sm text-white-dark mb-3">تاريخ التحديث: {{ $shipment->entry_permit_date->format('d M Y') }}</p>
                    @endif

                    <button type="button" class="btn btn-sm btn-primary w-full" @click="$dispatch('open-modal', 'entry-permit-status-modal')">
                        تحديث حالة أوراق الدخول
                    </button>
                </div>
            </div>
        </div>

        <!-- الملف المرفق -->
        @if($shipment->invoice_file)
        <div class="panel">
            <div class="mb-5">
                <h5 class="font-semibold text-lg dark:text-white-light">الملف المرفق</h5>
            </div>
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-primary" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd" />
                </svg>
                <div class="flex-1">
                    <p class="font-semibold">ملف الفاتورة / المستندات</p>
                    <p class="text-sm text-white-dark">{{ basename($shipment->invoice_file) }}</p>
                </div>
                <a href="{{ Storage::url($shipment->invoice_file) }}" target="_blank" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ltr:mr-2 rtl:ml-2" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z" />
                        <path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z" />
                    </svg>
                    عرض الملف
                </a>
            </div>
        </div>
        @endif

        <!-- الفواتير المرتبطة -->
        <div class="panel">
            <div class="mb-5 flex items-center justify-between">
                <h5 class="font-semibold text-lg dark:text-white-light">الفواتير المرتبطة ({{ $shipment->invoices->count() }})</h5>
                <button type="button"
                        class="btn btn-primary btn-sm"
                        @click="$dispatch('open-modal', 'attach-invoice-modal')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ltr:mr-1 rtl:ml-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    {{ $shipment->invoices->count() > 0 ? 'ربط بفاتورة أخرى' : 'ربط بفاتورة' }}
                </button>
            </div>

            @if($shipment->invoices->count() > 0)
                <div class="table-responsive">
                    <table class="table-hover">
                        <thead>
                            <tr>
                                <th>رقم الفاتورة</th>
                                <th>الشركة المستفيدة</th>
                                <th>المبلغ</th>
                                <th>التاريخ</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($shipment->invoices as $invoice)
                                <tr>
                                    <td class="font-semibold">#{{ $invoice->invoice_number }}</td>
                                    <td>{{ $invoice->beneficiary_company }}</td>
                                    <td>{{ number_format($invoice->amount, 2) }} دينار</td>
                                    <td>{{ $invoice->invoice_date->format('d M Y') }}</td>
                                    <td>
                                        @if($invoice->status === 'paid')
                                            <span class="badge bg-success">مدفوعة</span>
                                        @else
                                            <span class="badge bg-warning">غير مدفوعة</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('companies.invoices.show', [$company, $invoice]) }}" class="text-primary hover:underline">
                                            عرض التفاصيل
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8 text-white-dark">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto mb-3 opacity-50" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                    </svg>
                    <p>لا توجد فواتير مرتبطة بهذه الشحنة</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal تحديث حالة الاستلام -->
    <div x-data="{ open: false }"
         @open-modal.window="if ($event.detail === 'received-status-modal') open = true"
         @close-modal.window="open = false"
         @keydown.escape.window="open = false"
         x-show="open"
         class="fixed inset-0 z-[999] overflow-y-auto"
         style="display: none;">
        <div class="flex min-h-screen items-center justify-center px-4">
            <div @click="open = false" class="fixed inset-0 bg-[black]/60"></div>
            <div class="panel my-8 w-full max-w-lg overflow-hidden rounded-lg border-0 p-0">
                <div class="flex items-center justify-between bg-[#fbfbfb] px-5 py-3 dark:bg-[#121c2c]">
                    <h5 class="text-lg font-bold">تحديث حالة الاستلام</h5>
                    <button @click="open = false" type="button" class="text-white-dark hover:text-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                <div class="p-5">
                    <form action="{{ route('companies.shipments.update-received-status', [$company, $shipment]) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="mb-5">
                            <label class="block mb-3 font-semibold">حالة الاستلام</label>
                            <div class="space-y-2">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="received_status" value="received" class="form-radio" {{ $shipment->received_status === 'received' ? 'checked' : '' }} />
                                    <span class="ltr:ml-2 rtl:mr-2">مستلمة ✅</span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="received_status" value="not_received" class="form-radio" {{ $shipment->received_status === 'not_received' ? 'checked' : '' }} />
                                    <span class="ltr:ml-2 rtl:mr-2">غير مستلمة ❌</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-end items-center mt-8 gap-2">
                            <button @click="open = false" type="button" class="btn btn-outline-danger">إلغاء</button>
                            <button type="submit" class="btn btn-primary">حفظ التحديث</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal تحديث حالة الدخول -->
    <div x-data="{ open: false }"
         @open-modal.window="if ($event.detail === 'entry-status-modal') open = true"
         @close-modal.window="open = false"
         @keydown.escape.window="open = false"
         x-show="open"
         class="fixed inset-0 z-[999] overflow-y-auto"
         style="display: none;">
        <div class="flex min-h-screen items-center justify-center px-4">
            <div @click="open = false" class="fixed inset-0 bg-[black]/60"></div>
            <div class="panel my-8 w-full max-w-lg overflow-hidden rounded-lg border-0 p-0">
                <div class="flex items-center justify-between bg-[#fbfbfb] px-5 py-3 dark:bg-[#121c2c]">
                    <h5 class="text-lg font-bold">تحديث حالة الدخول</h5>
                    <button @click="open = false" type="button" class="text-white-dark hover:text-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                <div class="p-5">
                    <form action="{{ route('companies.shipments.update-entry-status', [$company, $shipment]) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="mb-5">
                            <label class="block mb-3 font-semibold">حالة الدخول</label>
                            <div class="space-y-2">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="entry_status" value="entered" class="form-radio" {{ $shipment->entry_status === 'entered' ? 'checked' : '' }} />
                                    <span class="ltr:ml-2 rtl:mr-2">داخلة ✅</span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="entry_status" value="not_entered" class="form-radio" {{ $shipment->entry_status === 'not_entered' ? 'checked' : '' }} />
                                    <span class="ltr:ml-2 rtl:mr-2">غير داخلة ❌</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-end items-center mt-8 gap-2">
                            <button @click="open = false" type="button" class="btn btn-outline-danger">إلغاء</button>
                            <button type="submit" class="btn btn-primary">حفظ التحديث</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal تحديث حالة أوراق الدخول -->
    <div x-data="{ open: false }"
         @open-modal.window="if ($event.detail === 'entry-permit-status-modal') open = true"
         @close-modal.window="open = false"
         @keydown.escape.window="open = false"
         x-show="open"
         class="fixed inset-0 z-[999] overflow-y-auto"
         style="display: none;">
        <div class="flex min-h-screen items-center justify-center px-4">
            <div @click="open = false" class="fixed inset-0 bg-[black]/60"></div>
            <div class="panel my-8 w-full max-w-lg overflow-hidden rounded-lg border-0 p-0">
                <div class="flex items-center justify-between bg-[#fbfbfb] px-5 py-3 dark:bg-[#121c2c]">
                    <h5 class="text-lg font-bold">تحديث حالة أوراق الدخول</h5>
                    <button @click="open = false" type="button" class="text-white-dark hover:text-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                <div class="p-5">
                    <form action="{{ route('companies.shipments.update-entry-permit-status', [$company, $shipment]) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="mb-5">
                            <label class="block mb-3 font-semibold">حالة أوراق الدخول</label>
                            <div class="space-y-2">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="entry_permit_status" value="received" class="form-radio" {{ $shipment->entry_permit_status === 'received' ? 'checked' : '' }} />
                                    <span class="ltr:ml-2 rtl:mr-2">مستلم ✅</span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="entry_permit_status" value="not_received" class="form-radio" {{ $shipment->entry_permit_status === 'not_received' ? 'checked' : '' }} />
                                    <span class="ltr:ml-2 rtl:mr-2">غير مستلم ❌</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-end items-center mt-8 gap-2">
                            <button @click="open = false" type="button" class="btn btn-outline-danger">إلغاء</button>
                            <button type="submit" class="btn btn-primary">حفظ التحديث</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal ربط الفاتورة -->
    <div x-data="{ open: false }"
         @open-modal.window="if ($event.detail === 'attach-invoice-modal') open = true"
         x-show="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[999] overflow-y-auto"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black bg-opacity-50" @click="open = false"></div>
            <div class="relative bg-white dark:bg-[#1b2e4b] rounded-lg shadow-lg max-w-md w-full p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">ربط الشحنة بفاتورة</h3>
                    <button @click="open = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form action="{{ route('companies.shipments.attach-invoice', [$company, $shipment]) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">اختر الفاتورة</label>
                            <select name="invoice_id" class="form-select" required>
                                <option value="">-- اختر الفاتورة --</option>
                                @foreach($company->invoices as $invoice)
                                    <option value="{{ $invoice->id }}">
                                        #{{ $invoice->invoice_number }} - {{ $invoice->beneficiary_company }} - {{ number_format($invoice->amount_usd, 2) }} USD
                                    </option>
                                @endforeach
                            </select>
                            @error('invoice_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        @if($company->invoices->count() == 0)
                            <div class="text-center py-4 text-gray-500">
                                <p>لا توجد فواتير متاحة للربط</p>
                                <p class="text-sm">جميع الفواتير مرتبطة بشحنات أخرى بالفعل</p>
                            </div>
                        @endif
                    </div>

                    <div class="flex justify-end items-center mt-6 gap-2">
                        <button @click="open = false" type="button" class="btn btn-outline-secondary">إلغاء</button>
                        @if($company->invoices->count() > 0)
                            <button type="submit" class="btn btn-primary">ربط الفاتورة</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout.company>

