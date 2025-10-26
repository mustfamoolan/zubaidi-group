<header class="z-40" :class="{ 'dark': $store.app.semidark && $store.app.menu === 'horizontal' }">
    <div class="shadow-sm">
        <div class="relative bg-white flex w-full items-center px-5 py-2.5 dark:bg-[#0e1726]">
            <div class="horizontal-logo flex lg:hidden justify-between items-center ltr:mr-2 rtl:ml-2">
                <a href="/" class="main-logo flex items-center shrink-0">
                    <img class="w-8 ltr:-ml-1 rtl:-mr-1 inline" src="/assets/images/logo.png"
                        alt="image" />
                    <span
                        class="text-2xl ltr:ml-1.5 rtl:mr-1.5  font-semibold  align-middle hidden md:inline dark:text-white-light transition-all duration-300">VRISTO</span>
                </a>

                <a href="javascript:;"
                    class="collapse-icon flex-none dark:text-[#d0d2d6] hover:text-primary dark:hover:text-primary flex lg:hidden ltr:ml-2 rtl:mr-2 p-2 rounded-full bg-white-light/40 dark:bg-dark/40 hover:bg-white-light/90 dark:hover:bg-dark/60"
                    @click="$store.app.toggleSidebar()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 7L4 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path opacity="0.5" d="M20 12L4 12" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" />
                        <path d="M20 17L4 17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                </a>
            </div>
            <div class="ltr:mr-2 rtl:ml-2 hidden sm:block">
                <ul class="flex items-center space-x-2 rtl:space-x-reverse dark:text-[#d0d2d6]">
                    <li>
                        <a href="/apps/calendar"
                            class="block p-2 rounded-full bg-white-light/40 dark:bg-dark/40 hover:text-primary hover:bg-white-light/90 dark:hover:bg-dark/60">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M2 12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H14C17.7712 4 19.6569 4 20.8284 5.17157C22 6.34315 22 8.22876 22 12V14C22 17.7712 22 19.6569 20.8284 20.8284C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.8284C2 19.6569 2 17.7712 2 14V12Z"
                                    stroke="currentColor" stroke-width="1.5" />
                                <path opacity="0.5" d="M7 4V2.5" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" />
                                <path opacity="0.5" d="M17 4V2.5" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" />
                                <path opacity="0.5" d="M2 9H22" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" />
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="/apps/todolist"
                            class="block p-2 rounded-full bg-white-light/40 dark:bg-dark/40 hover:text-primary hover:bg-white-light/90 dark:hover:bg-dark/60">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.5"
                                    d="M22 10.5V12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2H13.5"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                <path
                                    d="M17.3009 2.80624L16.652 3.45506L10.6872 9.41993C10.2832 9.82394 10.0812 10.0259 9.90743 10.2487C9.70249 10.5114 9.52679 10.7957 9.38344 11.0965C9.26191 11.3515 9.17157 11.6225 8.99089 12.1646L8.41242 13.9L8.03811 15.0229C7.9492 15.2897 8.01862 15.5837 8.21744 15.7826C8.41626 15.9814 8.71035 16.0508 8.97709 15.9619L10.1 15.5876L11.8354 15.0091C12.3775 14.8284 12.6485 14.7381 12.9035 14.6166C13.2043 14.4732 13.4886 14.2975 13.7513 14.0926C13.9741 13.9188 14.1761 13.7168 14.5801 13.3128L20.5449 7.34795L21.1938 6.69914C22.2687 5.62415 22.2687 3.88124 21.1938 2.80624C20.1188 1.73125 18.3759 1.73125 17.3009 2.80624Z"
                                    stroke="currentColor" stroke-width="1.5" />
                                <path opacity="0.5"
                                    d="M16.6522 3.45508C16.6522 3.45508 16.7333 4.83381 17.9499 6.05034C19.1664 7.26687 20.5451 7.34797 20.5451 7.34797M10.1002 15.5876L8.4126 13.9"
                                    stroke="currentColor" stroke-width="1.5" />
                            </svg>
                        </a>
                    </li>
                    <li><a href="/apps/chat"
                            class="block p-2 rounded-full bg-white-light/40 dark:bg-dark/40 hover:text-primary hover:bg-white-light/90 dark:hover:bg-dark/60">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <circle r="3" transform="matrix(-1 0 0 1 19 5)" stroke="currentColor"
                                    stroke-width="1.5" />
                                <path opacity="0.5"
                                    d="M14 2.20004C13.3538 2.06886 12.6849 2 12 2C6.47715 2 2 6.47715 2 12C2 13.5997 2.37562 15.1116 3.04346 16.4525C3.22094 16.8088 3.28001 17.2161 3.17712 17.6006L2.58151 19.8267C2.32295 20.793 3.20701 21.677 4.17335 21.4185L6.39939 20.8229C6.78393 20.72 7.19121 20.7791 7.54753 20.9565C8.88837 21.6244 10.4003 22 12 22C17.5228 22 22 17.5228 22 12C22 11.3151 21.9311 10.6462 21.8 10"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
            <div x-data="header"
                class="sm:flex-1 ltr:sm:ml-0 ltr:ml-auto sm:rtl:mr-0 rtl:mr-auto flex items-center space-x-1.5 lg:space-x-2 rtl:space-x-reverse dark:text-[#d0d2d6]">
                <div class="sm:ltr:mr-auto sm:rtl:ml-auto" x-data="smartSearch()">
                    <form class="sm:relative absolute inset-x-0 sm:top-0 top-1/2 sm:translate-y-0 -translate-y-1/2 sm:mx-0 mx-4 z-10 sm:block hidden"
                          :class="{ '!block': searchOpen }" @submit.prevent="navigateToFirst()">
                        <div class="relative">
                            <input type="text"
                                   x-model="searchQuery"
                                   @input.debounce.300ms="search()"
                                   @focus="searchOpen = true"
                                   @keydown.escape="closeSearch()"
                                   @keydown.down.prevent="navigateDown()"
                                   @keydown.up.prevent="navigateUp()"
                                   @keydown.enter.prevent="navigateToSelected()"
                                class="form-input ltr:pl-9 rtl:pr-9 ltr:sm:pr-4 rtl:sm:pl-4 ltr:pr-9 rtl:pl-9 peer sm:bg-transparent bg-gray-100 placeholder:tracking-widest"
                                   placeholder="ابحث عن فاتورة، شحنة، مصرف..." />
                            <button type="button"
                                class="absolute w-9 h-9 inset-0 ltr:right-auto rtl:left-auto appearance-none peer-focus:text-primary">
                                <svg class="mx-auto" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor" stroke-width="1.5" opacity="0.5" />
                                    <path d="M18.5 18.5L22 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                </svg>
                            </button>
                            <button type="button"
                                    x-show="searchQuery"
                                    @click="clearSearch()"
                                    class="absolute top-1/2 -translate-y-1/2 ltr:right-2 rtl:left-2 hover:opacity-80">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle opacity="0.5" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5" />
                                    <path d="M14.5 9.50002L9.5 14.5M9.49998 9.5L14.5 14.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                </svg>
                            </button>
                        </div>

                        <!-- نتائج البحث -->
                        <div x-show="searchOpen && hasResults()"
                             @click.outside="closeSearch()"
                             class="absolute top-full mt-2 w-full max-w-md bg-white dark:bg-[#1b2e4b] rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 max-h-[80vh] overflow-y-auto z-50">

                            <template x-if="searching">
                                <div class="p-4 text-center">
                                    <div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-primary"></div>
                                </div>
                            </template>

                            <template x-if="!searching">
                                <div>
                                    <!-- الفواتير -->
                                    <template x-if="results.invoices && results.invoices.length > 0">
                                        <div class="p-2">
                                            <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 px-3 py-2">الفواتير</div>
                                            <template x-for="(item, index) in results.invoices" :key="item.id">
                                                <a :href="item.url"
                                                   :class="{'bg-primary/10': selectedIndex === getItemIndex('invoices', index)}"
                                                   class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                                                    <div class="w-8 h-8 rounded-full bg-success/20 text-success flex items-center justify-center">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M9 7H5C3.89543 7 3 7.89543 3 9V18C3 19.1046 3.89543 20 5 20H19C20.1046 20 21 19.1046 21 18V9C21 7.89543 20.1046 7 19 7H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <div class="font-semibold text-sm" x-text="item.title"></div>
                                                        <div class="text-xs text-gray-500" x-text="item.subtitle"></div>
                                                    </div>
                                                </a>
                                            </template>
                                        </div>
                                    </template>

                                    <!-- الشحنات -->
                                    <template x-if="results.shipments && results.shipments.length > 0">
                                        <div class="p-2 border-t border-gray-200 dark:border-gray-700">
                                            <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 px-3 py-2">الشحنات</div>
                                            <template x-for="(item, index) in results.shipments" :key="item.id">
                                                <a :href="item.url"
                                                   :class="{'bg-primary/10': selectedIndex === getItemIndex('shipments', index)}"
                                                   class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                                                    <div class="w-8 h-8 rounded-full bg-info/20 text-info flex items-center justify-center">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M20 7L12 3L4 7M20 7L12 11M20 7V17L12 21M12 11L4 7M12 11V21M4 7V17L12 21" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <div class="font-semibold text-sm" x-text="item.title"></div>
                                                        <div class="text-xs text-gray-500" x-text="item.subtitle"></div>
                                                    </div>
                                                </a>
                                            </template>
                                        </div>
                                    </template>

                                    <!-- المصارف -->
                                    <template x-if="results.banks && results.banks.length > 0">
                                        <div class="p-2 border-t border-gray-200 dark:border-gray-700">
                                            <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 px-3 py-2">المصارف</div>
                                            <template x-for="(item, index) in results.banks" :key="item.id">
                                                <a :href="item.url"
                                                   :class="{'bg-primary/10': selectedIndex === getItemIndex('banks', index)}"
                                                   class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                                                    <div class="w-8 h-8 rounded-full bg-warning/20 text-warning flex items-center justify-center">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M3 21H21M5 21V7L12 3L19 7V21M9 9H10M14 9H15M9 13H10M14 13H15M9 17H10M14 17H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <div class="font-semibold text-sm" x-text="item.title"></div>
                                                        <div class="text-xs text-gray-500" x-text="item.subtitle"></div>
                                                    </div>
                                                </a>
                                            </template>
                                        </div>
                                    </template>

                                    <!-- المستفيدين -->
                                    <template x-if="results.beneficiaries && results.beneficiaries.length > 0">
                                        <div class="p-2 border-t border-gray-200 dark:border-gray-700">
                                            <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 px-3 py-2">المستفيدين</div>
                                            <template x-for="(item, index) in results.beneficiaries" :key="item.id">
                                                <a :href="item.url"
                                                   :class="{'bg-primary/10': selectedIndex === getItemIndex('beneficiaries', index)}"
                                                   class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                                                    <div class="w-8 h-8 rounded-full bg-secondary/20 text-secondary flex items-center justify-center">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="1.5"/>
                                                            <path d="M5 21C5 17.134 8.13401 14 12 14C15.866 14 19 17.134 19 21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <div class="font-semibold text-sm" x-text="item.title"></div>
                                                        <div class="text-xs text-gray-500" x-text="item.subtitle"></div>
                                                    </div>
                                                </a>
                                            </template>
                                        </div>
                                    </template>

                                    <!-- الصفحات -->
                                    <template x-if="results.pages && results.pages.length > 0">
                                        <div class="p-2 border-t border-gray-200 dark:border-gray-700">
                                            <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 px-3 py-2">الصفحات</div>
                                            <template x-for="(item, index) in results.pages" :key="index">
                                                <a :href="item.url"
                                                   :class="{'bg-primary/10': selectedIndex === getItemIndex('pages', index)}"
                                                   class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                                                    <div class="w-8 h-8 rounded-full bg-primary/20 text-primary flex items-center justify-center">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M9 4H7C5.89543 4 5 4.89543 5 6V18C5 19.1046 5.89543 20 7 20H17C18.1046 20 19 19.1046 19 18V6C19 4.89543 18.1046 4 17 4H15" stroke="currentColor" stroke-width="1.5"/>
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <div class="font-semibold text-sm" x-text="item.title"></div>
                                                    </div>
                                                </a>
                                            </template>
                                        </div>
                                    </template>

                                    <!-- لا توجد نتائج -->
                                    <template x-if="!hasResults() && searchQuery.length >= 2 && !searching">
                                        <div class="p-4 text-center text-gray-500">
                                            لا توجد نتائج للبحث عن "<span x-text="searchQuery"></span>"
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </form>
                    <button type="button"
                        class="search_btn sm:hidden p-2 rounded-full bg-white-light/40 dark:bg-dark/40 hover:bg-white-light/90 dark:hover:bg-dark/60"
                            @click="searchOpen = ! searchOpen">
                        <svg class="w-4.5 h-4.5 mx-auto dark:text-[#d0d2d6]" width="20" height="20"
                            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor"
                                stroke-width="1.5" opacity="0.5" />
                            <path d="M18.5 18.5L22 22" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" />
                        </svg>
                    </button>
                </div>
                <div>
                    <a href="javascript:;" x-cloak x-show="$store.app.theme === 'light'" href="javascript:;"
                        class="flex items-center p-2 rounded-full bg-white-light/40 dark:bg-dark/40 hover:text-primary hover:bg-white-light/90 dark:hover:bg-dark/60"
                        @click="$store.app.toggleTheme('dark')">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="5" stroke="currentColor"
                                stroke-width="1.5" />
                            <path d="M12 2V4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M12 20V22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M4 12L2 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M22 12L20 12" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" />
                            <path opacity="0.5" d="M19.7778 4.22266L17.5558 6.25424" stroke="currentColor"
                                stroke-width="1.5" stroke-linecap="round" />
                            <path opacity="0.5" d="M4.22217 4.22266L6.44418 6.25424" stroke="currentColor"
                                stroke-width="1.5" stroke-linecap="round" />
                            <path opacity="0.5" d="M6.44434 17.5557L4.22211 19.7779" stroke="currentColor"
                                stroke-width="1.5" stroke-linecap="round" />
                            <path opacity="0.5" d="M19.7778 19.7773L17.5558 17.5551" stroke="currentColor"
                                stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                    </a>
                    <a href="javascript:;" x-cloak x-show="$store.app.theme === 'dark'" href="javascript:;"
                        class="flex items-center p-2 rounded-full bg-white-light/40 dark:bg-dark/40 hover:text-primary hover:bg-white-light/90 dark:hover:bg-dark/60"
                        @click="$store.app.toggleTheme('system')">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M21.0672 11.8568L20.4253 11.469L21.0672 11.8568ZM12.1432 2.93276L11.7553 2.29085V2.29085L12.1432 2.93276ZM21.25 12C21.25 17.1086 17.1086 21.25 12 21.25V22.75C17.9371 22.75 22.75 17.9371 22.75 12H21.25ZM12 21.25C6.89137 21.25 2.75 17.1086 2.75 12H1.25C1.25 17.9371 6.06294 22.75 12 22.75V21.25ZM2.75 12C2.75 6.89137 6.89137 2.75 12 2.75V1.25C6.06294 1.25 1.25 6.06294 1.25 12H2.75ZM15.5 14.25C12.3244 14.25 9.75 11.6756 9.75 8.5H8.25C8.25 12.5041 11.4959 15.75 15.5 15.75V14.25ZM20.4253 11.469C19.4172 13.1373 17.5882 14.25 15.5 14.25V15.75C18.1349 15.75 20.4407 14.3439 21.7092 12.2447L20.4253 11.469ZM9.75 8.5C9.75 6.41182 10.8627 4.5828 12.531 3.57467L11.7553 2.29085C9.65609 3.5593 8.25 5.86509 8.25 8.5H9.75ZM12 2.75C11.9115 2.75 11.8077 2.71008 11.7324 2.63168C11.6686 2.56527 11.6538 2.50244 11.6503 2.47703C11.6461 2.44587 11.6482 2.35557 11.7553 2.29085L12.531 3.57467C13.0342 3.27065 13.196 2.71398 13.1368 2.27627C13.0754 1.82126 12.7166 1.25 12 1.25V2.75ZM21.7092 12.2447C21.6444 12.3518 21.5541 12.3539 21.523 12.3497C21.4976 12.3462 21.4347 12.3314 21.3683 12.2676C21.2899 12.1923 21.25 12.0885 21.25 12H22.75C22.75 11.2834 22.1787 10.9246 21.7237 10.8632C21.286 10.804 20.7293 10.9658 20.4253 11.469L21.7092 12.2447Z"
                                fill="currentColor" />
                        </svg>
                    </a>
                    <a href="javascript:;" x-cloak x-show="$store.app.theme === 'system'" href="javascript:;"
                        class="flex items-center p-2 rounded-full bg-white-light/40 dark:bg-dark/40 hover:text-primary hover:bg-white-light/90 dark:hover:bg-dark/60"
                        @click="$store.app.toggleTheme('light')">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M3 9C3 6.17157 3 4.75736 3.87868 3.87868C4.75736 3 6.17157 3 9 3H15C17.8284 3 19.2426 3 20.1213 3.87868C21 4.75736 21 6.17157 21 9V14C21 15.8856 21 16.8284 20.4142 17.4142C19.8284 18 18.8856 18 17 18H7C5.11438 18 4.17157 18 3.58579 17.4142C3 16.8284 3 15.8856 3 14V9Z"
                                stroke="currentColor" stroke-width="1.5" />
                            <path opacity="0.5" d="M22 21H2" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" />
                            <path opacity="0.5" d="M15 15H9" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" />
                        </svg>
                    </a>
                </div>

                <div class="dropdown shrink-0" x-data="dropdown" @click.outside="open = false">
                    <a href="javascript:;"
                        class="block p-2 rounded-full bg-white-light/40 dark:bg-dark/40 hover:text-primary hover:bg-white-light/90 dark:hover:bg-dark/60"
                        @click="toggle">
                        <img :src="`/assets/images/flags/${$store.app.locale.toUpperCase()}.svg`" alt="image"
                            class="w-5 h-5 object-cover rounded-full" />
                    </a>
                    <ul x-cloak x-show="open" x-transition x-transition.duration.300ms
                        class="ltr:-right-14 sm:ltr:-right-2 rtl:-left-14 sm:rtl:-left-2 top-11 !px-2 text-dark dark:text-white-dark grid grid-cols-2 gap-y-2 font-semibold dark:text-white-light/90 w-[280px]">
                        <template x-for="item in languages">
                            <li>
                                <a href="javascript:;" class="hover:text-primary"
                                    @click="$store.app.toggleLocale(item.value),toggle()"
                                    :class="{ 'bg-primary/10 text-primary': $store.app.locale == item.value }">
                                    <img class="w-5 h-5 object-cover rounded-full"
                                        :src="`/assets/images/flags/${item.value.toUpperCase()}.svg`" alt="image" />
                                    <span class="ltr:ml-3 rtl:mr-3" x-text="item.key"></span>
                                </a>
                            </li>
                        </template>
                    </ul>
                </div>

                <div class="dropdown" x-data="dropdown" @click.outside="open = false">
                    <a href="{{ route('notifications.index') }}"
                        class="block p-2 rounded-full bg-white-light/40 dark:bg-dark/40 hover:text-primary hover:bg-white-light/90 dark:hover:bg-dark/60">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M22 10C22.0185 10.7271 22 11.0542 22 12C22 15.7712 22 17.6569 20.8284 18.8284C19.6569 20 17.7712 20 14 20H10C6.22876 20 4.34315 20 3.17157 18.8284C2 17.6569 2 15.7712 2 12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H13"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path
                                d="M6 8L8.1589 9.79908C9.99553 11.3296 10.9139 12.0949 12 12.0949C13.0861 12.0949 14.0045 11.3296 15.8411 9.79908"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <circle cx="19" cy="5" r="3" stroke="currentColor"
                                stroke-width="1.5" />
                        </svg>
                    </a>
                </div>
                <div class="dropdown" x-data="dropdown" @click.outside="open = false">
                    <a href="javascript:;"
                        class="relative block p-2 rounded-full bg-white-light/40 dark:bg-dark/40 hover:text-primary hover:bg-white-light/90 dark:hover:bg-dark/60"
                        @click="toggle">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M19.0001 9.7041V9C19.0001 5.13401 15.8661 2 12.0001 2C8.13407 2 5.00006 5.13401 5.00006 9V9.7041C5.00006 10.5491 4.74995 11.3752 4.28123 12.0783L3.13263 13.8012C2.08349 15.3749 2.88442 17.5139 4.70913 18.0116C9.48258 19.3134 14.5175 19.3134 19.291 18.0116C21.1157 17.5139 21.9166 15.3749 20.8675 13.8012L19.7189 12.0783C19.2502 11.3752 19.0001 10.5491 19.0001 9.7041Z"
                                stroke="currentColor" stroke-width="1.5" />
                            <path d="M7.5 19C8.15503 20.7478 9.92246 22 12 22C14.0775 22 15.845 20.7478 16.5 19"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M12 6V10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>

                        @php
                            $unreadCount = auth()->check() ? \App\Models\Notification::where('user_id', auth()->id())->whereNull('read_at')->count() : 0;
                        @endphp
                        @if($unreadCount > 0)
                        <span class="flex absolute w-3 h-3 ltr:right-0 rtl:left-0 top-0">
                            <span
                                class="animate-ping absolute ltr:-left-[3px] rtl:-right-[3px] -top-[3px] inline-flex h-full w-full rounded-full bg-success/50 opacity-75"></span>
                            <span class="relative inline-flex rounded-full w-[6px] h-[6px] bg-success"></span>
                        </span>
                        @endif
                    </a>
                    <ul x-cloak x-show="open" x-transition x-transition.duration.300ms
                        class="ltr:-right-2 rtl:-left-2 top-11 !py-0 text-dark dark:text-white-dark w-[300px] sm:w-[350px] divide-y dark:divide-white/10">
                        <li>
                            <div
                                class="flex items-center px-4 py-2 justify-between font-semibold hover:!bg-transparent">
                                <h4 class="text-lg">الإشعارات</h4>
                                @if($unreadCount > 0)
                                    <span class="badge bg-primary/80">{{ $unreadCount }} جديد</span>
                                @endif
                            </div>
                        </li>
                        @php
                            $latestNotifications = auth()->check() ? \App\Models\Notification::where('user_id', auth()->id())->orderBy('created_at', 'desc')->limit(5)->get() : collect([]);
                        @endphp
                        @forelse($latestNotifications as $notification)
                            <li class=" dark:text-white-light/90 ">
                                <div class="flex items-center px-4 py-2 group">
                                    <div class="grid place-content-center rounded">
                                        <div class="w-12 h-12 relative flex items-center justify-center bg-primary/10 rounded-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ltr:pl-3 rtl:pr-3 flex flex-auto">
                                        <div class="ltr:pr-3 rtl:pl-3">
                                            <h6 class="text-sm">{{ Str::limit($notification->message, 50) }}</h6>
                                            <span class="text-xs block font-normal dark:text-gray-500">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li>
                                <div class="!grid place-content-center hover:!bg-transparent text-lg min-h-[200px]">
                                    <div class="mx-auto ring-4 ring-primary/30 rounded-full mb-4 text-primary">
                                        <svg width="40" height="40" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.5"
                                                d="M20 10C20 4.47715 15.5228 0 10 0C4.47715 0 0 4.47715 0 10C0 15.5228 4.47715 20 10 20C15.5228 20 20 15.5228 20 10Z"
                                                fill="currentColor" />
                                            <path
                                                d="M10 4.25C10.4142 4.25 10.75 4.58579 10.75 5V11C10.75 11.4142 10.4142 11.75 10 11.75C9.58579 11.75 9.25 11.4142 9.25 11V5C9.25 4.58579 9.58579 4.25 10 4.25Z"
                                                fill="currentColor" />
                                            <path
                                                d="M10 15C10.5523 15 11 14.5523 11 14C11 13.4477 10.5523 13 10 13C9.44772 13 9 13.4477 9 14C9 14.5523 9.44772 15 10 15Z"
                                                fill="currentColor" />
                                        </svg>
                                    </div>
                                    لا توجد إشعارات
                                </div>
                            </li>
                        @endforelse
                        @if($latestNotifications->count() > 0)
                            <li>
                                <div class="p-4">
                                    <a href="{{ route('notifications.index') }}" class="btn btn-primary block w-full btn-small" @click="toggle">عرض جميع الإشعارات</a>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
                <div class="dropdown flex-shrink-0" x-data="dropdown" @click.outside="open = false">
                    <a href="javascript:;" class="relative group" @click="toggle()">
                        <span><img class="w-9 h-9 rounded-full object-cover saturate-50 group-hover:saturate-100"
                                src="/assets/images/user-profile.jpeg" alt="image" /></span>
                    </a>
                    <ul x-cloak x-show="open" x-transition x-transition.duration.300ms
                        class="ltr:right-0 rtl:left-0 text-dark dark:text-white-dark top-11 !py-0 w-[230px] font-semibold dark:text-white-light/90">
                        @auth
                        <li>
                            <div class="flex items-center px-4 py-4">
                                <div class="flex-none">
                                    <img class="rounded-md w-10 h-10 object-cover"
                                        src="/assets/images/user-profile.jpeg"
                                        alt="image" />
                                </div>
                                <div class="ltr:pl-4 rtl:pr-4 truncate">
                                    <h4 class="text-base">{{ auth()->user()->name }}
                                        @if(auth()->user()->role)
                                            <span class="text-xs bg-success-light rounded text-success px-1 ltr:ml-2 rtl:ml-2">{{ auth()->user()->role->name }}</span>
                                        @endif
                                    </h4>
                                    <span class="text-black/60 dark:text-dark-light/60 text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a href="{{ route('companies.index') }}" class="dark:hover:text-white" @click="toggle">
                                <svg class="w-4.5 h-4.5 ltr:mr-2 rtl:ml-2 shrink-0" width="18" height="18"
                                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.5"
                                        d="M2 12.2039C2 9.91549 2 8.77128 2.5192 7.82274C3.0384 6.87421 3.98695 6.28551 5.88403 5.10813L7.88403 3.86687C9.88939 2.62229 10.8921 2 12 2C13.1079 2 14.1106 2.62229 16.116 3.86687L18.116 5.10812C20.0131 6.28551 20.9616 6.87421 21.4808 7.82274C22 8.77128 22 9.91549 22 12.2039V13.725C22 17.6258 22 19.5763 20.8284 20.7881C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.7881C2 19.5763 2 17.6258 2 13.725V12.2039Z"
                                        fill="currentColor" />
                                    <path
                                        d="M9 17.25C8.58579 17.25 8.25 17.5858 8.25 18C8.25 18.4142 8.58579 18.75 9 18.75H15C15.4142 18.75 15.75 18.4142 15.75 18C15.75 17.5858 15.4142 17.25 15 17.25H9Z"
                                        fill="currentColor" />
                                </svg>
                                الشركات</a>
                        </li>
                        <li>
                            <a href="{{ route('notifications.index') }}" class="dark:hover:text-white" @click="toggle">
                                <svg class="w-4.5 h-4.5 ltr:mr-2 rtl:ml-2 shrink-0" width="18" height="18"
                                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M19.0001 9.7041V9C19.0001 5.13401 15.8661 2 12.0001 2C8.13407 2 5.00006 5.13401 5.00006 9V9.7041C5.00006 10.5491 4.74995 11.3752 4.28123 12.0783L3.13263 13.8012C2.08349 15.3749 2.88442 17.5139 4.70913 18.0116C9.48258 19.3134 14.5175 19.3134 19.291 18.0116C21.1157 17.5139 21.9166 15.3749 20.8675 13.8012L19.7189 12.0783C19.2502 11.3752 19.0001 10.5491 19.0001 9.7041Z"
                                        stroke="currentColor" stroke-width="1.5" />
                                    <path d="M7.5 19C8.15503 20.7478 9.92246 22 12 22C14.0775 22 15.845 20.7478 16.5 19"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                </svg>
                                الإشعارات</a>
                        </li>
                        <li id="pwa-install-button" class="border-t border-white-light dark:border-white-light/10">
                            <button type="button" class="text-primary !py-3 w-full text-right" @click="toggle">
                                <svg class="w-4.5 h-4.5 ltr:mr-2 rtl:ml-2 shrink-0" width="18" height="18"
                                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 2L13.09 8.26L20 9L13.09 9.74L12 16L10.91 9.74L4 9L10.91 8.26L12 2Z" 
                                            fill="currentColor" />
                                    <path d="M19 15L20.09 19.26L24 20L20.09 20.74L19 25L17.91 20.74L14 20L17.91 19.26L19 15Z" 
                                            fill="currentColor" />
                                </svg>
                                تثبيت التطبيق
                            </button>
                        </li>
                        <li class="border-t border-white-light dark:border-white-light/10">
                            <button type="button" class="text-info !py-3 w-full text-right" @click="toggle" onclick="window.pwaManager.showPWAInfo(); window.pwaManager.checkPWARequirements();">
                                <svg class="w-4.5 h-4.5 ltr:mr-2 rtl:ml-2 shrink-0" width="18" height="18"
                                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" 
                                            fill="currentColor" />
                                </svg>
                                تشخيص PWA
                            </button>
                        </li>
                        <li class="border-t border-white-light dark:border-white-light/10">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="text-danger !py-3 w-full text-right" @click="toggle">
                                <svg class="w-4.5 h-4.5 ltr:mr-2 rtl:ml-2 shrink-0 rotate-90" width="18" height="18"
                                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.5"
                                        d="M17 9.00195C19.175 9.01406 20.3529 9.11051 21.1213 9.8789C22 10.7576 22 12.1718 22 15.0002V16.0002C22 18.8286 22 20.2429 21.1213 21.1215C20.2426 22.0002 18.8284 22.0002 16 22.0002H8C5.17157 22.0002 3.75736 22.0002 2.87868 21.1215C2 20.2429 2 18.8286 2 16.0002L2 15.0002C2 12.1718 2 10.7576 2.87868 9.87889C3.64706 9.11051 4.82497 9.01406 7 9.00195"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                    <path d="M12 15L12 2M12 2L15 5.5M12 2L9 5.5" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                    تسجيل الخروج
                                </button>
                            </form>
                        </li>
                        @else
                        <li>
                            <a href="{{ route('login') }}" class="dark:hover:text-white">
                                تسجيل الدخول
                            </a>
                        </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </div>

        <!-- horizontal menu -->
        @php
            // تحديد الشركة الحالية إذا كانت موجودة في الرابط
            $currentCompany = null;
            if (request()->route('company')) {
                $currentCompany = request()->route('company');
            }
        @endphp
        <x-common.horizontal-menu :company="$currentCompany" />
    </div>
</header>
<script>
    document.addEventListener("alpine:init", () => {
        Alpine.data("header", () => ({
            init() {
                const selector = document.querySelector('ul.horizontal-menu a[href="' + window
                    .location.pathname + '"]');
                if (selector) {
                    selector.classList.add('active');
                    const ul = selector.closest('ul.sub-menu');
                    if (ul) {
                        let ele = ul.closest('li.menu').querySelectorAll('.nav-link');
                        if (ele) {
                            ele = ele[0];
                            setTimeout(() => {
                                ele.classList.add('active');
                            });
                        }
                    }
                }
            },

            notifications: [],
            messages: [],

            languages: [
                {
                    id: 1,
                    key: 'العربية',
                    value: 'ae',
                },
                {
                    id: 2,
                    key: 'English',
                    value: 'en',
                },
            ],


        }));
    });

    // Smart Search Function
    function smartSearch() {
        return {
            searchQuery: '',
            searchOpen: false,
            searching: false,
            selectedIndex: 0,
            results: {
                invoices: [],
                shipments: [],
                banks: [],
                beneficiaries: [],
                pages: []
            },

            async search() {
                if (this.searchQuery.length < 2) {
                    this.results = {
                        invoices: [],
                        shipments: [],
                        banks: [],
                        beneficiaries: [],
                        pages: []
                    };
                    return;
                }

                this.searching = true;
                try {
                    const response = await fetch(`/search?q=${encodeURIComponent(this.searchQuery)}`);
                    this.results = await response.json();
                } catch (error) {
                    console.error('Search error:', error);
                }
                this.searching = false;
                this.selectedIndex = 0;
            },

            hasResults() {
                return Object.values(this.results).some(arr => arr && arr.length > 0);
            },

            closeSearch() {
                this.searchOpen = false;
            },

            clearSearch() {
                this.searchQuery = '';
                this.results = {
                    invoices: [],
                    shipments: [],
                    banks: [],
                    beneficiaries: [],
                    pages: []
                };
            },

            getAllItems() {
                return [
                    ...(this.results.invoices || []),
                    ...(this.results.shipments || []),
                    ...(this.results.banks || []),
                    ...(this.results.beneficiaries || []),
                    ...(this.results.pages || [])
                ];
            },

            getItemIndex(type, index) {
                let currentIndex = 0;
                const types = ['invoices', 'shipments', 'banks', 'beneficiaries', 'pages'];

                for (let t of types) {
                    if (t === type) {
                        return currentIndex + index;
                    }
                    currentIndex += (this.results[t] || []).length;
                }
                return -1;
            },

            navigateDown() {
                const items = this.getAllItems();
                if (this.selectedIndex < items.length - 1) {
                    this.selectedIndex++;
                }
            },

            navigateUp() {
                if (this.selectedIndex > 0) {
                    this.selectedIndex--;
                }
            },

            navigateToSelected() {
                const items = this.getAllItems();
                if (items[this.selectedIndex]) {
                    window.location.href = items[this.selectedIndex].url;
                }
            },

            navigateToFirst() {
                const items = this.getAllItems();
                if (items.length > 0) {
                    window.location.href = items[0].url;
                }
            }
        };
    }
</script>
