<x-layout.company :company="$company">
    <script src="{{ asset('assets/js/number-formatter.js') }}"></script>
    <div class="panel">
        <div class="flex items-center justify-between mb-5">
            <h5 class="font-semibold text-lg dark:text-white-light">إضافة شحنة جديدة</h5>
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

        <form action="{{ route('companies.shipments.store', $company) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                <div>
                    <label for="container_number">رقم الحاوية <span class="text-danger">*</span></label>
                    <input id="container_number" name="container_number" type="text" class="form-input" value="{{ old('container_number') }}" required />
                </div>

                <div>
                    <label for="policy_number">رقم البوليصة <span class="text-danger">*</span></label>
                    <input id="policy_number" name="policy_number" type="text" class="form-input" value="{{ old('policy_number') }}" required />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                <div>
                    <label for="status">حالة الشحن <span class="text-danger">*</span></label>
                    <select id="status" name="status" class="form-select" required>
                        <option value="not_shipped" {{ old('status') === 'not_shipped' ? 'selected' : '' }}>غير مشحون</option>
                        <option value="shipped" {{ old('status') === 'shipped' ? 'selected' : '' }}>مشحون</option>
                    </select>
                </div>

                <div>
                    <label for="shipping_date">تاريخ الشحن <span class="text-danger">*</span></label>
                    <input id="shipping_date" name="shipping_date" type="date" class="form-input" value="{{ old('shipping_date', date('Y-m-d')) }}" required />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">
                <div>
                    <label for="weight">الوزن (كغ)</label>
                    <input id="weight" name="weight" type="text" step="0.01" class="form-input number-input" value="{{ old('weight') }}" placeholder="0.00" />
                </div>

                <div>
                    <label for="container_size">حجم الحاوية (قياسها بالمترية)</label>
                    <input id="container_size" name="container_size" type="text" class="form-input" value="{{ old('container_size') }}" placeholder="مثال: 68.0 MCB" />
                    <small class="text-white-dark">أدخل حجم الحاوية مع الوحدة، مثال: 68.0 MCB</small>
                </div>

                <div>
                    <label for="carton_count">عدد الكراتين</label>
                    <input id="carton_count" name="carton_count" type="text" class="form-input number-input" value="{{ old('carton_count') }}" placeholder="0" />
                </div>
            </div>

            <div class="mb-5">
                <label for="invoice_file">ملف الفاتورة (PDF, JPG, PNG - حتى 10MB)</label>
                <input id="invoice_file" name="invoice_file" type="file" class="form-input" accept=".pdf,.jpg,.jpeg,.png" />
                <small class="text-white-dark">اختياري - يمكنك رفع ملف الفاتورة أو أي مستند متعلق بالشحنة</small>
            </div>

            <div class="mb-5">
                <label for="invoices">الفواتير المرتبطة (اختياري)</label>
                <select id="invoices" name="invoices[]" class="form-select" multiple>
                    @foreach($invoices as $invoice)
                        <option value="{{ $invoice->id }}" {{ in_array($invoice->id, old('invoices', [])) ? 'selected' : '' }}>
                            #{{ $invoice->invoice_number }} - {{ $invoice->beneficiary_company }} ({{ number_format($invoice->amount, 2) }} دينار)
                        </option>
                    @endforeach
                </select>
                <small class="text-white-dark">يمكنك اختيار عدة فواتير بالضغط على Ctrl + Click</small>
            </div>

            <div class="flex justify-end items-center mt-8 gap-2">
                <a href="{{ route('companies.shipments.index', $company) }}" class="btn btn-outline-danger">
                    إلغاء
                </a>
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ltr:mr-2 rtl:ml-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    حفظ الشحنة
                </button>
            </div>
        </form>
    </div>
</x-layout.company>

