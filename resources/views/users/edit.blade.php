<x-layout.custom-default>
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="javascript:;" class="text-primary hover:underline">لوحة التحكم</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <a href="{{ route('users.index') }}" class="text-primary hover:underline">المستخدمين</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>تعديل المستخدم</span>
            </li>
        </ul>
        <div class="pt-5">
            <div class="panel">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">تعديل المستخدم</h5>
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

                <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="name">الاسم</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" class="form-input" placeholder="أدخل اسم المستخدم" required />
                    </div>

                    <div>
                        <label for="email">البريد الإلكتروني</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" class="form-input" placeholder="أدخل البريد الإلكتروني" required />
                    </div>

                    <div>
                        <label for="phone">رقم الهاتف</label>
                        <input id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}" class="form-input" placeholder="أدخل رقم الهاتف" />
                    </div>

                    <div>
                        <label for="password">كلمة المرور</label>
                        <input id="password" name="password" type="password" class="form-input" placeholder="أدخل كلمة المرور الجديدة (اتركها فارغة للاحتفاظ بكلمة المرور الحالية)" />
                    </div>

                    <div>
                        <label for="password_confirmation">تأكيد كلمة المرور</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" class="form-input" placeholder="أدخل تأكيد كلمة المرور" />
                    </div>

                    <div>
                        <label for="role_id">الدور</label>
                        <select id="role_id" name="role_id" class="form-select" required>
                            <option value="">اختر الدور</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>{{ $role->display_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="is_active" class="flex items-center cursor-pointer">
                            <input id="is_active" name="is_active" type="checkbox" class="form-checkbox" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} />
                            <span class="mr-2">مفعل</span>
                        </label>
                    </div>
                    
                    <div>
                        <label>الصلاحيات المباشرة</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-3">
                            @foreach($permissions as $group => $groupPermissions)
                                <div class="border rounded-md p-4">
                                    <h6 class="font-semibold mb-3">{{ ucfirst($group) }}</h6>
                                    <div class="space-y-2">
                                        @foreach($groupPermissions as $permission)
                                            <div>
                                                <label class="flex items-center cursor-pointer">
                                                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="form-checkbox" {{ in_array($permission->id, old('permissions', $userPermissions)) ? 'checked' : '' }} />
                                                    <span class="mr-2">{{ $permission->display_name }}</span>
                                                </label>
                                                <p class="text-xs text-gray-500 mt-1">{{ $permission->name }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <p class="text-xs text-gray-500 mt-2">هذه الصلاحيات ستضاف مباشرة للمستخدم، بالإضافة إلى الصلاحيات التي يحصل عليها من الدور.</p>
                    </div>

                    <div class="flex items-center justify-end">
                        <a href="{{ route('users.index') }}" class="btn btn-outline-danger ltr:mr-3 rtl:ml-3">إلغاء</a>
                        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout.custom-default>
