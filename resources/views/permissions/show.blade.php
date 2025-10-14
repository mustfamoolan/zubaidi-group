<x-layout.admin>
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="javascript:;" class="text-primary hover:underline">لوحة التحكم</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <a href="{{ route('permissions.index') }}" class="text-primary hover:underline">الصلاحيات</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>عرض الصلاحية</span>
            </li>
        </ul>
        <div class="pt-5">
            <div class="panel">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">تفاصيل الصلاحية: {{ $permission->display_name }}</h5>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('permissions.index') }}" class="btn btn-outline-primary">
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
                            <h6 class="text-lg font-semibold mb-2">معلومات الصلاحية</h6>
                            <div class="border rounded-md p-4 space-y-4">
                                <div class="flex flex-col">
                                    <span class="text-white-dark mb-1">الاسم:</span>
                                    <span class="font-semibold">{{ $permission->name }}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-white-dark mb-1">الاسم المعروض:</span>
                                    <span class="font-semibold">{{ $permission->display_name }}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-white-dark mb-1">المجموعة:</span>
                                    <span class="font-semibold">{{ ucfirst($permission->group) }}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-white-dark mb-1">الوصف:</span>
                                    <span>{{ $permission->description ?: 'لا يوجد وصف' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="mb-6">
                            <h6 class="text-lg font-semibold mb-2">الأدوار التي تمتلك هذه الصلاحية</h6>
                            <div class="border rounded-md p-4">
                                @if($permission->roles->count() > 0)
                                    <div class="space-y-2">
                                        @foreach($permission->roles as $role)
                                            <div class="flex items-center justify-between">
                                                <span>{{ $role->display_name }}</span>
                                                <a href="{{ route('roles.show', $role->id) }}" class="btn btn-sm btn-outline-primary">عرض</a>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-white-dark">لا توجد أدوار تمتلك هذه الصلاحية</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout.admin>
