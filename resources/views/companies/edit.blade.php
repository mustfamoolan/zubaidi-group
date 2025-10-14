<x-layout.custom-default>
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="javascript:;" class="text-primary hover:underline">لوحة التحكم</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <a href="{{ route('companies.index') }}" class="text-primary hover:underline">الشركات</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>تعديل الشركة</span>
            </li>
        </ul>
        <div class="pt-5">
            <div class="panel">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">تعديل الشركة</h5>
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

                <form action="{{ route('companies.update', $company->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="name">اسم الشركة</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $company->name) }}" class="form-input" placeholder="أدخل اسم الشركة" required />
                    </div>

                    <div>
                        <label for="image">صورة الشركة</label>
                        @if($company->image)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $company->image) }}" alt="{{ $company->name }}" class="w-32 h-32 object-cover rounded-md" />
                                <p class="text-xs text-gray-500 mt-1">الصورة الحالية</p>
                            </div>
                        @endif
                        <input id="image" name="image" type="file" class="form-input" accept="image/*" />
                        <p class="text-xs text-gray-500 mt-1">اترك هذا الحقل فارغًا إذا كنت لا تريد تغيير الصورة. الأنواع المسموح بها: JPG, PNG, GIF. الحد الأقصى للحجم: 2MB</p>
                    </div>

                    <div class="flex items-center justify-end">
                        <a href="{{ route('companies.index') }}" class="btn btn-outline-danger ltr:mr-3 rtl:ml-3">إلغاء</a>
                        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout.custom-default>
