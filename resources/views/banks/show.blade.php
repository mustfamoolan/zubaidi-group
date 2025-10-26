<x-layout.company :company="$company">
    <script src="{{ asset('assets/js/number-formatter.js') }}"></script>
    <div class="space-y-6">
        <!-- معلومات المصرف -->
        <div class="panel">
            <div class="flex items-center justify-between mb-5">
                <h5 class="font-semibold text-lg dark:text-white-light">{{ $bank->name }}</h5>
                <a href="{{ route('companies.banks.edit', [$company, $bank]) }}" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ltr:mr-2 rtl:ml-2" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                    تعديل
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="border border-[#e0e6ed] dark:border-[#1b2e4b] rounded p-4">
                    <h6 class="text-[#3b3f5c] dark:text-white-light font-semibold text-sm mb-1">الرصيد الافتتاحي</h6>
                    <p class="text-primary text-2xl font-bold">{{ number_format($bank->opening_balance, 2) }} <span class="text-sm">{{ $bank->currency }}</span></p>
                </div>
                <div class="border border-[#e0e6ed] dark:border-[#1b2e4b] rounded p-4">
                    <h6 class="text-[#3b3f5c] dark:text-white-light font-semibold text-sm mb-1">الرصيد الحالي</h6>
                    <p class="text-success text-2xl font-bold">{{ number_format($bank->current_balance, 2) }} <span class="text-sm">{{ $bank->currency }}</span></p>
                </div>
                <div class="border border-[#e0e6ed] dark:border-[#1b2e4b] rounded p-4">
                    <h6 class="text-[#3b3f5c] dark:text-white-light font-semibold text-sm mb-1">العملة</h6>
                    <p class="text-2xl font-bold">{{ $bank->currency }}</p>
                </div>
            </div>
        </div>

        <!-- أزرار الإجراءات -->
        <div class="flex gap-2">
            <button type="button" class="btn btn-success gap-2" @click="$dispatch('open-modal', 'deposit-modal')">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd" />
                </svg>
                إيداع
            </button>
            <button type="button" class="btn btn-danger gap-2" @click="$dispatch('open-modal', 'withdraw-modal')">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd" transform="rotate(180 10 10)" />
                </svg>
                سحب
            </button>
        </div>

        <!-- سجل الحركات -->
        <div class="panel">
            <div class="flex items-center justify-between mb-5">
                <h5 class="font-semibold text-lg dark:text-white-light">سجل الحركات</h5>
            </div>

            <div class="table-responsive">
                <table class="table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>التاريخ</th>
                            <th>النوع</th>
                            <th>الوصف</th>
                            <th>المبلغ</th>
                            <th>الرصيد بعد العملية</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $runningBalance = $bank->opening_balance;
                        @endphp

                        @forelse($bank->transactions->reverse() as $transaction)
                            @php
                                if ($transaction->type === 'deposit') {
                                    $runningBalance += $transaction->amount;
                                } else {
                                    $runningBalance -= $transaction->amount;
                                }
                            @endphp
                            <tr>
                                <td>{{ $bank->transactions->count() - $loop->index }}</td>
                                <td>{{ \Carbon\Carbon::parse($transaction->date)->format('d M Y') }}</td>
                                <td>
                                    @if($transaction->type === 'deposit')
                                        <span class="badge bg-success">إيداع</span>
                                    @elseif($transaction->type === 'withdrawal')
                                        <span class="badge bg-warning">سحب</span>
                                    @else
                                        <span class="badge bg-danger">خصم فاتورة</span>
                                    @endif
                                </td>
                                <td>{{ $transaction->description ?? '-' }}</td>
                                <td>
                                    @if($transaction->type === 'deposit')
                                        <span class="text-success font-semibold">+ {{ number_format($transaction->amount, 2) }}</span>
                                    @else
                                        <span class="text-danger font-semibold">- {{ number_format($transaction->amount, 2) }}</span>
                                    @endif
                                    <span class="text-xs">{{ $bank->currency }}</span>
                                </td>
                                <td class="font-semibold">{{ number_format($runningBalance, 2) }} {{ $bank->currency }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">لا توجد حركات</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal إيداع -->
    <div x-data="{ open: false }"
         @open-modal.window="if ($event.detail === 'deposit-modal') open = true"
         @close-modal.window="open = false"
         @keydown.escape.window="open = false"
         x-show="open"
         class="fixed inset-0 z-[999] overflow-y-auto"
         style="display: none;">
        <div class="flex min-h-screen items-center justify-center px-4">
            <div @click="open = false" class="fixed inset-0 bg-[black]/60"></div>
            <div class="panel my-8 w-full max-w-lg overflow-hidden rounded-lg border-0 p-0">
                <div class="flex items-center justify-between bg-[#fbfbfb] px-5 py-3 dark:bg-[#121c2c]">
                    <h5 class="text-lg font-bold">إضافة عملية إيداع</h5>
                    <button @click="open = false" type="button" class="text-white-dark hover:text-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                <div class="p-5">
                    <form action="{{ route('companies.banks.deposit', [$company, $bank]) }}" method="POST">
                        @csrf
                        <div class="mb-5">
                            <label for="deposit_amount">المبلغ</label>
                            <div class="flex">
                                <input id="deposit_amount" name="amount" type="text" step="0.01" placeholder="0.00" class="form-input rounded-none number-input" required />
                                <div class="bg-[#eee] flex justify-center items-center ltr:rounded-r-md rtl:rounded-l-md px-3 font-semibold border ltr:border-l-0 rtl:border-r-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">{{ $bank->currency }}</div>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label for="deposit_date">التاريخ</label>
                            <input id="deposit_date" name="date" type="date" value="{{ date('Y-m-d') }}" class="form-input" required />
                        </div>

                        <div class="mb-5">
                            <label for="deposit_description">الوصف (اختياري)</label>
                            <textarea id="deposit_description" name="description" rows="3" class="form-textarea" placeholder="وصف العملية..."></textarea>
                        </div>

                        <div class="flex justify-end items-center mt-8">
                            <button @click="open = false" type="button" class="btn btn-outline-danger ltr:mr-2 rtl:ml-2">إلغاء</button>
                            <button type="submit" class="btn btn-success">إضافة إيداع</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal سحب -->
    <div x-data="{ open: false }"
         @open-modal.window="if ($event.detail === 'withdraw-modal') open = true"
         @close-modal.window="open = false"
         @keydown.escape.window="open = false"
         x-show="open"
         class="fixed inset-0 z-[999] overflow-y-auto"
         style="display: none;">
        <div class="flex min-h-screen items-center justify-center px-4">
            <div @click="open = false" class="fixed inset-0 bg-[black]/60"></div>
            <div class="panel my-8 w-full max-w-lg overflow-hidden rounded-lg border-0 p-0">
                <div class="flex items-center justify-between bg-[#fbfbfb] px-5 py-3 dark:bg-[#121c2c]">
                    <h5 class="text-lg font-bold">إضافة عملية سحب</h5>
                    <button @click="open = false" type="button" class="text-white-dark hover:text-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                <div class="p-5">
                    <form action="{{ route('companies.banks.withdraw', [$company, $bank]) }}" method="POST">
                        @csrf
                        <div class="mb-5">
                            <label for="withdraw_amount">المبلغ</label>
                            <div class="flex">
                                <input id="withdraw_amount" name="amount" type="text" step="0.01" placeholder="0.00" class="form-input rounded-none number-input" max="{{ $bank->current_balance }}" required />
                                <div class="bg-[#eee] flex justify-center items-center ltr:rounded-r-md rtl:rounded-l-md px-3 font-semibold border ltr:border-l-0 rtl:border-r-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">{{ $bank->currency }}</div>
                            </div>
                            <small class="text-white-dark">الرصيد المتاح: {{ number_format($bank->current_balance, 2) }} {{ $bank->currency }}</small>
                        </div>

                        <div class="mb-5">
                            <label for="withdraw_date">التاريخ</label>
                            <input id="withdraw_date" name="date" type="date" value="{{ date('Y-m-d') }}" class="form-input" required />
                        </div>

                        <div class="mb-5">
                            <label for="withdraw_description">الوصف (اختياري)</label>
                            <textarea id="withdraw_description" name="description" rows="3" class="form-textarea" placeholder="وصف العملية..."></textarea>
                        </div>

                        <div class="flex justify-end items-center mt-8">
                            <button @click="open = false" type="button" class="btn btn-outline-danger ltr:mr-2 rtl:ml-2">إلغاء</button>
                            <button type="submit" class="btn btn-danger">إضافة سحب</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout.company>

