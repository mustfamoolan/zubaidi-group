<x-layout.admin>
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="javascript:;" class="text-primary hover:underline">لوحة التحكم</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <a href="{{ route('roles.index') }}" class="text-primary hover:underline">الأدوار</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>إضافة دور جديد</span>
            </li>
        </ul>
        <div class="pt-5">
            <div class="panel">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">إضافة دور جديد</h5>
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

                <form action="{{ route('roles.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label for="name">اسم الدور (بالإنجليزية)</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" class="form-input" placeholder="أدخل اسم الدور بالإنجليزية" required />
                        <p class="text-xs text-gray-500 mt-1">مثال: admin, editor, user</p>
                    </div>

                    <div>
                        <label for="display_name">الاسم المعروض</label>
                        <input id="display_name" name="display_name" type="text" value="{{ old('display_name') }}" class="form-input" placeholder="أدخل الاسم المعروض للدور" required />
                        <p class="text-xs text-gray-500 mt-1">مثال: مدير النظام، محرر، مستخدم</p>
                    </div>

                    <div>
                        <label for="description">الوصف</label>
                        <textarea id="description" name="description" class="form-textarea" placeholder="أدخل وصف الدور">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label class="mb-2 inline-block">الصلاحيات</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($permissions as $group => $groupPermissions)
                                <div class="border rounded-md p-4">
                                    <h6 class="font-semibold mb-3">{{ ucfirst($group) }}</h6>
                                    @foreach ($groupPermissions as $permission)
                                        <div class="mb-2">
                                            <label class="flex items-center cursor-pointer">
                                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="form-checkbox" {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }} />
                                                <span class="text-white-dark mr-2">{{ $permission->display_name }}</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex items-center justify-end">
                        <a href="{{ route('roles.index') }}" class="btn btn-outline-danger ltr:mr-3 rtl:ml-3">إلغاء</a>
                        <button type="submit" class="btn btn-primary">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout.admin>
