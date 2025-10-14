<x-layout.company :company="$company">
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{ route('companies.show', $company) }}" class="text-primary hover:underline">{{ $company->name }}</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>المصارف</span>
            </li>
        </ul>

        <div class="pt-5">
            <div class="panel">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">قائمة المصارف</h5>
                    <a href="{{ route('companies.banks.create', $company) }}" class="btn btn-primary">
                        <svg class="ltr:mr-2 rtl:ml-2" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 4V20M20 12H4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                        إضافة مصرف جديد
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table-hover">
                        <thead>
                            <tr>
                                <th>اسم المصرف</th>
                                <th>المبلغ الافتتاحي</th>
                                <th>الرصيد الحالي</th>
                                <th>العملة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($banks as $bank)
                                <tr>
                                    <td>{{ $bank->name }}</td>
                                    <td>{{ number_format($bank->opening_balance, 2) }}</td>
                                    <td class="font-semibold {{ $bank->current_balance >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($bank->current_balance, 2) }}
                                    </td>
                                    <td>{{ $bank->currency }}</td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('companies.banks.show', [$company, $bank]) }}" class="btn btn-sm btn-outline-info">
                                                عرض
                                            </a>
                                            <a href="{{ route('companies.banks.edit', [$company, $bank]) }}" class="btn btn-sm btn-outline-primary">
                                                تعديل
                                            </a>
                                            <form action="{{ route('companies.banks.destroy', [$company, $bank]) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا المصرف؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    حذف
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">لا توجد مصارف مضافة</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layout.company>

