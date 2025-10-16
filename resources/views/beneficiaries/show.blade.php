<x-layout.company :company="$company">
    <div x-data="beneficiaryShow">
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{ route('companies.show', $company) }}" class="text-primary hover:underline">{{ $company->name }}</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <a href="{{ route('companies.beneficiaries.index', $company) }}" class="text-primary hover:underline">المستفيدين</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>{{ $beneficiary->name }}</span>
            </li>
        </ul>
        <div class="pt-5">
            <!-- معلومات المستفيد -->
            <div class="panel mb-5">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">معلومات المستفيد</h5>
                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                        <a href="{{ route('companies.beneficiaries.edit', [$company, $beneficiary]) }}" class="btn btn-outline-primary btn-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ltr:mr-1 rtl:ml-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                            تعديل
                        </a>
                        <button type="button" class="btn btn-outline-danger btn-sm" @click="deleteBeneficiary({{ $beneficiary->id }})">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ltr:mr-1 rtl:ml-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3,6 5,6 21,6"></polyline>
                                <path d="M19,6v14a2,2 0 0,1 -2,2H7a2,2 0 0,1 -2,-2V6m3,0V4a2,2 0 0,1 2,-2h4a2,2 0 0,1 2,2v2"></path>
                            </svg>
                            حذف
                        </button>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="font-semibold text-gray-600 dark:text-gray-400">اسم المستفيد</label>
                        <p class="text-lg">{{ $beneficiary->name }}</p>
                    </div>
                    <div>
                        <label class="font-semibold text-gray-600 dark:text-gray-400">تاريخ الإنشاء</label>
                        <p class="text-lg">{{ $beneficiary->created_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- الإحصائيات -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-5">
                <div class="panel">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-primary/10 text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="px-4 flex-1">
                            <div class="text-sm text-gray-500 dark:text-gray-400">إجمالي الفواتير</div>
                            <div class="text-lg font-semibold">{{ $statistics['total_invoices'] }}</div>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-success/10 text-success">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="px-4 flex-1">
                            <div class="text-sm text-gray-500 dark:text-gray-400">الفواتير المدفوعة</div>
                            <div class="text-lg font-semibold">{{ $statistics['paid_invoices'] }}</div>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-warning/10 text-warning">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="px-4 flex-1">
                            <div class="text-sm text-gray-500 dark:text-gray-400">الفواتير غير المدفوعة</div>
                            <div class="text-lg font-semibold">{{ $statistics['unpaid_invoices'] }}</div>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-info/10 text-info">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                        </div>
                        <div class="px-4 flex-1">
                            <div class="text-sm text-gray-500 dark:text-gray-400">إجمالي المبلغ</div>
                            <div class="text-lg font-semibold">{{ number_format($statistics['total_amount'], 2) }} دينار</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- تفاصيل المبالغ -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                <div class="panel">
                    <h6 class="font-semibold text-lg mb-4">المبالغ المدفوعة</h6>
                    <div class="text-2xl font-bold text-success">{{ number_format($statistics['paid_amount'], 2) }} دينار</div>
                    <div class="text-sm text-gray-500 mt-1">{{ $statistics['paid_invoices'] }} فاتورة</div>
                </div>
                <div class="panel">
                    <h6 class="font-semibold text-lg mb-4">المبالغ غير المدفوعة</h6>
                    <div class="text-2xl font-bold text-warning">{{ number_format($statistics['unpaid_amount'], 2) }} دينار</div>
                    <div class="text-sm text-gray-500 mt-1">{{ $statistics['unpaid_invoices'] }} فاتورة</div>
                </div>
            </div>

            <!-- قائمة الفواتير -->
            <div class="panel">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">فواتير المستفيد</h5>
                </div>
                <div class="table-responsive">
                    <table class="table-hover">
                        <thead>
                            <tr>
                                <th>رقم الفاتورة</th>
                                <th>المبلغ (دولار)</th>
                                <th>المبلغ الإجمالي (دينار)</th>
                                <th>المصرف</th>
                                <th>التاريخ</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($beneficiary->invoices as $invoice)
                                <tr>
                                    <td>
                                        <div class="font-semibold">{{ $invoice->invoice_number }}</div>
                                    </td>
                                    <td>
                                        <div class="font-semibold">${{ number_format($invoice->amount_usd ?? 0, 2) }}</div>
                                    </td>
                                    <td>
                                        <div class="font-semibold">{{ number_format($invoice->total_amount_iqd ?? $invoice->amount, 2) }} دينار</div>
                                    </td>
                                    <td>
                                        <div class="font-semibold">{{ $invoice->bank->name ?? 'غير محدد' }}</div>
                                    </td>
                                    <td>
                                        <div class="font-semibold">{{ $invoice->invoice_date->format('d M Y') }}</div>
                                    </td>
                                    <td>
                                        @if ($invoice->status === 'paid')
                                            <span class="badge bg-success">مدفوعة</span>
                                        @else
                                            <span class="badge bg-warning">غير مدفوعة</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                            <a href="{{ route('companies.invoices.show', [$company, $invoice]) }}" class="btn btn-outline-primary btn-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>
                                            <a href="{{ route('companies.invoices.edit', [$company, $invoice]) }}" class="btn btn-outline-info btn-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-gray-500 dark:text-gray-400">لا توجد فواتير لهذا المستفيد</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal حذف المستفيد -->
    <div x-data="{ open: false }"
         @open-modal.window="if ($event.detail === 'delete-beneficiary-modal') open = true"
         @close-modal.window="open = false"
         @keydown.escape.window="open = false"
         x-show="open"
         class="fixed inset-0 z-[999] overflow-y-auto"
         style="display: none;">
        <div class="flex min-h-screen items-center justify-center px-4">
            <div @click="open = false" class="fixed inset-0 bg-[black]/60"></div>
            <div class="panel my-8 w-full max-w-lg overflow-hidden rounded-lg border-0 p-0">
                <div class="flex items-center justify-between bg-[#fbfbfb] px-5 py-3 dark:bg-[#121c2c]">
                    <h5 class="text-lg font-bold">تأكيد حذف المستفيد</h5>
                    <button @click="open = false" type="button" class="text-white-dark hover:text-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                <div class="p-5">
                    <div class="mb-5">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 bg-danger/20 rounded-full flex items-center justify-center">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.5" d="M9.17065 4C9.58249 2.83481 10.6937 2 11.9999 2C13.3062 2 14.4174 2.83481 14.8292 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                    <path d="M20.5001 6H3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                    <path d="M18.8334 8.5L18.3735 15.3991C18.1965 18.054 18.108 19.3815 17.243 20.1907C16.378 21 15.0476 21 12.3868 21H11.6134C8.9526 21 7.6222 21 6.75719 20.1907C5.89218 19.3815 5.80368 18.054 5.62669 15.3991L5.16675 8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                </svg>
                            </div>
                            <div>
                                <h6 class="font-semibold text-lg">حذف المستفيد: {{ $beneficiary->name }}</h6>
                                <p class="text-gray-500">هذا الإجراء لا يمكن التراجع عنه</p>
                            </div>
                        </div>

                        @if ($beneficiary->invoices()->count() > 0)
                            <div class="bg-danger/10 border border-danger/20 rounded p-4 mb-4">
                                <div class="flex items-start gap-3">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-danger mt-0.5">
                                        <path d="M12 9V13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M12 17H12.01" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M7.5 3.20461C8.82378 2.43828 10.3607 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 10.3607 2.43828 8.82378 3.20461 7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <div>
                                        <h6 class="font-semibold text-danger">تحذير!</h6>
                                        <p class="text-sm text-gray-600">لا يمكن حذف هذا المستفيد لأنه مرتبط بـ {{ $beneficiary->invoices()->count() }} فاتورة.</p>
                                        <p class="text-sm text-gray-600 mt-1">يجب حذف أو تعديل الفواتير المرتبطة أولاً.</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="bg-warning/10 border border-warning/20 rounded p-4 mb-4">
                                <div class="flex items-start gap-3">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-warning mt-0.5">
                                        <path d="M12 9V13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M12 17H12.01" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M7.5 3.20461C8.82378 2.43828 10.3607 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 10.3607 2.43828 8.82378 3.20461 7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <div>
                                        <h6 class="font-semibold text-warning">تحذير!</h6>
                                        <p class="text-sm text-gray-600">سيتم حذف المستفيد نهائياً من النظام.</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if ($beneficiary->invoices()->count() == 0)
                        <form action="{{ route('companies.beneficiaries.destroy', [$company, $beneficiary]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <div class="flex items-center justify-end gap-3">
                                <button type="button" @click="open = false" class="btn btn-outline-secondary">إلغاء</button>
                                <button type="submit" class="btn btn-danger">حذف المستفيد</button>
                            </div>
                        </form>
                    @else
                        <div class="flex items-center justify-end gap-3">
                            <button type="button" @click="open = false" class="btn btn-outline-secondary">إغلاق</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("alpine:init", () => {
            Alpine.data('beneficiaryShow', () => ({
                deleteBeneficiary(id) {
                    this.$dispatch('open-modal', 'delete-beneficiary-modal');
                }
            }))
        })
    </script>
</x-layout.company>
