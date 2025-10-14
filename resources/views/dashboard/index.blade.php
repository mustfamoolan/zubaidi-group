<x-layout.default>
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="javascript:;" class="text-primary hover:underline">لوحة التحكم</a>
            </li>
        </ul>
        <div class="pt-5">
            <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-2">
                <div class="panel h-full">
                    <div class="flex items-center justify-between mb-5">
                        <h5 class="font-semibold text-lg dark:text-white-light">مرحباً بك في لوحة التحكم</h5>
                    </div>
                    <div class="mb-5">
                        <p>مرحباً {{ Auth::user()->name }}، أنت مسجل الدخول الآن.</p>
                        @if(session('selected_company_id'))
                            @php
                                $company = \App\Models\Company::find(session('selected_company_id'));
                            @endphp
                            @if($company)
                                <div class="mt-4 p-4 bg-primary/10 rounded-md">
                                    <h6 class="font-semibold mb-2">الشركة الحالية: {{ $company->name }}</h6>
                                    @if($company->image)
                                        <img src="{{ asset('storage/' . $company->image) }}" alt="{{ $company->name }}" class="w-32 h-32 object-cover rounded-md mt-2" />
                                    @endif
                                </div>
                            @endif
                        @endif
                    </div>
                    <div class="mb-5">
                        <h6 class="font-semibold text-base dark:text-white-light mb-2">الروابط السريعة</h6>
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('companies.index') }}" class="btn btn-primary">إدارة الشركات</a>
                            @can('users.view')
                                <a href="{{ route('users.index') }}" class="btn btn-info">إدارة المستخدمين</a>
                            @endcan
                            @can('roles.view')
                                <a href="{{ route('roles.index') }}" class="btn btn-warning">إدارة الأدوار</a>
                            @endcan
                            @can('permissions.view')
                                <a href="{{ route('permissions.index') }}" class="btn btn-secondary">إدارة الصلاحيات</a>
                            @endcan
                        </div>
                    </div>
                </div>

                <div class="panel h-full">
                    <div class="flex items-center justify-between mb-5">
                        <h5 class="font-semibold text-lg dark:text-white-light">معلومات الحساب</h5>
                    </div>
                    <div class="mb-5">
                        <div class="space-y-4">
                            <div class="border-b pb-2">
                                <span class="font-semibold">الاسم:</span> {{ Auth::user()->name }}
                            </div>
                            <div class="border-b pb-2">
                                <span class="font-semibold">البريد الإلكتروني:</span> {{ Auth::user()->email }}
                            </div>
                            <div class="border-b pb-2">
                                <span class="font-semibold">الهاتف:</span> {{ Auth::user()->phone ?: 'غير متوفر' }}
                            </div>
                            <div class="border-b pb-2">
                                <span class="font-semibold">الدور:</span> {{ Auth::user()->role->display_name }}
                            </div>
                            <div class="border-b pb-2">
                                <span class="font-semibold">الشركة:</span>
                                @if(Auth::user()->company)
                                    {{ Auth::user()->company->name }}
                                @else
                                    غير متوفر
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout.default>
