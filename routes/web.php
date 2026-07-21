<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ResellerController;
use Illuminate\Support\Facades\Route;

// Public Marketing Pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/hosting', [HomeController::class, 'hosting'])->name('hosting.index');
Route::get('/domains/search', [HomeController::class, 'domainSearch'])->name('domains.search');

// Shopping Cart Dedicated Page & Actions
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::post('/cart/plan/remove', [CartController::class, 'removePlan'])->name('cart.plan.remove');
Route::post('/cart/domain/remove', [CartController::class, 'removeDomain'])->name('cart.domain.remove');

// Checkout & Order System Routes
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/plan', [CheckoutController::class, 'selectPlan'])->name('checkout.plan');
Route::post('/checkout/domain/remove', [CheckoutController::class, 'removeDomain'])->name('checkout.domain.remove');
Route::post('/checkout/coupon', [CheckoutController::class, 'applyCoupon'])->name('checkout.coupon');
Route::post('/checkout/coupon/remove', [CheckoutController::class, 'removeCoupon'])->name('checkout.coupon.remove');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success/{invoice}', [CheckoutController::class, 'success'])->name('checkout.success');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/auth/quick/{role}', [AuthController::class, 'quickLogin'])->name('auth.quick');

// Dedicated Customer Portal Pages
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard/services', [DashboardController::class, 'services'])->name('dashboard.services');
Route::get('/dashboard/domains', [DashboardController::class, 'domains'])->name('dashboard.domains');
Route::get('/dashboard/invoices', [DashboardController::class, 'invoices'])->name('dashboard.invoices');
Route::get('/dashboard/tickets', [DashboardController::class, 'tickets'])->name('dashboard.tickets');

// Customer Actions
Route::post('/dashboard/domains/{domain}/renew', [DashboardController::class, 'renewDomain'])->name('dashboard.domains.renew');
Route::post('/dashboard/domains/{domain}/nameservers', [DashboardController::class, 'updateNameservers'])->name('dashboard.domains.nameservers');
Route::post('/dashboard/domains/{domain}/toggle-lock', [DashboardController::class, 'toggleDomainLock'])->name('dashboard.domains.lock');
Route::post('/dashboard/domains/{domain}/whois-privacy', [DashboardController::class, 'toggleWhoisPrivacy'])->name('dashboard.domains.whois-privacy');
Route::post('/dashboard/services/{service}/upgrade', [DashboardController::class, 'upgradeService'])->name('dashboard.services.upgrade');
Route::post('/dashboard/services/{service}/cancel', [DashboardController::class, 'cancelService'])->name('dashboard.services.cancel');
Route::post('/dashboard/tickets', [DashboardController::class, 'createTicket'])->name('dashboard.tickets.create');
Route::post('/dashboard/tickets/{ticket}/reply', [DashboardController::class, 'replyTicket'])->name('dashboard.tickets.reply');
Route::post('/dashboard/invoices/{invoice}/pay', [DashboardController::class, 'payInvoice'])->name('dashboard.invoices.pay');

// Reseller Partner Portal Dedicated Page
Route::get('/reseller', [ResellerController::class, 'index'])->name('reseller.dashboard');
Route::post('/reseller/client/add', [ResellerController::class, 'addClient'])->name('reseller.client.add');

// Dedicated Admin Console Pages
Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
Route::get('/admin/servers', [AdminController::class, 'servers'])->name('admin.servers');
Route::get('/admin/plans', [AdminController::class, 'plans'])->name('admin.plans');
Route::get('/admin/tickets', [AdminController::class, 'tickets'])->name('admin.tickets');
Route::get('/admin/invoices', [AdminController::class, 'invoices'])->name('admin.invoices');
Route::get('/admin/settings', [AdminController::class, 'settings'])->name('admin.settings');
Route::post('/admin/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');
Route::post('/admin/smtp/test', [AdminController::class, 'testSmtp'])->name('admin.smtp.test');
Route::post('/admin/invoices/{invoice}/resend-email', [AdminController::class, 'resendInvoiceEmail'])->name('admin.invoices.resend');
Route::post('/admin/services/{service}/send-cpanel-credentials', [AdminController::class, 'sendCpanelCredentialsEmail'])->name('admin.services.cpanel-credentials');
Route::get('/admin/integrations/registrars', [AdminController::class, 'registrars'])->name('admin.registrars');
Route::get('/admin/integrations/registrar-logs', [AdminController::class, 'registrarLogs'])->name('admin.registrar-logs');

// Admin Actions
Route::post('/admin/hosting-plans', [AdminController::class, 'createPlan'])->name('admin.plans.create');
Route::post('/admin/services/{service}/suspend', [AdminController::class, 'suspendService'])->name('admin.services.suspend');
Route::post('/admin/services/{service}/unsuspend', [AdminController::class, 'unsuspendService'])->name('admin.services.unsuspend');
Route::post('/admin/services/{service}/terminate', [AdminController::class, 'terminateService'])->name('admin.services.terminate');
