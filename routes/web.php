<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\BeneficiaryController;
use App\Http\Controllers\SearchController;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard', [AuthController::class, 'dashboard']);

    // Companies Management
    Route::resource('companies', CompanyController::class);

    // Banks Management
    Route::resource('companies.banks', BankController::class);
    Route::post('companies/{company}/banks/{bank}/deposit', [BankController::class, 'deposit'])->name('companies.banks.deposit');
    Route::post('companies/{company}/banks/{bank}/withdraw', [BankController::class, 'withdraw'])->name('companies.banks.withdraw');

    // Invoices Management
    Route::resource('companies.invoices', InvoiceController::class);
    Route::post('companies/{company}/invoices/{invoice}/pay', [InvoiceController::class, 'processPayment'])->name('companies.invoices.pay');
    Route::post('companies/{company}/invoices/{invoice}/attach-shipment', [InvoiceController::class, 'attachShipment'])->name('companies.invoices.attach-shipment');

    // Invoice PDF Routes
    Route::get('companies/{company}/invoices/{invoice}/pdf', [InvoiceController::class, 'viewPdf'])->name('companies.invoices.pdf');
    Route::get('companies/{company}/invoices/{invoice}/download', [InvoiceController::class, 'downloadPdf'])->name('companies.invoices.download');
    Route::get('companies/{company}/invoices/{invoice}/share-link', [InvoiceController::class, 'getShareLink'])->name('companies.invoices.share-link');

    // Shipments Management
    Route::resource('companies.shipments', ShipmentController::class);
    Route::patch('companies/{company}/shipments/{shipment}/update-received-status', [ShipmentController::class, 'updateReceivedStatus'])->name('companies.shipments.update-received-status');
    Route::patch('companies/{company}/shipments/{shipment}/update-entry-status', [ShipmentController::class, 'updateEntryStatus'])->name('companies.shipments.update-entry-status');
    Route::patch('companies/{company}/shipments/{shipment}/update-entry-permit-status', [ShipmentController::class, 'updateEntryPermitStatus'])->name('companies.shipments.update-entry-permit-status');
    Route::post('companies/{company}/shipments/{shipment}/attach-invoice', [ShipmentController::class, 'attachInvoice'])->name('companies.shipments.attach-invoice');

    // Beneficiaries Routes
    Route::resource('companies.beneficiaries', BeneficiaryController::class);

    // Quick Update for Shipments
    Route::get('companies/{company}/shipments-quick-update', [\App\Http\Controllers\QuickUpdateController::class, 'index'])->name('companies.shipments.quick-update');
    Route::patch('companies/{company}/shipments/{shipment}/quick-update-shipping', [\App\Http\Controllers\QuickUpdateController::class, 'updateShippingStatus'])->name('companies.shipments.quick-update-shipping');
    Route::patch('companies/{company}/shipments/{shipment}/quick-update-received', [\App\Http\Controllers\QuickUpdateController::class, 'updateReceivedStatusQuick'])->name('companies.shipments.quick-update-received');
    Route::patch('companies/{company}/shipments/{shipment}/quick-update-entry', [\App\Http\Controllers\QuickUpdateController::class, 'updateEntryStatusQuick'])->name('companies.shipments.quick-update-entry');
    Route::patch('companies/{company}/shipments/{shipment}/quick-update-entry-permit', [\App\Http\Controllers\QuickUpdateController::class, 'updateEntryPermitStatusQuick'])->name('companies.shipments.quick-update-entry-permit');

    // Notifications
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('notifications/{notification}/resend-email', [NotificationController::class, 'resendEmail'])->name('notifications.resend-email');
    Route::get('notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');

    // Users Management
    Route::resource('users', UserController::class);
    Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Roles Management
    Route::resource('roles', RoleController::class);

    // Permissions Management
    Route::resource('permissions', PermissionController::class);

    // Global Search
    Route::get('/search', [SearchController::class, 'globalSearch'])->name('search.global');
});

// Original template routes (keeping for design reference)
Route::view('/analytics', 'analytics');
Route::view('/finance', 'finance');
Route::view('/crypto', 'crypto');

Route::view('/apps/chat', 'apps.chat');
Route::view('/apps/mailbox', 'apps.mailbox');
Route::view('/apps/todolist', 'apps.todolist');
Route::view('/apps/notes', 'apps.notes');
Route::view('/apps/scrumboard', 'apps.scrumboard');
Route::view('/apps/contacts', 'apps.contacts');
Route::view('/apps/calendar', 'apps.calendar');

Route::view('/apps/invoice/list', 'apps.invoice.list');
Route::view('/apps/invoice/preview', 'apps.invoice.preview');
Route::view('/apps/invoice/add', 'apps.invoice.add');
Route::view('/apps/invoice/edit', 'apps.invoice.edit');

Route::view('/components/tabs', 'ui-components.tabs');
Route::view('/components/accordions', 'ui-components.accordions');
Route::view('/components/modals', 'ui-components.modals');
Route::view('/components/cards', 'ui-components.cards');
Route::view('/components/carousel', 'ui-components.carousel');
Route::view('/components/countdown', 'ui-components.countdown');
Route::view('/components/counter', 'ui-components.counter');
Route::view('/components/sweetalert', 'ui-components.sweetalert');
Route::view('/components/timeline', 'ui-components.timeline');
Route::view('/components/notifications', 'ui-components.notifications');
Route::view('/components/media-object', 'ui-components.media-object');
Route::view('/components/list-group', 'ui-components.list-group');
Route::view('/components/pricing-table', 'ui-components.pricing-table');
Route::view('/components/lightbox', 'ui-components.lightbox');

Route::view('/elements/alerts', 'elements.alerts');
Route::view('/elements/avatar', 'elements.avatar');
Route::view('/elements/badges', 'elements.badges');
Route::view('/elements/breadcrumbs', 'elements.breadcrumbs');
Route::view('/elements/buttons', 'elements.buttons');
Route::view('/elements/buttons-group', 'elements.buttons-group');
Route::view('/elements/color-library', 'elements.color-library');
Route::view('/elements/dropdown', 'elements.dropdown');
Route::view('/elements/infobox', 'elements.infobox');
Route::view('/elements/jumbotron', 'elements.jumbotron');
Route::view('/elements/loader', 'elements.loader');
Route::view('/elements/pagination', 'elements.pagination');
Route::view('/elements/popovers', 'elements.popovers');
Route::view('/elements/progressbar', 'elements.progressbar');
Route::view('/elements/search', 'elements.search');
Route::view('/elements/tooltips', 'elements.tooltips');
Route::view('/elements/treeview', 'elements.treeview');
Route::view('/elements/typography', 'elements.typography');

Route::view('/datatables/basic', 'datatables.basic');
Route::view('/datatables/advanced', 'datatables.advanced');
Route::view('/datatables/skin', 'datatables.skin');
Route::view('/datatables/order-sorting', 'datatables.order-sorting');
Route::view('/datatables/multi-column', 'datatables.multi-column');
Route::view('/datatables/multiple-tables', 'datatables.multiple-tables');
Route::view('/datatables/alt-pagination', 'datatables.alt-pagination');
Route::view('/datatables/checkbox', 'datatables.checkbox');
Route::view('/datatables/range-search', 'datatables.range-search');
Route::view('/datatables/export', 'datatables.export');
Route::view('/datatables/column-chooser', 'datatables.column-chooser');

Route::view('/forms/basic', 'forms.basic');
Route::view('/forms/input-group', 'forms.input-group');
Route::view('/forms/layouts', 'forms.layouts');
Route::view('/forms/validation', 'forms.validation');
Route::view('/forms/input-mask', 'forms.input-mask');
Route::view('/forms/select2', 'forms.select2');
Route::view('/forms/touchspin', 'forms.touchspin');
Route::view('/forms/checkbox-radio', 'forms.checkbox-radio');
Route::view('/forms/switches', 'forms.switches');
Route::view('/forms/wizards', 'forms.wizards');
Route::view('/forms/file-upload', 'forms.file-upload');
Route::view('/forms/quill-editor', 'forms.quill-editor');
Route::view('/forms/markdown-editor', 'forms.markdown-editor');
Route::view('/forms/date-picker', 'forms.date-picker');
Route::view('/forms/clipboard', 'forms.clipboard');

Route::view('/users/profile', 'users.profile');
Route::view('/users/user-account-settings', 'users.user-account-settings');

Route::view('/pages/knowledge-base', 'pages.knowledge-base');
Route::view('/pages/contact-us-boxed', 'pages.contact-us-boxed');
Route::view('/pages/contact-us-cover', 'pages.contact-us-cover');
Route::view('/pages/faq', 'pages.faq');
Route::view('/pages/coming-soon-boxed', 'pages.coming-soon-boxed');
Route::view('/pages/coming-soon-cover', 'pages.coming-soon-cover');
Route::view('/pages/maintenence', 'pages.maintenence');

Route::view('/auth/boxed-signin', 'auth.boxed-signin');
Route::view('/auth/boxed-signup', 'auth.boxed-signup');
Route::view('/auth/boxed-lockscreen', 'auth.boxed-lockscreen');
Route::view('/auth/boxed-password-reset', 'auth.boxed-password-reset');
Route::view('/auth/cover-login', 'auth.cover-login');
Route::view('/auth/cover-register', 'auth.cover-register');
Route::view('/auth/cover-lockscreen', 'auth.cover-lockscreen');
Route::view('/auth/cover-password-reset', 'auth.cover-password-reset');

Route::view('/tables/basic', 'tables.basic');

Route::view('/font-icons', 'font-icons');
Route::view('/dragndrop', 'dragndrop');
Route::view('/charts', 'charts');
Route::view('/widgets', 'widgets');
Route::view('/elements/progress-bar', 'elements.progress-bar');
Route::view('/elements/search', 'elements.search');
Route::view('/elements/tooltips', 'elements.tooltips');
Route::view('/elements/treeview', 'elements.treeview');
Route::view('/elements/typography', 'elements.typography');

Route::view('/charts', 'charts');
Route::view('/widgets', 'widgets');
Route::view('/font-icons', 'font-icons');
Route::view('/dragndrop', 'dragndrop');

Route::view('/tables', 'tables');

Route::view('/datatables/advanced', 'datatables.advanced');
Route::view('/datatables/alt-pagination', 'datatables.alt-pagination');
Route::view('/datatables/basic', 'datatables.basic');
Route::view('/datatables/checkbox', 'datatables.checkbox');
Route::view('/datatables/clone-header', 'datatables.clone-header');
Route::view('/datatables/column-chooser', 'datatables.column-chooser');
Route::view('/datatables/export', 'datatables.export');
Route::view('/datatables/multi-column', 'datatables.multi-column');
Route::view('/datatables/multiple-tables', 'datatables.multiple-tables');
Route::view('/datatables/order-sorting', 'datatables.order-sorting');
Route::view('/datatables/range-search', 'datatables.range-search');
Route::view('/datatables/skin', 'datatables.skin');
Route::view('/datatables/sticky-header', 'datatables.sticky-header');

Route::view('/forms/basic', 'forms.basic');
Route::view('/forms/input-group', 'forms.input-group');
Route::view('/forms/layouts', 'forms.layouts');
Route::view('/forms/validation', 'forms.validation');
Route::view('/forms/input-mask', 'forms.input-mask');
Route::view('/forms/select2', 'forms.select2');
Route::view('/forms/touchspin', 'forms.touchspin');
Route::view('/forms/checkbox-radio', 'forms.checkbox-radio');
Route::view('/forms/switches', 'forms.switches');
Route::view('/forms/wizards', 'forms.wizards');
Route::view('/forms/file-upload', 'forms.file-upload');
Route::view('/forms/quill-editor', 'forms.quill-editor');
Route::view('/forms/markdown-editor', 'forms.markdown-editor');
Route::view('/forms/date-picker', 'forms.date-picker');
Route::view('/forms/clipboard', 'forms.clipboard');

Route::view('/users/profile', 'users.profile');
Route::view('/users/user-account-settings', 'users.user-account-settings');

Route::view('/pages/knowledge-base', 'pages.knowledge-base');
Route::view('/pages/contact-us-boxed', 'pages.contact-us-boxed');
Route::view('/pages/contact-us-cover', 'pages.contact-us-cover');
Route::view('/pages/faq', 'pages.faq');
Route::view('/pages/coming-soon-boxed', 'pages.coming-soon-boxed');
Route::view('/pages/coming-soon-cover', 'pages.coming-soon-cover');
Route::view('/pages/error404', 'pages.error404');
Route::view('/pages/error500', 'pages.error500');
Route::view('/pages/error503', 'pages.error503');
Route::view('/pages/maintenence', 'pages.maintenence');

Route::view('/auth/boxed-lockscreen', 'auth.boxed-lockscreen');
Route::view('/auth/boxed-signin', 'auth.boxed-signin');
Route::view('/auth/boxed-signup', 'auth.boxed-signup');
Route::view('/auth/boxed-password-reset', 'auth.boxed-password-reset');
Route::view('/auth/cover-login', 'auth.cover-login');
Route::view('/auth/cover-register', 'auth.cover-register');
Route::view('/auth/cover-lockscreen', 'auth.cover-lockscreen');
Route::view('/auth/cover-password-reset', 'auth.cover-password-reset');
