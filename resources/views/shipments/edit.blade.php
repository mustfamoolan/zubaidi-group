<x-layout.company :company="$company">
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse mb-5">
            <li>
                <a href="{{ route('companies.show', $company) }}" class="text-primary hover:underline">{{ $company->name }}</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <a href="{{ route('companies.shipments.index', $company) }}" class="text-primary hover:underline">الشحنات</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>تعديل الشحنة #{{ $shipment->container_number }}</span>
            </li>
        </ul>

        <div class="panel">
            <div class="flex items-center justify-between mb-5">
                <h5 class="font-semibold text-lg dark:text-white-light">تعديل الشحنة #{{ $shipment->container_number }}</h5>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('companies.shipments.update', [$company, $shipment]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    <div>
                        <label for="container_number">رقم الحاوية <span class="text-danger">*</span></label>
                        <input id="container_number" name="container_number" type="text" class="form-input" value="{{ old('container_number', $shipment->container_number) }}" required />
                    </div>

                    <div>
                        <label for="policy_number">رقم البوليصة <span class="text-danger">*</span></label>
                        <input id="policy_number" name="policy_number" type="text" class="form-input" value="{{ old('policy_number', $shipment->policy_number) }}" required />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    <div>
                        <label for="status">حالة الشحن <span class="text-danger">*</span></label>
                        <select id="status" name="status" class="form-select" required>
                            <option value="not_shipped" {{ old('status', $shipment->status) === 'not_shipped' ? 'selected' : '' }}>غير مشحون</option>
                            <option value="shipped" {{ old('status', $shipment->status) === 'shipped' ? 'selected' : '' }}>مشحون</option>
                        </select>
                    </div>

                    <div>
                        <label for="shipping_date">تاريخ الشحن <span class="text-danger">*</span></label>
                        <input id="shipping_date" name="shipping_date" type="date" class="form-input" value="{{ old('shipping_date', $shipment->shipping_date->format('Y-m-d')) }}" required />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">
                    <div>
                        <label for="weight">الوزن (كغ)</label>
                        <input id="weight" name="weight" type="number" step="0.01" class="form-input" value="{{ old('weight', $shipment->weight) }}" placeholder="0.00" />
                    </div>

                    <div>
                        <label for="container_size">حجم الحاوية (قياسها بالمترية)</label>
                        <select id="container_size" name="container_size" class="form-select">
                            <option value="">اختر الحجم</option>
                            <option value="C" {{ old('container_size', $shipment->container_size) === 'C' ? 'selected' : '' }}>C (20 قدم)</option>
                            <option value="B" {{ old('container_size', $shipment->container_size) === 'B' ? 'selected' : '' }}>B (40 قدم)</option>
                            <option value="M" {{ old('container_size', $shipment->container_size) === 'M' ? 'selected' : '' }}>M (45 قدم)</option>
                        </select>
                    </div>

                    <div>
                        <label for="carton_count">عدد الكراتين</label>
                        <input id="carton_count" name="carton_count" type="number" class="form-input" value="{{ old('carton_count', $shipment->carton_count) }}" placeholder="0" />
                    </div>
                </div>

                <!-- حالات الشحنة -->
                <div class="panel border border-primary/30 mb-5">
                    <div class="flex items-center justify-between mb-4">
                        <h6 class="font-semibold">حالات الشحنة</h6>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <!-- حالة الاستلام -->
                        <div>
                            <label for="received_status">حالة الاستلام</label>
                            <select id="received_status" name="received_status" class="form-select">
                                <option value="not_received" {{ old('received_status', $shipment->received_status) === 'not_received' ? 'selected' : '' }}>غير مستلمة</option>
                                <option value="received" {{ old('received_status', $shipment->received_status) === 'received' ? 'selected' : '' }}>مستلمة</option>
                            </select>
                            @if($shipment->received_date)
                                <small class="text-white-dark">آخر تحديث: {{ $shipment->received_date->format('Y-m-d') }}</small>
                            @endif
                        </div>

                        <!-- حالة الدخول -->
                        <div>
                            <label for="entry_status">حالة الدخول</label>
                            <select id="entry_status" name="entry_status" class="form-select">
                                <option value="not_entered" {{ old('entry_status', $shipment->entry_status) === 'not_entered' ? 'selected' : '' }}>غير داخلة</option>
                                <option value="entered" {{ old('entry_status', $shipment->entry_status) === 'entered' ? 'selected' : '' }}>داخلة</option>
                            </select>
                            @if($shipment->entry_date)
                                <small class="text-white-dark">آخر تحديث: {{ $shipment->entry_date->format('Y-m-d') }}</small>
                            @endif
                        </div>

                        <!-- حالة أوراق الدخول -->
                        <div>
                            <label for="entry_permit_status">تصريح الدخول</label>
                            <select id="entry_permit_status" name="entry_permit_status" class="form-select">
                                <option value="not_received" {{ old('entry_permit_status', $shipment->entry_permit_status) === 'not_received' ? 'selected' : '' }}>غير مستلم</option>
                                <option value="received" {{ old('entry_permit_status', $shipment->entry_permit_status) === 'received' ? 'selected' : '' }}>مستلم</option>
                            </select>
                            @if($shipment->entry_permit_date)
                                <small class="text-white-dark">آخر تحديث: {{ $shipment->entry_permit_date->format('Y-m-d') }}</small>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- ملف الفاتورة -->
                <div class="mb-5">
                    <label for="invoice_file">ملف الفاتورة (PDF, JPG, PNG - حتى 10MB)</label>
                    @if($shipment->invoice_file)
                        <div class="flex items-center gap-2 mb-2">
                            <a href="{{ asset('storage/' . $shipment->invoice_file) }}" target="_blank" class="text-primary hover:underline flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                                </svg>
                                عرض الملف الحالي
                            </a>
                            <span class="badge badge-outline-success">موجود</span>
                        </div>
                    @endif
                    <input id="invoice_file" name="invoice_file" type="file" class="form-input" accept=".pdf,.jpg,.jpeg,.png" />
                    <small class="text-white-dark">
                        @if($shipment->invoice_file)
                            اترك الحقل فارغاً للحفاظ على الملف الحالي، أو اختر ملفاً جديداً لاستبداله
                        @else
                            اختياري - يمكنك رفع ملف الفاتورة أو أي مستند متعلق بالشحنة
                        @endif
                    </small>

                    @if($shipment->invoice_file)
                        <div class="form-check mt-2">
                            <input type="checkbox" id="delete_invoice_file" name="delete_invoice_file" value="1" class="form-checkbox">
                            <label for="delete_invoice_file" class="text-danger">حذف الملف الحالي</label>
                        </div>
                    @endif
                </div>

                <!-- الفواتير المرتبطة -->
                <div class="mb-5">
                    <label for="invoices">الفواتير المرتبطة (اختياري)</label>
                    <select id="invoices" name="invoices[]" class="form-select" multiple>
                        @foreach($invoices as $invoice)
                            <option value="{{ $invoice->id }}"
                                {{ in_array($invoice->id, old('invoices', $shipment->invoices->pluck('id')->toArray())) ? 'selected' : '' }}>
                                #{{ $invoice->invoice_number }} - {{ $invoice->beneficiary_company }} ({{ number_format($invoice->amount, 2) }} دينار)
                            </option>
                        @endforeach
                    </select>
                    <small class="text-white-dark">يمكنك اختيار عدة فواتير بالضغط على Ctrl + Click</small>

                    @if($shipment->invoices->count() > 0)
                        <div class="mt-2">
                            <span class="text-sm font-semibold">الفواتير المرتبطة حالياً:</span>
                            <div class="flex flex-wrap gap-2 mt-1">
                                @foreach($shipment->invoices as $invoice)
                                    <span class="badge badge-outline-primary">
                                        #{{ $invoice->invoice_number }} - {{ number_format($invoice->amount, 2) }} دينار
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div class="flex justify-end items-center mt-8 gap-2">
                    <a href="{{ route('companies.shipments.index', $company) }}" class="btn btn-outline-danger">
                        إلغاء
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ltr:mr-2 rtl:ml-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        حفظ التعديلات
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout.company>
