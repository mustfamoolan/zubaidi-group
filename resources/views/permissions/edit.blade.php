<x-layout.custom-default>
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="javascript:;" class="text-primary hover:underline">لوحة التحكم</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <a href="{{ route('permissions.index') }}" class="text-primary hover:underline">الصلاحيات</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>تعديل الصلاحية</span>
            </li>
        </ul>
        <div class="pt-5">
            <div class="panel">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">تعديل الصلاحية</h5>
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

                <form action="{{ route('permissions.update', $permission->id) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="name">اسم الصلاحية (بالإنجليزية)</label>
                        <input id="name" type="text" name="name" class="form-input" value="{{ old('name', $permission->name) }}" required />
                        <p class="text-xs text-gray-500 mt-1">مثال: users.create، users.edit، users.delete</p>
                    </div>
                    <div>
                        <label for="display_name">الاسم المعروض</label>
                        <input id="display_name" type="text" name="display_name" class="form-input" value="{{ old('display_name', $permission->display_name) }}" required />
                        <p class="text-xs text-gray-500 mt-1">مثال: إنشاء مستخدم، تعديل مستخدم، حذف مستخدم</p>
                    </div>
                    <div>
                        <label for="group">المجموعة</label>
                        <input id="group" type="text" name="group" class="form-input" value="{{ old('group', $permission->group) }}" required />
                        <p class="text-xs text-gray-500 mt-1">مثال: المستخدمين، الأدوار، الشركات</p>
                    </div>
                    <div>
                        <label for="description">الوصف</label>
                        <textarea id="description" name="description" class="form-textarea" rows="3">{{ old('description', $permission->description) }}</textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="btn btn-primary">تحديث</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout.custom-default>
