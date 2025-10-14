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
                <span>عرض الدور</span>
            </li>
        </ul>
        <div class="pt-5">
            <div class="panel">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">تفاصيل الدور: {{ $role->display_name }}</h5>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-primary">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="ltr:mr-2 rtl:ml-2">
                                <path d="M15.2869 3.15178L14.3601 4.07866L5.83882 12.5999L5.83881 12.5999C5.26166 13.1771 4.97308 13.4656 4.7249 13.7838C4.43213 14.1592 4.18114 14.5653 3.97634 14.995C3.80273 15.3593 3.67368 15.7465 3.41556 16.5208L2.32181 19.8021L2.05445 20.6042C1.92743 20.9852 2.0266 21.4053 2.31063 21.6894C2.59466 21.9734 3.01478 22.0726 3.39584 21.9456L4.19792 21.6782L7.47918 20.5844L7.47919 20.5844C8.25353 20.3263 8.6407 20.1973 9.00498 20.0237C9.43469 19.8189 9.84082 19.5679 10.2162 19.2751C10.5344 19.0269 10.8229 18.7383 11.4001 18.1612L11.4001 18.1612L19.9213 9.63993L20.8482 8.71306C22.3839 7.17735 22.3839 4.68748 20.8482 3.15178C19.3125 1.61607 16.8226 1.61607 15.2869 3.15178Z" stroke="currentColor" stroke-width="1.5" />
                                <path opacity="0.5" d="M14.36 4.07812C14.36 4.07812 14.4759 6.04774 16.2138 7.78564C17.9517 9.52354 19.9213 9.6394 19.9213 9.6394M4.19789 21.6777L2.32178 19.8015" stroke="currentColor" stroke-width="1.5" />
                            </svg>
                            تعديل
                        </a>
                        <a href="{{ route('roles.index') }}" class="btn btn-outline-primary">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="ltr:mr-2 rtl:ml-2">
                                <path d="M9.5 7L4.5 12L9.5 17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path opacity="0.5" d="M4.5 12L19.5 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            العودة
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="mb-6">
                            <h6 class="text-lg font-semibold mb-2">معلومات الدور</h6>
                            <div class="border rounded-md p-4 space-y-4">
                                <div class="flex flex-col">
                                    <span class="text-white-dark mb-1">الاسم:</span>
                                    <span class="font-semibold">{{ $role->name }}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-white-dark mb-1">الاسم المعروض:</span>
                                    <span class="font-semibold">{{ $role->display_name }}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-white-dark mb-1">الوصف:</span>
                                    <span>{{ $role->description ?: 'لا يوجد وصف' }}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-white-dark mb-1">عدد المستخدمين:</span>
                                    <span class="font-semibold">{{ $role->users->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="mb-6">
                            <h6 class="text-lg font-semibold mb-2">الصلاحيات</h6>
                            <div class="border rounded-md p-4">
                                @if($role->permissions->count() > 0)
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($role->permissions as $permission)
                                            <span class="badge badge-outline-primary">{{ $permission->display_name }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-white-dark">لا توجد صلاحيات مرتبطة بهذا الدور</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <h6 class="text-lg font-semibold mb-2">المستخدمون في هذا الدور</h6>
                    <div class="border rounded-md p-4">
                        @if($role->users->count() > 0)
                            <div class="table-responsive">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>الاسم</th>
                                            <th>البريد الإلكتروني</th>
                                            <th>الهاتف</th>
                                            <th>الحالة</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($role->users as $user)
                                            <tr>
                                                <td>{{ $user->id }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->phone ?: '-' }}</td>
                                                <td>
                                                    @if($user->is_active)
                                                        <span class="badge badge-outline-success">مفعل</span>
                                                    @else
                                                        <span class="badge badge-outline-danger">غير مفعل</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-white-dark">لا يوجد مستخدمون في هذا الدور</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout.admin>
