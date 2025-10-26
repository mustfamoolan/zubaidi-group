class PWAManager {
    constructor() {
        this.deferredPrompt = null;
        this.isInstalled = false;
        this.init();
    }

    init() {
        // Register service worker
        this.registerServiceWorker();

        // Listen for beforeinstallprompt event
        window.addEventListener('beforeinstallprompt', (e) => {
            console.log('PWA install prompt available');
            e.preventDefault();
            this.deferredPrompt = e;
            this.showInstallButton();
        });

        // Listen for appinstalled event
        window.addEventListener('appinstalled', () => {
            console.log('PWA was installed');
            this.isInstalled = true;
            this.hideInstallButton();
            this.showInstallSuccessMessage();
        });

        // Check if app is already installed
        this.checkIfInstalled();
        
        // Check PWA requirements
        this.checkPWARequirements();
        
        // Always show install button for testing
        this.showInstallButton();
    }

    async registerServiceWorker() {
        if ('serviceWorker' in navigator) {
            try {
                const registration = await navigator.serviceWorker.register('/sw.js', {
                    scope: '/'
                });
                console.log('Service Worker registered successfully:', registration);
                
                // Check for updates
                registration.addEventListener('updatefound', () => {
                    const newWorker = registration.installing;
                    if (newWorker) {
                        newWorker.addEventListener('statechange', () => {
                            if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                this.showUpdateAvailable();
                            }
                        });
                    }
                });
                
                // Force update check
                await registration.update();
                
            } catch (error) {
                console.log('Service Worker registration failed:', error);
            }
        } else {
            console.log('Service Worker not supported');
        }
    }

    checkIfInstalled() {
        // Check if running in standalone mode
        if (window.matchMedia('(display-mode: standalone)').matches ||
            window.navigator.standalone === true) {
            this.isInstalled = true;
            this.hideInstallButton();
        }
    }

    showInstallButton() {
        const installButton = document.getElementById('pwa-install-button');
        if (installButton) {
            installButton.style.display = 'block';
            installButton.addEventListener('click', () => this.installApp());
        }
    }

    hideInstallButton() {
        const installButton = document.getElementById('pwa-install-button');
        if (installButton) {
            installButton.style.display = 'none';
        }
    }

    async installApp() {
        if (this.deferredPrompt) {
            // Show the install prompt
            this.deferredPrompt.prompt();

            // Wait for the user to respond to the prompt
            const { outcome } = await this.deferredPrompt.userChoice;

            if (outcome === 'accepted') {
                console.log('User accepted the install prompt');
                this.showInstallSuccessMessage();
            } else {
                console.log('User dismissed the install prompt');
            }

            // Clear the deferredPrompt
            this.deferredPrompt = null;
        } else {
            // Fallback for browsers that don't support beforeinstallprompt
            this.showInstallInstructions();
        }
    }

    showInstallSuccessMessage() {
        // Show success message
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

    showInstallInstructions() {
        // Show installation instructions
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

    showUpdateAvailable() {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'تحديث متاح',
                text: 'يوجد تحديث جديد للتطبيق. هل تريد تحديثه الآن؟',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'تحديث',
                cancelButtonText: 'لاحقاً'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.reload();
                }
            });
        }
    }

    // Method to manually check for updates
    async checkForUpdates() {
        if ('serviceWorker' in navigator) {
            const registration = await navigator.serviceWorker.getRegistration();
            if (registration) {
                await registration.update();
            }
        }
    }

    // Method to show PWA info
    showPWAInfo() {
        const info = {
            isInstalled: this.isInstalled,
            isStandalone: window.matchMedia('(display-mode: standalone)').matches,
            hasServiceWorker: 'serviceWorker' in navigator,
            canInstall: !!this.deferredPrompt,
            userAgent: navigator.userAgent,
            isHTTPS: location.protocol === 'https:' || location.hostname === 'localhost'
        };
        
        console.log('PWA Status:', info);
        return info;
    }

    // Method to check PWA requirements
    checkPWARequirements() {
        const requirements = {
            https: location.protocol === 'https:' || location.hostname === 'localhost',
            serviceWorker: 'serviceWorker' in navigator,
            manifest: document.querySelector('link[rel="manifest"]') !== null,
            icons: document.querySelectorAll('link[rel="icon"], link[rel="apple-touch-icon"]').length > 0
        };
        
        const allMet = Object.values(requirements).every(req => req);
        
        console.log('PWA Requirements:', requirements, 'All met:', allMet);
        
        if (!allMet) {
            this.showRequirementsNotMet(requirements);
        }
        
        return { requirements, allMet };
    }

    showRequirementsNotMet(requirements) {
        const missing = Object.entries(requirements)
            .filter(([key, value]) => !value)
            .map(([key]) => key);
            
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'متطلبات PWA غير مكتملة',
                html: `
                    <div class="text-right">
                        <p>المتطلبات المفقودة:</p>
                        <ul>
                            ${missing.map(req => `<li>${req}</li>`).join('')}
                        </ul>
                        <p>يرجى التأكد من أن الموقع يعمل على HTTPS وأن جميع الملفات متوفرة.</p>
                    </div>
                `,
                icon: 'warning',
                confirmButtonText: 'حسناً'
            });
        }
    }
}

// Initialize PWA Manager when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.pwaManager = new PWAManager();
});

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = PWAManager;
}
