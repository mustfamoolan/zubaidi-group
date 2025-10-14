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
                <span>إضافة شركة جديدة</span>
            </li>
        </ul>
        <div class="pt-5">
            <div class="panel">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">إضافة شركة جديدة</h5>
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

                <form action="{{ route('companies.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    <div>
                        <label for="name">اسم الشركة</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" class="form-input" placeholder="أدخل اسم الشركة" required />
                    </div>

                    <div>
                        <label for="image">صورة الشركة</label>
                        <input id="image" name="image" type="file" class="form-input" accept="image/*" />
                        <p class="text-xs text-gray-500 mt-1">الصورة اختيارية. الأنواع المسموح بها: JPG, PNG, GIF. الحد الأقصى للحجم: 2MB</p>
                    </div>

                    <div class="flex items-center justify-end">
                        <a href="{{ route('companies.index') }}" class="btn btn-outline-danger ltr:mr-3 rtl:ml-3">إلغاء</a>
                        <button type="submit" class="btn btn-primary">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout.custom-default>
