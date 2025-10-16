<x-layout.company :company="$company">
    <div x-data="beneficiaryEdit">
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{ route('companies.show', $company) }}" class="text-primary hover:underline">{{ $company->name }}</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <a href="{{ route('companies.beneficiaries.index', $company) }}" class="text-primary hover:underline">المستفيدين</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>تعديل المستفيد</span>
            </li>
        </ul>
        <div class="pt-5">
            <div class="panel">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">تعديل المستفيد</h5>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('companies.beneficiaries.update', [$company, $beneficiary]) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="name">اسم المستفيد</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $beneficiary->name) }}" class="form-input" placeholder="أدخل اسم المستفيد" required />
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end">
                        <a href="{{ route('companies.beneficiaries.index', $company) }}" class="btn btn-outline-danger ltr:mr-3 rtl:ml-3">إلغاء</a>
                        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("alpine:init", () => {
            Alpine.data('beneficiaryEdit', () => ({
                init() {
                    // تهيئة البيانات
                }
            }))
        })
    </script>
</x-layout.company>
