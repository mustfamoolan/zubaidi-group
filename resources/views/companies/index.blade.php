<x-layout.custom-default>
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="javascript:;" class="text-primary hover:underline">لوحة التحكم</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>الشركات</span>
            </li>
        </ul>
        <div class="pt-5">
            <div class="panel">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">قائمة الشركات</h5>
                    <a href="{{ route('companies.create') }}" class="btn btn-primary">
                        <svg class="ltr:mr-2 rtl:ml-2" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 4V20M20 12H4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                        إضافة شركة جديدة
                    </a>
                </div>

                @if (session('success'))
                    <div class="alert alert-success mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($companies as $company)
                        <div class="border border-gray-500/20 rounded-md shadow-[rgb(31_45_61_/_10%)_0px_2px_10px_1px] dark:shadow-[0_2px_11px_0_rgb(6_8_24_/_39%)] p-6 pt-12 mt-8 relative">
                            <div class="bg-primary absolute text-white-light left-6 -top-8 w-16 h-16 rounded-md flex items-center justify-center mb-5 mx-auto">
                                @if($company->image)
                                    <img src="{{ asset('storage/' . $company->image) }}" alt="{{ $company->name }}" class="w-full h-full object-cover rounded-md">
                                @else
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M2 8.5H22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                        <path d="M6 16.5H8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                        <path d="M10.5 16.5H16.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                        <path d="M6.5 4V6.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                        <path d="M17.5 4V6.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                        <path d="M7.5 2.5H16.5C19.5 2.5 21 4 21 7V16.5C21 19.5 19.5 21 16.5 21H7.5C4.5 21 3 19.5 3 16.5V7C3 4 4.5 2.5 7.5 2.5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                    </svg>
                                @endif
                            </div>
                            <h5 class="text-dark text-lg font-semibold mb-3.5 dark:text-white-light">{{ $company->name }}</h5>
                            <p class="text-white-dark text-[15px] mb-3.5">تاريخ الإنشاء: {{ $company->created_at->format('Y-m-d') }}</p>
                            <div class="flex items-center justify-between">
                                <a href="{{ route('companies.show', $company->id) }}" class="text-primary font-semibold hover:underline group">
                                    الدخول
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="ltr:inline-block rtl:hidden">
                                        <path d="M4 12H20M20 12L14 6M20 12L14 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="rtl:inline-block ltr:hidden">
                                        <path d="M20 12H4M4 12L10 6M4 12L10 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </a>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('companies.edit', $company->id) }}" class="btn btn-sm btn-outline-primary p-2">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M15.2869 3.15178L14.3601 4.07866L5.83882 12.5999L5.83881 12.5999C5.26166 13.1771 4.97308 13.4656 4.7249 13.7838C4.43213 14.1592 4.18114 14.5653 3.97634 14.995C3.80273 15.3593 3.67368 15.7465 3.41556 16.5208L2.32181 19.8021L2.05445 20.6042C1.92743 20.9852 2.0266 21.4053 2.31063 21.6894C2.59466 21.9734 3.01478 22.0726 3.39584 21.9456L4.19792 21.6782L7.47918 20.5844L7.47919 20.5844C8.25353 20.3263 8.6407 20.1973 9.00498 20.0237C9.43469 19.8189 9.84082 19.5679 10.2162 19.2751C10.5344 19.0269 10.8229 18.7383 11.4001 18.1612L11.4001 18.1612L19.9213 9.63993L20.8482 8.71306C22.3839 7.17735 22.3839 4.68748 20.8482 3.15178C19.3125 1.61607 16.8226 1.61607 15.2869 3.15178Z" stroke="currentColor" stroke-width="1.5" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('companies.destroy', $company->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه الشركة؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger p-2">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path opacity="0.5" d="M9.17065 4C9.58249 2.83481 10.6937 2 11.9999 2C13.3062 2 14.4174 2.83481 14.8292 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                                <path d="M20.5001 6H3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                                <path d="M18.8334 8.5L18.3735 15.3991C18.1965 18.054 18.108 19.3815 17.243 20.1907C16.378 21 15.0476 21 12.3868 21H11.6134C8.9526 21 7.6222 21 6.75719 20.1907C5.89218 19.3815 5.80368 18.054 5.62669 15.3991L5.16675 8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center p-4">
                            <p>لا توجد شركات</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-5">
                    {{ $companies->links() }}
                </div>
            </div>
        </div>
    </div>
</x-layout.custom-default>