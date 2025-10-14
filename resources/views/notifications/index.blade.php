@php
    // تحديد الشركة من آخر شركة زارها المستخدم (من الـ session أو من آخر إشعار)
    $currentCompany = null;
    if (session('last_company_id')) {
        $currentCompany = \App\Models\Company::find(session('last_company_id'));
    } elseif ($notifications->first() && $notifications->first()->notifiable) {
        $currentCompany = $notifications->first()->notifiable->company;
        if ($currentCompany) {
            session(['last_company_id' => $currentCompany->id]);
        }
    }
@endphp

@if($currentCompany)
    <x-layout.company :company="$currentCompany">
        <div>
            <ul class="flex space-x-2 rtl:space-x-reverse">
                <li>
                    <a href="{{ route('companies.index') }}" class="text-primary hover:underline">الشركات</a>
                </li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                    <a href="{{ route('companies.show', $currentCompany) }}" class="text-primary hover:underline">{{ $currentCompany->name }}</a>
                </li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                    <span>الإشعارات</span>
                </li>
            </ul>

            <div class="pt-5">
                <!-- عرض الرسائل -->
                @if (session('success'))
                    <div class="alert alert-success mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="panel">
                    <div class="flex items-center justify-between mb-5">
                        <h5 class="font-semibold text-lg dark:text-white-light">الإشعارات</h5>
                        <div class="flex items-center gap-2">
                            <span class="badge bg-primary">{{ $notifications->count() }} إشعار</span>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @forelse($notifications as $notification)
                            <div class="flex items-start gap-4 p-4 rounded-md {{ $notification->read_at ? 'bg-gray-100 dark:bg-gray-800' : 'bg-blue-50 dark:bg-blue-900/20' }} border {{ $notification->read_at ? 'border-gray-200 dark:border-gray-700' : 'border-blue-200 dark:border-blue-800' }}">
                                <div class="flex-shrink-0">
                                    @if($notification->type === 'received_reminder')
                                        <div class="w-12 h-12 rounded-full bg-warning flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                        </div>
                                    @elseif($notification->type === 'entry_reminder')
                                        <div class="w-12 h-12 rounded-full bg-info flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                        </div>
                                    @else
                                        <div class="w-12 h-12 rounded-full bg-primary flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex-1">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <p class="font-semibold text-dark dark:text-white-light mb-1">
                                                {{ $notification->message }}
                                            </p>
                                            <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
                                                <span>{{ $notification->created_at->diffForHumans() }}</span>
                                                @if($notification->sent_at)
                                                    <span class="flex items-center gap-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                        </svg>
                                                        تم إرسال البريد
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            @if(!$notification->read_at)
                                                <form action="{{ route('notifications.mark-as-read', $notification) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-primary" title="تحديد كمقروء">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif

                                            <form action="{{ route('notifications.resend-email', $notification) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-info" title="إعادة إرسال البريد">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                    </svg>
                                                </button>
                                            </form>

                                            @if($notification->notifiable)
                                                <a href="{{ route('companies.shipments.show', [$notification->notifiable->company_id, $notification->notifiable_id]) }}" class="btn btn-sm btn-outline-primary" title="عرض الشحنة">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <p class="text-gray-500 dark:text-gray-400 text-lg">لا توجد إشعارات</p>
                            </div>
                        @endforelse
                    </div>

                    @if($notifications->count() > 0)
                        <div class="mt-5">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </x-layout.company>
@else
    <x-layout.default>
        <div>
            <ul class="flex space-x-2 rtl:space-x-reverse">
                <li>
                    <a href="{{ route('companies.index') }}" class="text-primary hover:underline">الشركات</a>
                </li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                    <span>الإشعارات</span>
                </li>
            </ul>

            <div class="pt-5">
                <!-- عرض الرسائل -->
                @if (session('success'))
                    <div class="alert alert-success mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="panel">
                    <div class="flex items-center justify-between mb-5">
                        <h5 class="font-semibold text-lg dark:text-white-light">الإشعارات</h5>
                        <div class="flex items-center gap-2">
                            <span class="badge bg-primary">{{ $notifications->count() }} إشعار</span>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @forelse($notifications as $notification)
                            <div class="flex items-start gap-4 p-4 rounded-md {{ $notification->read_at ? 'bg-gray-100 dark:bg-gray-800' : 'bg-blue-50 dark:bg-blue-900/20' }} border {{ $notification->read_at ? 'border-gray-200 dark:border-gray-700' : 'border-blue-200 dark:border-blue-800' }}">
                                <div class="flex-shrink-0">
                                    @if($notification->type === 'received_reminder')
                                        <div class="w-12 h-12 rounded-full bg-warning flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                        </div>
                                    @elseif($notification->type === 'entry_reminder')
                                        <div class="w-12 h-12 rounded-full bg-info flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                        </div>
                                    @else
                                        <div class="w-12 h-12 rounded-full bg-primary flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex-1">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <p class="font-semibold text-dark dark:text-white-light mb-1">
                                                {{ $notification->message }}
                                            </p>
                                            <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
                                                <span>{{ $notification->created_at->diffForHumans() }}</span>
                                                @if($notification->sent_at)
                                                    <span class="flex items-center gap-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                        </svg>
                                                        تم إرسال البريد
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            @if(!$notification->read_at)
                                                <form action="{{ route('notifications.mark-as-read', $notification) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-primary" title="تحديد كمقروء">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif

                                            <form action="{{ route('notifications.resend-email', $notification) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-info" title="إعادة إرسال البريد">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                    </svg>
                                                </button>
                                            </form>

                                            @if($notification->notifiable)
                                                <a href="{{ route('companies.shipments.show', [$notification->notifiable->company_id, $notification->notifiable_id]) }}" class="btn btn-sm btn-outline-primary" title="عرض الشحنة">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <p class="text-gray-500 dark:text-gray-400 text-lg">لا توجد إشعارات</p>
                            </div>
                        @endforelse
                    </div>

                    @if($notifications->count() > 0)
                        <div class="mt-5">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </x-layout.default>
@endif
