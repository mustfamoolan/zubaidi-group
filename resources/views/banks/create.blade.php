<x-layout.company :company="$company">
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{ route('companies.show', $company) }}" class="text-primary hover:underline">{{ $company->name }}</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <a href="{{ route('companies.banks.index', $company) }}" class="text-primary hover:underline">المصارف</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>إضافة مصرف جديد</span>
            </li>
        </ul>

        <div class="pt-5">
            <div class="panel">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">إضافة مصرف جديد</h5>
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

                <form action="{{ route('companies.banks.store', $company) }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label for="name">اسم المصرف</label>
                        <input id="name" type="text" name="name" class="form-input" value="{{ old('name') }}" required />
                    </div>
                    <div>
                        <label for="opening_balance">المبلغ الافتتاحي</label>
                        <input id="opening_balance" type="number" step="0.01" name="opening_balance" class="form-input" value="{{ old('opening_balance', 0) }}" required />
                    </div>
                    <div>
                        <label for="currency">العملة</label>
                        <select id="currency" name="currency" class="form-select" required>
                            <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>دولار أمريكي (USD)</option>
                            <option value="IQD" {{ old('currency') == 'IQD' ? 'selected' : '' }}>دينار عراقي (IQD)</option>
                            <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>يورو (EUR)</option>
                        </select>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="btn btn-primary">إضافة</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout.company>

