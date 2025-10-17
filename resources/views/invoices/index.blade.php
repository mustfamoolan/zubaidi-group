<x-layout.company :company="$company">
    <link rel='stylesheet' type='text/css' href='{{ Vite::asset('resources/css/nice-select2.css') }}'>
    <style>
        .nice-select .list {
            max-height: 300px !important;
            overflow-y: auto !important;
        }
        .nice-select .list::-webkit-scrollbar {
            width: 8px;
        }
        .nice-select .list::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        .nice-select .list::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        .nice-select .list::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        .nice-select .list .option {
            padding: 8px 12px !important;
            font-size: 14px !important;
        }
        .nice-select .list .option:hover {
            background-color: #f8f9fa !important;
        }

        /* تحسين سكرول modal */
        .modal-scroll::-webkit-scrollbar {
            width: 8px;
        }
        .modal-scroll::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        .modal-scroll::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        .modal-scroll::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>

    <div x-data="invoiceList" x-init="checkOpenModal()">
        <script src="/assets/js/simple-datatables.js"></script>

        <div class="panel px-0 border-[#e0e6ed] dark:border-[#1b2e4b]">
            <div class="px-5">
                <div class="md:absolute md:top-5 ltr:md:left-5 rtl:md:right-5">
                    <div class="flex items-center gap-2 mb-5">
                        <button type="button" class="btn btn-danger gap-2" @click="deleteRow()">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                <path d="M20.5001 6H3.5" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round"></path>
                                <path
                                    d="M18.8334 8.5L18.3735 15.3991C18.1965 18.054 18.108 19.3815 17.243 20.1907C16.378 21 15.0476 21 12.3868 21H11.6134C8.9526 21 7.6222 21 6.75719 20.1907C5.89218 19.3815 5.80368 18.054 5.62669 15.3991L5.16675 8.5"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                <path opacity="0.5" d="M9.5 11L10 16" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round"></path>
                                <path opacity="0.5" d="M14.5 11L14 16" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round"></path>
                                <path opacity="0.5"
                                    d="M6.5 6C6.55588 6 6.58382 6 6.60915 5.99936C7.43259 5.97849 8.15902 5.45491 8.43922 4.68032C8.44784 4.65649 8.45667 4.62999 8.47434 4.57697L8.57143 4.28571C8.65431 4.03708 8.69575 3.91276 8.75071 3.8072C8.97001 3.38607 9.37574 3.09364 9.84461 3.01877C9.96213 3 10.0932 3 10.3553 3H13.6447C13.9068 3 14.0379 3 14.1554 3.01877C14.6243 3.09364 15.03 3.38607 15.2493 3.8072C15.3043 3.91276 15.3457 4.03708 15.4286 4.28571L15.5257 4.57697C15.5433 4.62992 15.5522 4.65651 15.5608 4.68032C15.841 5.45491 16.5674 5.97849 17.3909 5.99936C17.4162 6 17.4441 6 17.5 6"
                                    stroke="currentColor" stroke-width="1.5"></path>
                            </svg>
                            حذف
                        </button>
                        <button type="button" class="btn btn-primary gap-2" @click="$dispatch('open-modal', 'add-invoice-modal')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" class="w-5 h-5">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            إضافة فاتورة جديدة
                        </button>
                    </div>
                </div>
            </div>
            <div class="invoice-table">
                <table id="myTable" class="whitespace-nowrap"></table>
            </div>
        </div>
    </div>

    <!-- Modal إضافة فاتورة -->
    <div x-data="{ open: false }"
         @open-modal.window="if ($event.detail === 'add-invoice-modal') open = true"
         @close-modal.window="open = false"
         @keydown.escape.window="open = false"
         x-show="open"
         class="fixed inset-0 z-[999] overflow-y-auto"
         style="display: none;">
        <div class="flex min-h-screen items-center justify-center px-4 py-8">
            <div @click="open = false" class="fixed inset-0 bg-[black]/60"></div>
            <div class="panel my-8 w-full max-w-4xl max-h-[90vh] overflow-hidden rounded-lg border-0 p-0">
                <div class="flex items-center justify-between bg-[#fbfbfb] px-5 py-3 dark:bg-[#121c2c]">
                    <h5 class="text-lg font-bold">إضافة فاتورة جديدة</h5>
                    <button @click="open = false" type="button" class="text-white-dark hover:text-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                <div class="p-5 overflow-y-auto max-h-[calc(90vh-120px)] modal-scroll">
                    <form action="{{ route('companies.invoices.store', $company) }}" method="POST">
                        @csrf
                        <div class="mb-5">
                            <label for="invoice_number">رقم الفاتورة</label>
                            <div class="flex">
                                <div class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 font-semibold border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">#</div>
                                <input id="invoice_number" name="invoice_number" type="text" placeholder="INV-000001" class="form-input ltr:rounded-l-none rtl:rounded-r-none" required />
                            </div>
                        </div>

                        <div class="mb-5">
                            <label for="amount_usd">المبلغ (بالدولار)</label>
                            <div class="flex">
                                <input id="amount_usd" name="amount_usd" type="number" step="0.01" placeholder="100.00" class="form-input rounded-none" required oninput="calculateTotalAmount()" />
                                <div class="bg-[#eee] flex justify-center items-center ltr:rounded-r-md rtl:rounded-l-md px-3 font-semibold border ltr:border-l-0 rtl:border-r-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">USD</div>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label for="exchange_rate">سعر الصرف</label>
                            <div class="flex">
                                <input id="exchange_rate" name="exchange_rate" type="number" step="0.01" placeholder="1500.00" class="form-input rounded-none" required oninput="calculateTotalAmount()" />
                                <div class="bg-[#eee] flex justify-center items-center ltr:rounded-r-md rtl:rounded-l-md px-3 font-semibold border ltr:border-l-0 rtl:border-r-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">دينار/دولار</div>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label for="bank_commission_percent">عمولة المصرف (نسبة مئوية)</label>
                            <div class="flex">
                                <input id="bank_commission_percent" name="bank_commission_percent" type="number" step="0.01" min="0" max="100" placeholder="0.00" class="form-input rounded-none" value="0" oninput="calculateTotalAmount()" />
                                <div class="bg-[#eee] flex justify-center items-center ltr:rounded-r-md rtl:rounded-l-md px-3 font-semibold border ltr:border-l-0 rtl:border-r-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">%</div>
                            </div>
                            <!-- حقل مخفي لإرسال المبلغ المحسوب -->
                            <input type="hidden" id="bank_commission" name="bank_commission" value="0" />
                        </div>

                        <div class="mb-5">
                            <label for="total_amount_iqd">المبلغ الإجمالي (دينار عراقي)</label>
                            <div class="flex">
                                <input id="total_amount_iqd" name="total_amount_iqd" type="number" step="0.01" placeholder="0.00" class="form-input rounded-none bg-gray-100" readonly />
                                <div class="bg-[#eee] flex justify-center items-center ltr:rounded-r-md rtl:rounded-l-md px-3 font-semibold border ltr:border-l-0 rtl:border-r-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">دينار</div>
                            </div>
                            <small class="text-gray-500">يتم حسابه تلقائياً: (المبلغ بالدولار × سعر الصرف) + عمولة المصرف</small>
                        </div>

                        <div class="mb-5">
                            <label for="bank_id">المصرف</label>
                            <select id="bank_id" name="bank_id" class="form-select" required>
                                <option value="">اختر مصرف</option>
                                @foreach($banks as $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->name }} ({{ number_format($bank->current_balance, 2) }} {{ $bank->currency }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-5">
                            <label for="invoice_date">تاريخ الفاتورة</label>
                            <input id="invoice_date" name="invoice_date" type="date" class="form-input" required />
                        </div>

                        <div class="mb-5">
                            <label for="beneficiary_id">اسم المستفيد</label>
                            <select id="beneficiary_id" name="beneficiary_id" class="form-select" required>
                                <option value="">اختر المستفيد</option>
                                @foreach($beneficiaries as $beneficiary)
                                    <option value="{{ $beneficiary->id }}">{{ $beneficiary->name }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">
                                <a href="{{ route('companies.beneficiaries.create', $company) }}" class="text-primary hover:underline">إضافة مستفيد جديد</a>
                            </p>
                        </div>

                        <div class="mb-5">
                            <label for="shipments">الشحنات المرتبطة <span class="text-danger">*</span></label>
                            <select id="shipments" name="shipments[]" class="selectize" multiple placeholder="ابحث واختر الشحنات..." required>
                                @foreach($shipments as $shipment)
                                    <option value="{{ $shipment->id }}">
                                        {{ $shipment->container_number }} - {{ $shipment->policy_number }} ({{ $shipment->status === 'shipped' ? 'مشحون' : 'غير مشحون' }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-white-dark">اكتب للبحث عن الشحنات، يمكنك اختيار عدة شحنات</small>
                            @error('shipments')
                                <span class="text-danger text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex justify-end items-center mt-8">
                            <button @click="open = false" type="button" class="btn btn-outline-danger ltr:mr-2 rtl:ml-2">إلغاء</button>
                            <button type="submit" class="btn btn-primary">إضافة</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // دالة حساب المبلغ الإجمالي
        function calculateTotalAmount() {
            const amountUsd = parseFloat(document.getElementById('amount_usd')?.value || 0);
            const exchangeRate = parseFloat(document.getElementById('exchange_rate')?.value || 0);
            const commissionPercent = parseFloat(document.getElementById('bank_commission_percent')?.value || 0);

            // حساب المبلغ بالدينار
            const amountIqd = amountUsd * exchangeRate;

            // حساب عمولة المصرف من النسبة المئوية
            const bankCommission = (amountIqd * commissionPercent) / 100;

            // حساب المبلغ الإجمالي
            const totalAmount = amountIqd + bankCommission;

            // تحديث الحقول
            const commissionInput = document.getElementById('bank_commission');
            if (commissionInput) {
                commissionInput.value = bankCommission.toFixed(2);
            }

            const totalInput = document.getElementById('total_amount_iqd');
            if (totalInput) {
                totalInput.value = totalAmount.toFixed(2);
            }
        }

        document.addEventListener("alpine:init", () => {
            Alpine.data('invoiceList', () => ({
                selectedRows: [],
                items: [
                    @foreach($invoices as $invoice)
                    {
                        id: {{ $invoice->id }},
                        invoice: '{{ $invoice->invoice_number }}',
                        name: '{{ $invoice->beneficiary->name ?? $invoice->beneficiary_company }}',
                        bank: '{{ $invoice->bank->name ?? "غير محدد" }}',
                        date: '{{ $invoice->invoice_date->format("d M Y") }}',
                        amount: '{{ number_format($invoice->total_amount_iqd ?? $invoice->amount, 2) }}',
                        status: '{{ $invoice->status === "paid" ? "مدفوعة" : "غير مدفوعة" }}',
                        action: {{ $invoice->id }},
                    },
                    @endforeach
                ],
                searchText: '',
                datatable: null,
                dataArr: [],

                init() {
                    this.setTableData();
                    this.initializeTable();
                    this.$watch('items', value => {
                        this.datatable.destroy()
                        this.setTableData();
                        this.initializeTable();
                    });
                    this.$watch('selectedRows', value => {
                        this.datatable.destroy()
                        this.setTableData();
                        this.initializeTable();
                    });
                },

                checkOpenModal() {
                    // فحص إذا كان هناك query parameter لفتح modal
                    const urlParams = new URLSearchParams(window.location.search);
                    if (urlParams.get('open_modal') === 'true') {
                        // إزالة parameter من URL
                        const newUrl = window.location.pathname;
                        window.history.replaceState({}, document.title, newUrl);

                        // فتح modal
                        setTimeout(() => {
                            this.$dispatch('open-modal', 'add-invoice-modal');
                        }, 100);
                    }
                },

                initializeTable() {
                    this.datatable = new simpleDatatables.DataTable('#myTable', {
                        data: {
                            headings: [
                                '<input type="checkbox" class="form-checkbox" :checked="checkAllCheckbox" :value="checkAllCheckbox" @change="checkAll($event.target.checked)"/>',
                                "رقم الفاتورة",
                                "الشركة المستفيدة",
                                "المصرف",
                                "التاريخ",
                                "المبلغ",
                                "الحالة",
                                "الإجراءات",
                            ],
                            data: this.dataArr
                        },
                        perPage: 10,
                        perPageSelect: [10, 20, 30, 50, 100],
                        columns: [{
                                select: 0,
                                sortable: false,
                                render: function(data, cell, row) {
                                    return `<input type="checkbox" class="form-checkbox mt-1" :id="'chk' + ${data}" :value="(${data})" x-model.number="selectedRows" />`;
                                }
                            },
                            {
                                select: 1,
                                render: function(data, cell, row) {
                                    return '<a href="{{ route("companies.invoices.show", [$company, ""]) }}/' + row.cells[0].data + '" class="text-primary underline font-semibold hover:no-underline">#' +
                                        data + '</a>';
                                }
                            },
                            {
                                select: 5,
                                render: function(data, cell, row) {
                                    return '<div class="font-semibold">' + data + ' دينار</div>';
                                }
                            },
                            {
                                select: 6,
                                render: function(data, cell, row) {
                                    let statusText = data;
                                    let styleClass = statusText == 'مدفوعة' ? 'badge-outline-success' : 'badge-outline-danger';
                                    return '<span class="badge ' + styleClass + '">' + statusText + '</span>';
                                },
                            },
                            {
                                select: 7,
                                sortable: false,
                                render: function(data, cell, row) {
                                    let invoiceId = row.cells[0].data;
                                    let editUrl = '/companies/{{ $company->id }}/invoices/' + invoiceId + '/edit';
                                    let showUrl = '/companies/{{ $company->id }}/invoices/' + invoiceId;
                                    return `<div class="flex gap-4 items-center">
                                                <a href="${editUrl}" class="hover:text-info">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5">
                                                        <path
                                                            opacity="0.5"
                                                            d="M22 10.5V12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2H13.5"
                                                            stroke="currentColor"
                                                            stroke-width="1.5"
                                                            stroke-linecap="round"
                                                        ></path>
                                                        <path
                                                            d="M17.3009 2.80624L16.652 3.45506L10.6872 9.41993C10.2832 9.82394 10.0812 10.0259 9.90743 10.2487C9.70249 10.5114 9.52679 10.7957 9.38344 11.0965C9.26191 11.3515 9.17157 11.6225 8.99089 12.1646L8.41242 13.9L8.03811 15.0229C7.9492 15.2897 8.01862 15.5837 8.21744 15.7826C8.41626 15.9814 8.71035 16.0508 8.97709 15.9619L10.1 15.5876L11.8354 15.0091C12.3775 14.8284 12.6485 14.7381 12.9035 14.6166C13.2043 14.4732 13.4886 14.2975 13.7513 14.0926C13.9741 13.9188 14.1761 13.7168 14.5801 13.3128L20.5449 7.34795L21.1938 6.69914C22.2687 5.62415 22.2687 3.88124 21.1938 2.80624C20.1188 1.73125 18.3759 1.73125 17.3009 2.80624Z"
                                                            stroke="currentColor"
                                                            stroke-width="1.5"
                                                        ></path>
                                                        <path
                                                            opacity="0.5"
                                                            d="M16.6522 3.45508C16.6522 3.45508 16.7333 4.83381 17.9499 6.05034C19.1664 7.26687 20.5451 7.34797 20.5451 7.34797M10.1002 15.5876L8.4126 13.9"
                                                            stroke="currentColor"
                                                            stroke-width="1.5"
                                                        ></path>
                                                    </svg>
                                                </a>
                                                <a href="${showUrl}" class="hover:text-primary">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                                        <path
                                                            opacity="0.5"
                                                            d="M3.27489 15.2957C2.42496 14.1915 2 13.6394 2 12C2 10.3606 2.42496 9.80853 3.27489 8.70433C4.97196 6.49956 7.81811 4 12 4C16.1819 4 19.028 6.49956 20.7251 8.70433C21.575 9.80853 22 10.3606 22 12C22 13.6394 21.575 14.1915 20.7251 15.2957C19.028 17.5004 16.1819 20 12 20C7.81811 20 4.97196 17.5004 3.27489 15.2957Z"
                                                            stroke="currentColor"
                                                            stroke-width="1.5"
                                                        ></path>
                                                        <path d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z" stroke="currentColor" stroke-width="1.5"></path>
                                                    </svg>
                                                </a>
                                                <button type="button" class="hover:text-danger" @click="deleteRow(${invoiceId})">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                                        <path d="M20.5001 6H3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                                        <path
                                                            d="M18.8334 8.5L18.3735 15.3991C18.1965 18.054 18.108 19.3815 17.243 20.1907C16.378 21 15.0476 21 12.3868 21H11.6134C8.9526 21 7.6222 21 6.75719 20.1907C5.89218 19.3815 5.80368 18.054 5.62669 15.3991L5.16675 8.5"
                                                            stroke="currentColor"
                                                            stroke-width="1.5"
                                                            stroke-linecap="round"
                                                        ></path>
                                                        <path opacity="0.5" d="M9.5 11L10 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                                        <path opacity="0.5" d="M14.5 11L14 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                                        <path
                                                            opacity="0.5"
                                                            d="M6.5 6C6.55588 6 6.58382 6 6.60915 5.99936C7.43259 5.97849 8.15902 5.45491 8.43922 4.68032C8.44784 4.65649 8.45667 4.62999 8.47434 4.57697L8.57143 4.28571C8.65431 4.03708 8.69575 3.91276 8.75071 3.8072C8.97001 3.38607 9.37574 3.09364 9.84461 3.01877C9.96213 3 10.0932 3 10.3553 3H13.6447C13.9068 3 14.0379 3 14.1554 3.01877C14.6243 3.09364 15.03 3.38607 15.2493 3.8072C15.3043 3.91276 15.3457 4.03708 15.4286 4.28571L15.5257 4.57697C15.5433 4.62992 15.5522 4.65651 15.5608 4.68032C15.841 5.45491 16.5674 5.97849 17.3909 5.99936C17.4162 6 17.4441 6 17.5 6"
                                                            stroke="currentColor"
                                                            stroke-width="1.5"
                                                        ></path>
                                                    </svg>
                                                </button>
                                            </div>`;
                                }
                            }
                        ],
                        firstLast: true,
                        firstText: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180"> <path d="M13 19L7 12L13 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> <path opacity="0.5" d="M16.9998 19L10.9998 12L16.9998 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </svg>',
                        lastText: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180"> <path d="M11 19L17 12L11 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> <path opacity="0.5" d="M6.99976 19L12.9998 12L6.99976 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </svg>',
                        prevText: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180"> <path d="M15 5L9 12L15 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </svg>',
                        nextText: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180"> <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </svg>',
                        labels: {
                            perPage: "<span class='ml-2'>{select}</span>",
                            noRows: "لا توجد بيانات",
                        },
                        layout: {
                            top: "{search}",
                            bottom: "{info}{select}{pager}",
                        },
                    });
                },

                checkAllCheckbox() {
                    if (this.items.length && this.selectedRows.length === this.items.length) {
                        return true;
                    } else {
                        return false;
                    }
                },

                checkAll(isChecked) {
                    if (isChecked) {
                        this.selectedRows = this.items.map((d) => {
                            return d.id;
                        });
                    } else {
                        this.selectedRows = [];
                    }
                },

                setTableData() {
                    this.dataArr = [];
                    for (let i = 0; i < this.items.length; i++) {
                        this.dataArr[i] = [];
                        for (let p in this.items[i]) {
                            if (this.items[i].hasOwnProperty(p)) {
                                this.dataArr[i].push(this.items[i][p]);
                            }
                        }
                    }
                },

                deleteRow(item) {
                    if (confirm('هل أنت متأكد من حذف الفاتورة المحددة؟')) {
                        if (item) {
                            // حذف فاتورة واحدة
                            let form = document.createElement('form');
                            form.method = 'POST';
                            form.action = `{{ route('companies.invoices.destroy', [$company, '']) }}/${item}`;

                            let csrfInput = document.createElement('input');
                            csrfInput.type = 'hidden';
                            csrfInput.name = '_token';
                            csrfInput.value = '{{ csrf_token() }}';

                            let methodInput = document.createElement('input');
                            methodInput.type = 'hidden';
                            methodInput.name = '_method';
                            methodInput.value = 'DELETE';

                            form.appendChild(csrfInput);
                            form.appendChild(methodInput);
                            document.body.appendChild(form);
                            form.submit();
                        } else {
                            // حذف متعدد
                            if (this.selectedRows.length === 0) {
                                alert('يرجى تحديد فاتورة واحدة على الأقل للحذف');
                                return;
                            }
                            // يمكن إضافة وظيفة حذف متعدد هنا
                            alert('وظيفة الحذف المتعدد قيد التطوير');
                        }
                    }
                },
            }))
        })

        // تهيئة NiceSelect للشحنات مع تحسينات
        document.addEventListener("DOMContentLoaded", function(e) {
            // التحقق من وجود NiceSelect
            if (typeof NiceSelect !== 'undefined') {
                var els = document.querySelectorAll(".selectize");
                els.forEach(function(select) {
                    NiceSelect.bind(select, {
                        searchable: true,
                        placeholder: 'ابحث واختر الشحنات...',
                        searchtext: 'ابحث...',
                        selectedtext: 'تم اختيار',
                        maxHeight: '300px',
                        searchable: true,
                        searchtext: 'ابحث في الشحنات...',
                        selectedtext: 'تم اختيار',
                        maxHeight: '300px'
                    });
                });
            } else {
                console.error('NiceSelect library is not loaded');
            }
        });
    </script>
</x-layout.company>
