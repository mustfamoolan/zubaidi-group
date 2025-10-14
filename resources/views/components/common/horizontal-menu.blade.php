@props(['company' => null])

<!-- horizontal menu -->
<ul class="horizontal-menu hidden py-1.5 font-semibold px-6 lg:space-x-1.5 xl:space-x-8 rtl:space-x-reverse bg-white border-t border-[#ebedf2] dark:border-[#191e3a] dark:bg-[#0e1726] text-black dark:text-white-dark">

    <!-- الشركات -->
    <li class="menu nav-item relative">
        <a href="{{ route('companies.index') }}" class="nav-link">
            <div class="flex items-center">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="shrink-0">
                    <path opacity="0.5" d="M2 12.2039C2 9.91549 2 8.77128 2.5192 7.82274C3.0384 6.87421 3.98695 6.28551 5.88403 5.10813L7.88403 3.86687C9.88939 2.62229 10.8921 2 12 2C13.1079 2 14.1106 2.62229 16.116 3.86687L18.116 5.10812C20.0131 6.28551 20.9616 6.87421 21.4808 7.82274C22 8.77128 22 9.91549 22 12.2039V13.725C22 17.6258 22 19.5763 20.8284 20.7881C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.7881C2 19.5763 2 17.6258 2 13.725V12.2039Z" fill="currentColor" />
                    <path d="M9 17.25C8.58579 17.25 8.25 17.5858 8.25 18C8.25 18.4142 8.58579 18.75 9 18.75H15C15.4142 18.75 15.75 18.4142 15.75 18C15.75 17.5858 15.4142 17.25 15 17.25H9Z" fill="currentColor" />
                </svg>
                <span class="px-1">الشركات</span>
            </div>
        </a>
    </li>

    @if($company)
    <!-- لوحة التحكم -->
    <li class="menu nav-item relative">
        <a href="{{ route('companies.show', $company) }}" class="nav-link">
            <div class="flex items-center">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="shrink-0">
                    <path opacity="0.5" d="M2 12.2039C2 9.91549 2 8.77128 2.5192 7.82274C3.0384 6.87421 3.98695 6.28551 5.88403 5.10813L7.88403 3.86687C9.88939 2.62229 10.8921 2 12 2C13.1079 2 14.1106 2.62229 16.116 3.86687L18.116 5.10812C20.0131 6.28551 20.9616 6.87421 21.4808 7.82274C22 8.77128 22 9.91549 22 12.2039V13.725C22 17.6258 22 19.5763 20.8284 20.7881C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.7881C2 19.5763 2 17.6258 2 13.725V12.2039Z" fill="currentColor" />
                    <path d="M9 17.25C8.58579 17.25 8.25 17.5858 8.25 18C8.25 18.4142 8.58579 18.75 9 18.75H15C15.4142 18.75 15.75 18.4142 15.75 18C15.75 17.5858 15.4142 17.25 15 17.25H9Z" fill="currentColor" />
                </svg>
                <span class="px-1">لوحة التحكم</span>
            </div>
        </a>
    </li>

    <!-- المصارف -->
    <li class="menu nav-item relative">
        <a href="{{ route('companies.banks.index', $company) }}" class="nav-link">
            <div class="flex items-center">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="shrink-0">
                    <path d="M2 8.50488H22" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M13.0262 2L12.9433 2.44427C12.6361 3.9442 12.4826 4.69416 12.0074 5.18934C11.9659 5.2323 11.9225 5.27361 11.8775 5.31317C11.3602 5.74646 10.5943 5.84446 9.06261 6.04046L6.3801 6.38734C4.80568 6.58858 4.01847 6.6892 3.53262 7.19469C3.04677 7.70018 3.04677 8.4663 3.04677 10L3.04677 13.5442C3.04677 17.7892 3.04677 19.9117 4.34316 21.2054C5.63955 22.4991 7.76616 22.4991 11.0194 22.4991H13.0315C16.2848 22.4991 18.4114 22.4991 19.7078 21.2054C21.0042 19.9117 21.0042 17.7892 21.0042 13.5442V10C21.0042 8.4663 21.0042 7.70018 20.5183 7.19469C20.0325 6.6892 19.2453 6.58858 17.6709 6.38734L14.9883 6.04046C13.4567 5.84446 12.6908 5.74646 12.1735 5.31317C12.1285 5.27361 12.085 5.2323 12.0436 5.18934C11.5684 4.69416 11.4148 3.9442 11.1077 2.44427L11.0247 2C11.0247 2 13.0262 2 13.0262 2Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                    <circle cx="17.5" cy="11.4243" r="1" fill="currentColor"/>
                </svg>
                <span class="px-1">المصارف</span>
            </div>
        </a>
    </li>

    <!-- الفواتير -->
    <li class="menu nav-item relative">
        <a href="{{ route('companies.invoices.index', $company) }}" class="nav-link">
            <div class="flex items-center">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="shrink-0">
                    <path d="M22 10V12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12V10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M12 2L12 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M12 12L16 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M12 12L8 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                <span class="px-1">الفواتير</span>
            </div>
        </a>
    </li>

    <!-- الشحنات -->
    <li class="menu nav-item relative">
        <a href="javascript:;" class="nav-link">
            <div class="flex items-center">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="shrink-0">
                    <path d="M20.5 7.27783L12 12.0001M12 12.0001L3.49997 7.27783M12 12.0001L12 21.5001" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    <path opacity="0.5" d="M21 16.0586V7.94153C21 7.59889 21 7.42757 20.9495 7.27477C20.9049 7.13959 20.8318 7.01551 20.7354 6.91082C20.6263 6.79248 20.4766 6.70928 20.177 6.54288L12.777 2.43177C12.4934 2.27421 12.3516 2.19543 12.2015 2.16454C12.0685 2.13721 11.9315 2.13721 11.7986 2.16454C11.6484 2.19543 11.5066 2.27421 11.223 2.43177L3.82297 6.54288C3.52345 6.70928 3.37369 6.79248 3.26463 6.91082C3.16816 7.01551 3.09515 7.13959 3.05048 7.27477C3 7.42757 3 7.59889 3 7.94153V16.0586C3 16.4012 3 16.5725 3.05048 16.7253C3.09515 16.8605 3.16816 16.9846 3.26463 17.0893C3.37369 17.2076 3.52345 17.2908 3.82297 17.4572L11.223 21.5683C11.5066 21.7259 11.6484 21.8047 11.7986 21.8356C11.9315 21.8629 12.0685 21.8629 12.2015 21.8356C12.3516 21.8047 12.4934 21.7259 12.777 21.5683L20.177 17.4572C20.4766 17.2908 20.6263 17.2076 20.7354 17.0893C20.8318 16.9846 20.9049 16.8605 20.9495 16.7253C21 16.5725 21 16.4012 21 16.0586Z" stroke="currentColor" stroke-width="1.5"/>
                </svg>
                <span class="px-1">الشحنات</span>
            </div>
            <div class="right_arrow">
                <svg class="w-4 h-4 rotate-90" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
        </a>
        <ul class="sub-menu">
            <li>
                <a href="{{ route('companies.shipments.index', $company) }}">قائمة الشحنات</a>
            </li>
            <li>
                <a href="{{ route('companies.shipments.quick-update', $company) }}">التحديث السريع</a>
            </li>
        </ul>
    </li>
    @endif

    <!-- الإدارة -->
    <li class="menu nav-item relative">
        <a href="javascript:;" class="nav-link">
            <div class="flex items-center">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="shrink-0">
                    <circle cx="12" cy="6" r="4" stroke="currentColor" stroke-width="1.5"/>
                    <ellipse opacity="0.5" cx="12" cy="17" rx="7" ry="4" stroke="currentColor" stroke-width="1.5"/>
                </svg>
                <span class="px-1">الإدارة</span>
            </div>
            <div class="right_arrow">
                <svg class="w-4 h-4 rotate-90" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
        </a>
        <ul class="sub-menu">
            <li>
                <a href="{{ route('users.index') }}">المستخدمين</a>
            </li>
            <li>
                <a href="{{ route('roles.index') }}">الأدوار</a>
            </li>
            <li>
                <a href="{{ route('permissions.index') }}">الصلاحيات</a>
            </li>
        </ul>
    </li>

    <!-- الإشعارات -->
    <li class="menu nav-item relative">
        <a href="{{ route('notifications.index') }}" class="nav-link">
            <div class="flex items-center">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="shrink-0">
                    <path d="M19.0001 9.7041V9C19.0001 5.13401 15.8661 2 12.0001 2C8.13407 2 5.00006 5.13401 5.00006 9V9.7041C5.00006 10.5491 4.74995 11.3752 4.28123 12.0783L3.13263 13.8012C2.08349 15.3749 2.88442 17.5139 4.70913 18.0116C9.48258 19.3134 14.5175 19.3134 19.291 18.0116C21.1157 17.5139 21.9166 15.3749 20.8675 13.8012L19.7189 12.0783C19.2502 11.3752 19.0001 10.5491 19.0001 9.7041Z" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M7.5 19C8.15503 20.7478 9.92246 22 12 22C14.0775 22 15.845 20.7478 16.5 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                <span class="px-1">الإشعارات</span>
            </div>
        </a>
    </li>
</ul>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // تفعيل الرابط النشط في القائمة الأفقية
        const currentPath = window.location.pathname;
        const horizontalLinks = document.querySelectorAll('ul.horizontal-menu a[href]');

        horizontalLinks.forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('active');

                // إذا كان الرابط داخل sub-menu، نفعل القائمة الأب
                const subMenu = link.closest('ul.sub-menu');
                if (subMenu) {
                    const parentLink = subMenu.previousElementSibling;
                    if (parentLink) {
                        parentLink.classList.add('active');
                    }
                }
            }
        });
    });
</script>

