<x-layout.company :company="$company">
    <div x-data="invoicePreview">
        <div class="flex items-center lg:justify-end justify-center flex-wrap gap-4 mb-6">
            <button type="button" class="btn btn-info gap-2" @click="openShareModal">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5">
                    <path
                        d="M17.4975 18.4851L20.6281 9.09373C21.8764 5.34874 22.5006 3.47624 21.5122 2.48782C20.5237 1.49939 18.6511 2.12356 14.906 3.37189L5.57477 6.48218C3.49295 7.1761 2.45203 7.52305 2.13608 8.28637C2.06182 8.46577 2.01692 8.65596 2.00311 8.84963C1.94433 9.67365 2.72018 10.4495 4.27188 12.0011L4.55451 12.2837C4.80921 12.5384 4.93655 12.6658 5.03282 12.8075C5.22269 13.0871 5.33046 13.4143 5.34393 13.7519C5.35076 13.9232 5.32403 14.1013 5.27057 14.4574C5.07488 15.7612 4.97703 16.4131 5.0923 16.9147C5.32205 17.9146 6.09599 18.6995 7.09257 18.9433C7.59255 19.0656 8.24576 18.977 9.5522 18.7997L9.62363 18.79C9.99191 18.74 10.1761 18.715 10.3529 18.7257C10.6738 18.745 10.9838 18.8496 11.251 19.0285C11.3981 19.1271 11.5295 19.2585 11.7923 19.5213L12.0436 19.7725C13.5539 21.2828 14.309 22.0379 15.1101 21.9985C15.3309 21.9877 15.5479 21.9365 15.7503 21.8474C16.4844 21.5244 16.8221 20.5113 17.4975 18.4851Z"
                        stroke="currentColor" stroke-width="1.5" />
                    <path opacity="0.5" d="M6 18L21 3" stroke="currentColor" stroke-width="1.5"
                        stroke-linecap="round" />
                </svg>
                إرسال الفاتورة
            </button>

            <a href="{{ route('companies.invoices.pdf', [$company, $invoice]) }}" target="_blank" class="btn btn-primary gap-2">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5">
                    <path
                        d="M6 17.9827C4.44655 17.9359 3.51998 17.7626 2.87868 17.1213C2 16.2426 2 14.8284 2 12C2 9.17157 2 7.75736 2.87868 6.87868C3.75736 6 5.17157 6 8 6H16C18.8284 6 20.2426 6 21.1213 6.87868C22 7.75736 22 9.17157 22 12C22 14.8284 22 16.2426 21.1213 17.1213C20.48 17.7626 19.5535 17.9359 18 17.9827"
                        stroke="currentColor" stroke-width="1.5" />
                    <path opacity="0.5" d="M9 10H6" stroke="currentColor" stroke-width="1.5"
                        stroke-linecap="round" />
                    <path d="M19 14L5 14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    <path
                        d="M18 14V16C18 18.8284 18 20.2426 17.1213 21.1213C16.2426 22 14.8284 22 12 22C9.17157 22 7.75736 22 6.87868 21.1213C6 20.2426 6 18.8284 6 16V14"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    <path opacity="0.5"
                        d="M17.9827 6C17.9359 4.44655 17.7626 3.51998 17.1213 2.87868C16.2427 2 14.8284 2 12 2C9.17158 2 7.75737 2 6.87869 2.87868C6.23739 3.51998 6.06414 4.44655 6.01733 6"
                        stroke="currentColor" stroke-width="1.5" />
                    <circle opacity="0.5" cx="17" cy="10" r="1" fill="currentColor" />
                    <path opacity="0.5" d="M15 16.5H9" stroke="currentColor" stroke-width="1.5"
                        stroke-linecap="round" />
                    <path opacity="0.5" d="M13 19H9" stroke="currentColor" stroke-width="1.5"
                        stroke-linecap="round" />
                </svg>
                طباعة
            </a>

            <a href="{{ route('companies.invoices.download', [$company, $invoice]) }}" class="btn btn-success gap-2">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5">
                    <path opacity="0.5"
                        d="M17 9.00195C19.175 9.01406 20.3529 9.11051 21.1213 9.8789C22 10.7576 22 12.1718 22 15.0002V16.0002C22 18.8286 22 20.2429 21.1213 21.1215C20.2426 22.0002 18.8284 22.0002 16 22.0002H8C5.17157 22.0002 3.75736 22.0002 2.87868 21.1215C2 20.2429 2 18.8286 2 16.0002L2 15.0002C2 12.1718 2 10.7576 2.87868 9.87889C3.64706 9.11051 4.82497 9.01406 7 9.00195"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                    <path d="M12 2L12 15M12 15L9 11.5M12 15L15 11.5" stroke="currentColor" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
                تحميل PDF
            </a>

            <a href="{{ route('companies.invoices.index', $company) }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="w-5 h-5">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                العودة للقائمة
            </a>

            <a href="{{ route('companies.invoices.edit', [$company, $invoice]) }}" class="btn btn-warning gap-2">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                    <path opacity="0.5"
                        d="M22 10.5V12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2H13.5"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                    <path
                        d="M17.3009 2.80624L16.652 3.45506L10.6872 9.41993C10.2832 9.82394 10.0812 10.0259 9.90743 10.2487C9.70249 10.5114 9.52679 10.7957 9.38344 11.0965C9.26191 11.3515 9.17157 11.6225 8.99089 12.1646L8.41242 13.9L8.03811 15.0229C7.9492 15.2897 8.01862 15.5837 8.21744 15.7826C8.41626 15.9814 8.71035 16.0508 8.97709 15.9619L10.1 15.5876L11.8354 15.0091C12.3775 14.8284 12.6485 14.7381 12.9035 14.6166C13.2043 14.4732 13.4886 14.2975 13.7513 14.0926C13.9741 13.9188 14.1761 13.7168 14.5801 13.3128L20.5449 7.34795L21.1938 6.69914C22.2687 5.62415 22.2687 3.88124 21.1938 2.80624C20.1188 1.73125 18.3759 1.73125 17.3009 2.80624Z"
                        stroke="currentColor" stroke-width="1.5"></path>
                    <path opacity="0.5"
                        d="M16.6522 3.45508C16.6522 3.45508 16.7333 4.83381 17.9499 6.05034C19.1664 7.26687 20.5451 7.34797 20.5451 7.34797M10.1002 15.5876L8.4126 13.9"
                        stroke="currentColor" stroke-width="1.5"></path>
                </svg>
                تعديل
            </a>
        </div>
        <div class="panel">
            <div class="flex justify-between flex-wrap gap-4 px-4">
                <div class="text-2xl font-semibold uppercase">فاتورة</div>
                <div class="shrink-0">
                    <img src="/assets/images/logo.png" alt="image"
                        class="w-14 ltr:ml-auto rtl:mr-auto" />
                </div>
            </div>
            <div class="ltr:text-right rtl:text-left px-4">
                <div class="space-y-1 mt-6 text-white-dark">
                    <div><strong>{{ $company->name }}</strong></div>
                    <div>العراق، بغداد</div>
                    <div>info@alzubaidgroup.com</div>
                    <div dir="ltr">+964 (0) 770 123 4567</div>
                </div>
            </div>

            <hr class="border-[#e0e6ed] dark:border-[#1b2e4b] my-6">
            <div class="flex justify-between lg:flex-row flex-col gap-6 flex-wrap">
                <div class="flex-1">
                    <div class="space-y-1 text-white-dark">
                        <div>الفاتورة لصالح:</div>
                        <div class="text-black dark:text-white font-semibold">{{ $invoice->beneficiary_company }}</div>
                        <div>{{ $invoice->beneficiary_company }}</div>
                    </div>
                </div>
                <div class="flex justify-between sm:flex-row flex-col gap-6 lg:w-2/3">
                    <div class="xl:1/3 lg:w-2/5 sm:w-1/2">
                        <div class="flex items-center w-full justify-between mb-2">
                            <div class="text-white-dark">رقم الفاتورة:</div>
                            <div>#{{ $invoice->invoice_number }}</div>
                        </div>
                        <div class="flex items-center w-full justify-between mb-2">
                            <div class="text-white-dark">تاريخ الإصدار:</div>
                            <div>{{ $invoice->invoice_date->format('d M Y') }}</div>
                        </div>
                        <div class="flex items-center w-full justify-between mb-2">
                            <div class="text-white-dark">الحالة:</div>
                            <div>
                                <span class="badge bg-success">مدفوعة</span>
                            </div>
                        </div>
                        <div class="flex items-center w-full justify-between mb-2">
                            <div class="text-white-dark">حالة الشحن:</div>
                            <div>
                                <span class="badge {{ $invoice->shipping_status === 'shipped' ? 'bg-success' : 'bg-warning' }}">
                                    {{ $invoice->shipping_status === 'shipped' ? 'مشحون' : 'غير مشحون' }}
                                </span>
                            </div>
                        </div>
                        @if($invoice->shipments->count() > 0)
                        <div class="flex items-center w-full justify-between">
                            <div class="text-white-dark">الشحنات المرتبطة:</div>
                            <div>{{ $invoice->shipments->count() }}</div>
                        </div>
                        @endif
                    </div>
                    <div class="xl:1/3 lg:w-2/5 sm:w-1/2">
                        <div class="flex items-center w-full justify-between mb-2 ">
                            <div class="text-white-dark">اسم المصرف:</div>
                            <div class="whitespace-nowrap">{{ $invoice->bank->name ?? 'غير محدد' }}</div>
                        </div>
                        @if($invoice->bank)
                        <div class="flex items-center w-full justify-between mb-2">
                            <div class="text-white-dark">الرصيد الحالي:</div>
                            <div>{{ number_format($invoice->bank->current_balance, 2) }} {{ $invoice->bank->currency }}</div>
                        </div>
                        <div class="flex items-center w-full justify-between mb-2">
                            <div class="text-white-dark">الرصيد الافتتاحي:</div>
                            <div>{{ number_format($invoice->bank->opening_balance, 2) }} {{ $invoice->bank->currency }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            @if($invoice->shipments->count() > 0)
            <div class="table-responsive mt-6">
                <table class="table-striped">
                    <thead>
                        <tr>
                            <template x-for="item in columns" :key="item.key">
                                <th :class="[item.class]" x-text="item.label"></th>
                            </template>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="item in items" :key="item.id">
                            <tr>
                                <td x-text="item.id"></td>
                                <td x-text="item.container_number"></td>
                                <td x-text="item.status"></td>
                                <td x-text="item.weight"></td>
                                <td x-text="item.carton_count"></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            @endif

            <div class="grid sm:grid-cols-2 grid-cols-1 px-4 mt-6">
                <div></div>
                <div class="ltr:text-right rtl:text-left space-y-2">
                    <div class="flex items-center font-semibold text-lg">
                        <div class="flex-1">المبلغ الإجمالي:</div>
                        <div class="w-[37%]">{{ number_format($invoice->amount, 2) }} دينار</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Share Modal -->
    <div x-data="{ open: false, shareLink: '', loading: false, copied: false }"
         @open-share-modal.window="open = true"
         @close-share-modal.window="open = false"
         x-show="open"
         style="display: none;"
         class="fixed inset-0 z-[999] overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center px-4">
            <div @click="open = false" class="fixed inset-0 bg-[black]/60"></div>
            <div class="panel my-8 w-full max-w-lg overflow-hidden rounded-lg border-0 p-0">
                <div class="flex items-center justify-between bg-[#fbfbfb] px-5 py-3 dark:bg-[#121c2c]">
                    <h5 class="text-lg font-bold">مشاركة الفاتورة</h5>
                    <button @click="open = false" type="button" class="text-white-dark hover:text-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                <div class="p-5">
                    <div class="mb-5">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 bg-info/20 rounded-full flex items-center justify-center">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17.4975 18.4851L20.6281 9.09373C21.8764 5.34874 22.5006 3.47624 21.5122 2.48782C20.5237 1.49939 18.6511 2.12356 14.906 3.37189L5.57477 6.48218C3.49295 7.1761 2.45203 7.52305 2.13608 8.28637C2.06182 8.46577 2.01692 8.65596 2.00311 8.84963C1.94433 9.67365 2.72018 10.4495 4.27188 12.0011L4.55451 12.2837C4.80921 12.5384 4.93655 12.6658 5.03282 12.8075C5.22269 13.0871 5.33046 13.4143 5.34393 13.7519C5.35076 13.9232 5.32403 14.1013 5.27057 14.4574C5.07488 15.7612 4.97703 16.4131 5.0923 16.9147C5.32205 17.9146 6.09599 18.6995 7.09257 18.9433C7.59255 19.0656 8.24576 18.977 9.5522 18.7997L9.62363 18.79C9.99191 18.74 10.1761 18.715 10.3529 18.7257C10.6738 18.745 10.9838 18.8496 11.251 19.0285C11.3981 19.1271 11.5295 19.2585 11.7923 19.5213L12.0436 19.7725C13.5539 21.2828 14.309 22.0379 15.1101 21.9985C15.3309 21.9877 15.5479 21.9365 15.7503 21.8474C16.4844 21.5244 16.8221 20.5113 17.4975 18.4851Z" stroke="currentColor" stroke-width="1.5" />
                                    <path opacity="0.5" d="M6 18L21 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                </svg>
                            </div>
                            <div>
                                <h6 class="font-semibold text-lg">فاتورة #{{ $invoice->invoice_number }}</h6>
                                <p class="text-gray-500">رابط مشاركة آمن صالح لمدة 24 ساعة</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label for="share_link">رابط المشاركة</label>
                        <div class="flex">
                            <input id="share_link" x-model="shareLink" type="text" class="form-input rounded-none" readonly />
                            <button @click="copyLink()" type="button" class="btn btn-primary rounded-none">
                                <span x-show="!copied">نسخ</span>
                                <span x-show="copied" class="text-green-500">تم النسخ!</span>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">انقر على نسخ ثم شارك الرابط عبر واتساب أو تليجرام</p>
                    </div>

                    <div class="mb-5">
                        <div class="flex items-center justify-center gap-4">
                            <button @click="shareViaWhatsApp()" type="button" class="btn btn-success gap-2">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                                </svg>
                                واتساب
                            </button>
                            <button @click="shareViaTelegram()" type="button" class="btn btn-info gap-2">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                                </svg>
                                تليجرام
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <button type="button" @click="open = false" class="btn btn-outline-secondary">إغلاق</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("alpine:init", () => {
            Alpine.data('invoicePreview', () => ({
                items: [
                    @foreach($invoice->shipments as $index => $shipment)
                    {
                        id: {{ $index + 1 }},
                        container_number: '{{ $shipment->container_number }}',
                        status: '<span class="badge badge-outline-{{ $shipment->status === "shipped" ? "success" : "warning" }}">{{ $shipment->status === "shipped" ? "مشحون" : "غير مشحون" }}</span>',
                        weight: '{{ $shipment->weight }} كغ',
                        carton_count: '{{ $shipment->carton_count }} كرتون',
                    },
                    @endforeach
                ],
                columns: [
                    {
                        key: 'id',
                        label: 'م.'
                    },
                    {
                        key: 'container_number',
                        label: 'رقم الحاوية'
                    },
                    {
                        key: 'status',
                        label: 'الحالة'
                    },
                    {
                        key: 'weight',
                        label: 'الوزن'
                    },
                    {
                        key: 'carton_count',
                        label: 'عدد الكراتين'
                    },
                ],

                print() {
                    window.print();
                },

                openShareModal() {
                    this.$dispatch('open-share-modal');
                    this.generateShareLink();
                },

                async generateShareLink() {
                    try {
                        const response = await fetch('{{ route("companies.invoices.share-link", [$company, $invoice]) }}');
                        const data = await response.json();

                        if (data.success) {
                            this.$refs.shareModal.shareLink = data.share_link;
                        }
                    } catch (error) {
                        console.error('Error generating share link:', error);
                    }
                }
            }));

            // Share Modal Functions
            Alpine.data('shareModal', () => ({
                async generateShareLink() {
                    this.loading = true;
                    try {
                        const response = await fetch('{{ route("companies.invoices.share-link", [$company, $invoice]) }}');
                        const data = await response.json();

                        if (data.success) {
                            this.shareLink = data.share_link;
                        }
                    } catch (error) {
                        console.error('Error generating share link:', error);
                    } finally {
                        this.loading = false;
                    }
                },

                async copyLink() {
                    try {
                        await navigator.clipboard.writeText(this.shareLink);
                        this.copied = true;
                        setTimeout(() => {
                            this.copied = false;
                        }, 2000);
                    } catch (error) {
                        console.error('Error copying link:', error);
                    }
                },

                shareViaWhatsApp() {
                    const message = `فاتورة #{{ $invoice->invoice_number }}\n\nرابط الفاتورة:\n${this.shareLink}`;
                    const url = `https://wa.me/?text=${encodeURIComponent(message)}`;
                    window.open(url, '_blank');
                },

                shareViaTelegram() {
                    const message = `فاتورة #{{ $invoice->invoice_number }}\n\nرابط الفاتورة:\n${this.shareLink}`;
                    const url = `https://t.me/share/url?url=${encodeURIComponent(this.shareLink)}&text=${encodeURIComponent(message)}`;
                    window.open(url, '_blank');
                }
            }));
        });
    </script>
</x-layout.company>

