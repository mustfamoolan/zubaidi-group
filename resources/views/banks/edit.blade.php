<x-layout.company :company="$company">
    <script src="{{ asset('assets/js/number-formatter.js') }}"></script>
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{ route('companies.show', $company) }}" class="text-primary hover:underline">{{ $company->name }}</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <a href="{{ route('companies.banks.index', $company) }}" class="text-primary hover:underline">المصارف</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>تعديل مصرف</span>
            </li>
        </ul>

        <div class="pt-5">
            <div class="panel">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">تعديل المصرف: {{ $bank->name }}</h5>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('companies.banks.update', [$company, $bank]) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="name">اسم المصرف</label>
                        <input id="name" type="text" name="name" class="form-input" value="{{ old('name', $bank->name) }}" required />
                    </div>
                    <div>
                        <label for="opening_balance">المبلغ الافتتاحي</label>
                        <input id="opening_balance" type="text" step="0.01" name="opening_balance" class="form-input number-input" value="{{ old('opening_balance', $bank->opening_balance) }}" required />
                        <small class="text-white-dark">ملاحظة: تغيير الرصيد الافتتاحي سيؤثر على الرصيد الحالي</small>
                    </div>
                    <div>
                        <label for="currency">العملة</label>
                        <select id="currency" name="currency" class="form-select" required>
                            <option value="USD" {{ old('currency', $bank->currency) == 'USD' ? 'selected' : '' }}>دولار أمريكي (USD)</option>
                            <option value="IQD" {{ old('currency', $bank->currency) == 'IQD' ? 'selected' : '' }}>دينار عراقي (IQD)</option>
                            <option value="EUR" {{ old('currency', $bank->currency) == 'EUR' ? 'selected' : '' }}>يورو (EUR)</option>
                        </select>
                    </div>
                    <div class="flex justify-between">
                        <a href="{{ route('companies.banks.show', [$company, $bank]) }}" class="btn btn-outline-danger">إلغاء</a>
                        <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout.company>

