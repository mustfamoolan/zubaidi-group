console.log('PWA Manager script loaded');
console.log('Script execution started');
console.log('Defining window.installPWA function...');

// Simple PWA installation function
window.installPWA = function() {
    console.log('Install PWA button clicked');
    console.log('Function called successfully');

    // Check if browser supports PWA installation
    if ('serviceWorker' in navigator) {
        console.log('Service Worker supported');
        // Register service worker if not already registered
        navigator.serviceWorker.register('/sw.js')
            .then(registration => {
                console.log('Service Worker registered:', registration);

                // Check if app can be installed
                if (window.deferredPrompt) {
                    console.log('Deferred prompt available, showing install prompt');
                    window.deferredPrompt.prompt();
                    window.deferredPrompt.userChoice.then(choiceResult => {
                        if (choiceResult.outcome === 'accepted') {
                            console.log('User accepted the install prompt');
                            showInstallSuccess();
                        } else {
                            console.log('User dismissed the install prompt');
                        }
                        window.deferredPrompt = null;
                    });
                } else {
                    console.log('No deferred prompt available, showing instructions');
                    // Show manual installation instructions
                    showInstallInstructions();
                }
            })
            .catch(error => {
                console.log('Service Worker registration failed:', error);
                showInstallInstructions();
            });
    } else {
        console.log('Service Worker not supported');
        showInstallInstructions();
    }
};

// Listen for beforeinstallprompt event
window.addEventListener('beforeinstallprompt', (e) => {
    console.log('PWA install prompt available');
    e.preventDefault();
    window.deferredPrompt = e;
    console.log('Deferred prompt stored');
});

// Listen for appinstalled event
window.addEventListener('appinstalled', () => {
    console.log('PWA was installed');
    showInstallSuccess();
});

// Show success message
function showInstallSuccess() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'تم التثبيت بنجاح!',
            text: 'تم تثبيت التطبيق على جهازك بنجاح',
            icon: 'success',
            confirmButtonText: 'حسناً',
            timer: 3000
        });
    }
}

// Show installation instructions
function showInstallInstructions() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'تثبيت التطبيق',
            html: `
                <div class="text-right">
                    <p><strong>للتثبيت على الموبايل:</strong></p>
                    <p>• اضغط على "إضافة إلى الشاشة الرئيسية" في المتصفح</p>
                    <p>• أو اضغط على زر التثبيت في شريط العنوان</p>
                    <br>
                    <p><strong>للتثبيت على الكمبيوتر:</strong></p>
                    <p>• اضغط على أيقونة التثبيت في شريط العنوان</p>
                    <p>• أو استخدم قائمة المتصفح: المزيد > تثبيت التطبيق</p>
                </div>
            `,
            icon: 'info',
            confirmButtonText: 'حسناً'
        });
    }
}

console.log('PWA Manager initialized successfully');
console.log('window.installPWA function available:', typeof window.installPWA === 'function');
console.log('All PWA functions ready');
