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
                <span>عرض المستخدم</span>
            </li>
        </ul>
        <div class="pt-5">
            <div class="panel">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">تفاصيل المستخدم</h5>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary">
                            <svg class="ltr:mr-2 rtl:ml-2" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15.2869 3.15178L14.3601 4.07866L5.83882 12.5999L5.83881 12.5999C5.26166 13.1771 4.97308 13.4656 4.7249 13.7838C4.43213 14.1592 4.18114 14.5653 3.97634 14.995C3.80273 15.3593 3.67368 15.7465 3.41556 16.5208L2.32181 19.8021L2.05445 20.6042C1.92743 20.9852 2.0266 21.4053 2.31063 21.6894C2.59466 21.9734 3.01478 22.0726 3.39584 21.9456L4.19792 21.6782L7.47918 20.5844L7.47919 20.5844C8.25353 20.3263 8.6407 20.1973 9.00498 20.0237C9.43469 19.8189 9.84082 19.5679 10.2162 19.2751C10.5344 19.0269 10.8229 18.7383 11.4001 18.1612L11.4001 18.1612L19.9213 9.63993L20.8482 8.71306C22.3839 7.17735 22.3839 4.68748 20.8482 3.15178C19.3125 1.61607 16.8226 1.61607 15.2869 3.15178Z" stroke="currentColor" stroke-width="1.5" />
                                <path opacity="0.5" d="M14.36 4.07812C14.36 4.07812 14.4759 6.04774 16.2138 7.78564C17.9517 9.52354 19.9213 9.6394 19.9213 9.6394M4.19789 21.6777L2.32178 19.8015" stroke="currentColor" stroke-width="1.5" />
                            </svg>
                            تعديل
                        </a>
                        <a href="{{ route('users.index') }}" class="btn btn-outline-danger">
                            <svg class="ltr:mr-2 rtl:ml-2" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13 15L17 19M17 15L13 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M6 15.0002L7 19.0002" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M9 15.0002L8 19.0002" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path opacity="0.5" d="M22 10.9998H2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M20 5H4C2.89543 5 2 5.89543 2 7V19C2 20.1046 2.89543 21 4 21H20C21.1046 21 22 20.1046 22 19V7C22 5.89543 21.1046 5 20 5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path opacity="0.5" d="M18 5.00019V3.00019C18 1.89562 17.1046 1.00019 16 1.00019H8C6.89543 1.00019 6 1.89562 6 3.00019V5.00019" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            العودة
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="panel">
                        <div class="flex items-center mb-5">
                            <h5 class="font-semibold text-lg dark:text-white-light">المعلومات الأساسية</h5>
                        </div>
                        <div class="space-y-5">
                            <div class="flex items-center">
                                <span class="w-1/4 font-semibold">الاسم:</span>
                                <span>{{ $user->name }}</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-1/4 font-semibold">البريد الإلكتروني:</span>
                                <span>{{ $user->email }}</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-1/4 font-semibold">رقم الهاتف:</span>
                                <span>{{ $user->phone ?: 'غير محدد' }}</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-1/4 font-semibold">الحالة:</span>
                                <span class="badge {{ $user->is_active ? 'badge-outline-success' : 'badge-outline-danger' }}">
                                    {{ $user->is_active ? 'مفعل' : 'غير مفعل' }}
                                </span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-1/4 font-semibold">تاريخ الإنشاء:</span>
                                <span>{{ $user->created_at->format('Y-m-d H:i') }}</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-1/4 font-semibold">آخر تحديث:</span>
                                <span>{{ $user->updated_at->format('Y-m-d H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="panel">
                        <div class="flex items-center mb-5">
                            <h5 class="font-semibold text-lg dark:text-white-light">معلومات الدور والصلاحيات</h5>
                        </div>
                        <div class="space-y-5">
                            <div class="flex items-center">
                                <span class="w-1/4 font-semibold">الدور:</span>
                                <span>{{ $user->role ? $user->role->display_name : 'غير محدد' }}</span>
                            </div>
                            @if($user->role)
                                <div>
                                    <span class="font-semibold">وصف الدور:</span>
                                    <p class="mt-2">{{ $user->role->description ?: 'لا يوجد وصف' }}</p>
                                </div>
                                <div>
                                    <span class="font-semibold">صلاحيات الدور:</span>
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        @forelse($user->role->permissions as $permission)
                                            <span class="badge badge-outline-primary">{{ $permission->display_name }}</span>
                                        @empty
                                            <span class="text-gray-500">لا توجد صلاحيات</span>
                                        @endforelse
                                    </div>
                                </div>
                                
                                <div>
                                    <span class="font-semibold">الصلاحيات المباشرة:</span>
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        @forelse($user->permissions as $permission)
                                            <span class="badge badge-outline-success">{{ $permission->display_name }}</span>
                                        @empty
                                            <span class="text-gray-500">لا توجد صلاحيات مباشرة</span>
                                        @endforelse
                                    </div>
                                </div>
                                
                                <div>
                                    <span class="font-semibold">جميع الصلاحيات:</span>
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        @forelse($allPermissions as $permission)
                                            <span class="badge badge-outline-info">{{ $permission->display_name }}</span>
                                        @empty
                                            <span class="text-gray-500">لا توجد صلاحيات</span>
                                        @endforelse
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout.custom-default>
