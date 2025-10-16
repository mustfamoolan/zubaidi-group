<x-layout.company :company="$company">
    <div class="panel">
        <div class="flex items-center justify-between mb-5">
            <h5 class="font-semibold text-lg dark:text-white-light">تعديل الفاتورة #{{ $invoice->invoice_number }}</h5>
        </div>
        <form action="{{ route('companies.invoices.update', [$company, $invoice]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-5">
                <label for="invoice_number">رقم الفاتورة</label>
                <div class="flex">
                    <div class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 font-semibold border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">#</div>
                    <input id="invoice_number" name="invoice_number" type="text" value="{{ old('invoice_number', $invoice->invoice_number) }}" class="form-input ltr:rounded-l-none rtl:rounded-r-none" required />
                </div>
                @error('invoice_number')
                    <span class="text-danger text-xs">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-5">
                <label for="amount_usd">المبلغ (بالدولار)</label>
                <div class="flex">
                    <input id="amount_usd" name="amount_usd" type="number" step="0.01" value="{{ old('amount_usd', $invoice->amount_usd) }}" class="form-input rounded-none" required oninput="calculateTotalAmount()" />
                    <div class="bg-[#eee] flex justify-center items-center ltr:rounded-r-md rtl:rounded-l-md px-3 font-semibold border ltr:border-l-0 rtl:border-r-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">USD</div>
                </div>
                @error('amount_usd')
                    <span class="text-danger text-xs">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-5">
                <label for="exchange_rate">سعر الصرف</label>
                <div class="flex">
                    <input id="exchange_rate" name="exchange_rate" type="number" step="0.01" value="{{ old('exchange_rate', $invoice->exchange_rate) }}" class="form-input rounded-none" required oninput="calculateTotalAmount()" />
                    <div class="bg-[#eee] flex justify-center items-center ltr:rounded-r-md rtl:rounded-l-md px-3 font-semibold border ltr:border-l-0 rtl:border-r-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">دينار/دولار</div>
                </div>
                @error('exchange_rate')
                    <span class="text-danger text-xs">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-5">
                <label for="bank_commission">عمولة المصرف (دينار عراقي)</label>
                <div class="flex">
                    <input id="bank_commission" name="bank_commission" type="number" step="0.01" value="{{ old('bank_commission', $invoice->bank_commission) }}" class="form-input rounded-none" oninput="calculateTotalAmount()" />
                    <div class="bg-[#eee] flex justify-center items-center ltr:rounded-r-md rtl:rounded-l-md px-3 font-semibold border ltr:border-l-0 rtl:border-r-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">دينار</div>
                </div>
                @error('bank_commission')
                    <span class="text-danger text-xs">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-5">
                <label for="total_amount_iqd">المبلغ الإجمالي (دينار عراقي)</label>
                <div class="flex">
                    <input id="total_amount_iqd" name="total_amount_iqd" type="number" step="0.01" value="{{ old('total_amount_iqd', $invoice->total_amount_iqd) }}" class="form-input rounded-none bg-gray-100" readonly />
                    <div class="bg-[#eee] flex justify-center items-center ltr:rounded-r-md rtl:rounded-l-md px-3 font-semibold border ltr:border-l-0 rtl:border-r-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">دينار</div>
                </div>
                <small class="text-gray-500">يتم حسابه تلقائياً: (المبلغ بالدولار × سعر الصرف) + عمولة المصرف</small>
                @error('total_amount_iqd')
                    <span class="text-danger text-xs">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-5">
                <label for="bank_id">المصرف</label>
                <select id="bank_id" name="bank_id" class="form-select" required>
                    <option value="">اختر مصرف</option>
                    @foreach($banks as $bank)
                        <option value="{{ $bank->id }}" @if(old('bank_id', $invoice->bank_id) == $bank->id) selected @endif>
                            {{ $bank->name }} ({{ number_format($bank->current_balance, 2) }} {{ $bank->currency }})
                        </option>
                    @endforeach
                </select>
                @error('bank_id')
                    <span class="text-danger text-xs">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-5">
                <label for="invoice_date">تاريخ الفاتورة</label>
                <input id="invoice_date" name="invoice_date" type="date" value="{{ old('invoice_date', $invoice->invoice_date->format('Y-m-d')) }}" class="form-input" required />
                @error('invoice_date')
                    <span class="text-danger text-xs">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-5">
                <label for="beneficiary_id">اسم المستفيد</label>
                <select id="beneficiary_id" name="beneficiary_id" class="form-select" required>
                    <option value="">اختر المستفيد</option>
                    @foreach($beneficiaries as $beneficiary)
                        <option value="{{ $beneficiary->id }}" @if(old('beneficiary_id', $invoice->beneficiary_id) == $beneficiary->id) selected @endif>
                            {{ $beneficiary->name }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">
                    <a href="{{ route('companies.beneficiaries.create', $company) }}" class="text-primary hover:underline">إضافة مستفيد جديد</a>
                </p>
                @error('beneficiary_id')
                    <span class="text-danger text-xs">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-5">
                <label for="status">الحالة</label>
                <select id="status" name="status" class="form-select" required>
                    <option value="unpaid" @if(old('status', $invoice->status) === 'unpaid') selected @endif>غير مدفوعة</option>
                    <option value="paid" @if(old('status', $invoice->status) === 'paid') selected @endif>مدفوعة</option>
                </select>
                @error('status')
                    <span class="text-danger text-xs">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-5">
                <label for="shipments">الشحنات المرتبطة (اختياري)</label>
                <select id="shipments" name="shipments[]" class="form-select" multiple>
                    @foreach($shipments as $shipment)
                        <option value="{{ $shipment->id }}" @if($invoice->shipments->contains($shipment->id)) selected @endif>
                            {{ $shipment->container_number }} - {{ $shipment->status === 'shipped' ? 'مشحون' : 'غير مشحون' }}
                        </option>
                    @endforeach
                </select>
                <small class="text-white-dark">يمكنك اختيار عدة شحنات بالضغط على Ctrl + Click</small>
                @error('shipments')
                    <span class="text-danger text-xs">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex justify-end items-center mt-8 gap-2">
                <a href="{{ route('companies.invoices.index', $company) }}" class="btn btn-outline-danger">
                    إلغاء
                </a>
                <button type="submit" class="btn btn-primary">
                    حفظ التعديلات
                </button>
            </div>
        </form>
    </div>

    <script>
        function calculateTotalAmount() {
            const amountUsd = parseFloat(document.getElementById('amount_usd')?.value || 0);
            const exchangeRate = parseFloat(document.getElementById('exchange_rate')?.value || 0);
            const bankCommission = parseFloat(document.getElementById('bank_commission')?.value || 0);

            const totalAmount = (amountUsd * exchangeRate) + bankCommission;

            const totalInput = document.getElementById('total_amount_iqd');
            if (totalInput) {
                totalInput.value = totalAmount.toFixed(2);
            }
        }

        // حساب المبلغ عند تحميل الصفحة
        document.addEventListener('DOMContentLoaded', function() {
            calculateTotalAmount();
        });
    </script>
</x-layout.company>

