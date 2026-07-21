<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\HostingPlan;
use App\Models\HostingService;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Registrar;
use App\Models\Server;
use App\Models\Setting;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    private function ensureAdminAuth(): void
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            redirect()->route('admin.login')->send();
            exit;
        }
    }

    public function dashboard()
    {
        $this->ensureAdminAuth();

        $tab = 'overview';
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');
        $customerCount = User::where('role', 'customer')->count();
        $activeDomainsCount = Domain::where('status', 'active')->count();
        $activeHostingCount = HostingService::where('status', 'active')->count();
        $openTicketsCount = Ticket::whereIn('status', ['open', 'customer_reply'])->count();

        $servers = Server::all();
        $hostingPlans = HostingPlan::all();
        $recentUsers = User::orderBy('created_at', 'desc')->take(10)->get();
        $recentInvoices = Invoice::with('user')->orderBy('created_at', 'desc')->take(8)->get();
        $ticketQueue = Ticket::with(['user', 'assignedAgent'])->orderBy('updated_at', 'desc')->take(8)->get();
        $allServices = HostingService::with(['user', 'hostingPlan', 'server'])->get();

        // Chart Data for Monthly Revenue (Last 6 Months in KES)
        $revenueChart = [
            'labels' => ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            'data' => [45200, 58900, 72400, 89100, 104500, 115100],
        ];

        return view('admin.dashboard', compact(
            'tab',
            'totalRevenue',
            'customerCount',
            'activeDomainsCount',
            'activeHostingCount',
            'openTicketsCount',
            'servers',
            'hostingPlans',
            'recentUsers',
            'recentInvoices',
            'ticketQueue',
            'allServices',
            'revenueChart'
        ));
    }

    public function users()
    {
        $this->ensureAdminAuth();
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.users', compact('users'));
    }

    public function servers()
    {
        $this->ensureAdminAuth();
        $servers = Server::all();
        $services = HostingService::with(['user', 'hostingPlan', 'server'])->get();
        return view('admin.servers', compact('servers', 'services'));
    }

    public function plans()
    {
        $this->ensureAdminAuth();
        $hostingPlans = HostingPlan::all();
        return view('admin.plans', compact('hostingPlans'));
    }

    public function tickets()
    {
        $this->ensureAdminAuth();
        $tickets = Ticket::with(['user', 'assignedAgent', 'messages'])->orderBy('updated_at', 'desc')->get();
        return view('admin.tickets', compact('tickets'));
    }

    public function invoices()
    {
        $this->ensureAdminAuth();
        $invoices = Invoice::with('user')->orderBy('created_at', 'desc')->get();
        return view('admin.invoices', compact('invoices'));
    }

    public function settings()
    {
        $this->ensureAdminAuth();
        $settings = Setting::all()->pluck('value', 'key');
        $paymentMethods = \App\Models\PaymentMethod::orderBy('sort_order', 'asc')->get();
        return view('admin.settings', compact('settings', 'paymentMethods'));
    }

    public function registrars()
    {
        $this->ensureAdminAuth();
        return view('admin.registrars');
    }

    public function registrarLogs()
    {
        $this->ensureAdminAuth();
        return view('admin.registrar_logs');
    }

    public function updateSettings(Request $request)
    {
        $this->ensureAdminAuth();
        foreach ($request->except('_token') as $key => $val) {
            Setting::setKey($key, $val);
        }

        return back()->with('success', 'Platform & Gateway Settings updated successfully!');
    }

    public function suspendService(HostingService $service)
    {
        $this->ensureAdminAuth();
        $service->update(['status' => 'suspended']);
        return back()->with('success', "Service for {$service->domain_name} has been suspended.");
    }

    public function unsuspendService(HostingService $service)
    {
        $this->ensureAdminAuth();
        $service->update(['status' => 'active']);
        return back()->with('success', "Service for {$service->domain_name} has been reactivated.");
    }

    public function terminateService(HostingService $service)
    {
        $this->ensureAdminAuth();
        $service->update(['status' => 'terminated']);
        return back()->with('success', "Service for {$service->domain_name} has been terminated.");
    }

    public function createPlan(Request $request)
    {
        $this->ensureAdminAuth();
        $request->validate([
            'name' => 'required|string',
            'price_monthly' => 'required|numeric',
            'price_yearly' => 'required|numeric',
            'storage_gb' => 'required|integer',
            'bandwidth_gb' => 'required|integer',
        ]);

        HostingPlan::create([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
            'tagline' => 'High performance hosting package',
            'price_monthly' => $request->price_monthly,
            'price_yearly' => $request->price_yearly,
            'storage_gb' => $request->storage_gb,
            'bandwidth_gb' => $request->bandwidth_gb,
            'email_accounts' => 50,
            'databases' => 25,
            'ssl_free' => true,
            'is_active' => true,
        ]);

        return back()->with('success', 'Hosting Plan created!');
    }

    public function testSmtp(Request $request)
    {
        $this->ensureAdminAuth();
        $testEmail = $request->input('test_email', Auth::user()->email);

        try {
            \App\Services\MailConfigService::applyDynamicSmtpConfig();
            \Illuminate\Support\Facades\Mail::raw("Hello! This is a test email sent from HostNinja Cloud SMTP server at " . now()->toDateTimeString(), function ($msg) use ($testEmail) {
                $msg->to($testEmail)->subject("HostNinja Cloud — SMTP Server Test Success");
            });

            return back()->with('success', "SMTP Connection Verified! Test email dispatched to {$testEmail}.");
        } catch (\Throwable $e) {
            return back()->with('error', "SMTP Dispatch Failed: " . $e->getMessage());
        }
    }

    public function resendInvoiceEmail(Invoice $invoice)
    {
        $this->ensureAdminAuth();
        $user = $invoice->user;

        try {
            if ($invoice->status === 'paid') {
                \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\InvoicePaidMail($invoice, $user));
                return back()->with('success', "Paid Invoice #{$invoice->invoice_number} receipt sent to {$user->email}.");
            } else {
                \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\PaymentReminderMail($invoice, $user));
                return back()->with('success', "Payment Due Reminder for Invoice #{$invoice->invoice_number} sent to {$user->email}.");
            }
        } catch (\Throwable $e) {
            return back()->with('error', "Failed to send invoice email: " . $e->getMessage());
        }
    }

    public function sendCpanelCredentialsEmail(HostingService $service)
    {
        $this->ensureAdminAuth();
        $user = $service->user;

        try {
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\ServiceProvisionedMail($service, $user, 'password123'));
            return back()->with('success', "cPanel Access credentials for {$service->domain_name} dispatched to {$user->email}.");
        } catch (\Throwable $e) {
            return back()->with('error', "Failed to send cPanel credentials: " . $e->getMessage());
        }
    }

    public function paymentGateways()
    {
        $this->ensureAdminAuth();
        $settings = Setting::all()->pluck('value', 'key');
        $paymentMethods = \App\Models\PaymentMethod::orderBy('sort_order', 'asc')->get();
        return view('admin.payment_gateways', compact('settings', 'paymentMethods'));
    }

    public function togglePaymentMethod(\App\Models\PaymentMethod $method)
    {
        $this->ensureAdminAuth();
        $method->update(['is_enabled' => !$method->is_enabled]);
        $status = $method->is_enabled ? 'enabled' : 'disabled';
        return back()->with('success', "Payment method {$method->name} has been {$status}.");
    }

    public function createPaymentMethod(Request $request)
    {
        $this->ensureAdminAuth();
        $request->validate([
            'name' => 'required|string',
            'code' => 'required|string|unique:payment_methods,code',
            'category' => 'required|string',
            'icon_svg' => 'required|string',
            'sort_order' => 'nullable|integer',
        ]);

        \App\Models\PaymentMethod::create([
            'name' => $request->name,
            'code' => \Illuminate\Support\Str::slug($request->code),
            'category' => $request->category,
            'icon_svg' => $request->icon_svg,
            'sort_order' => $request->sort_order ?? 99,
            'is_enabled' => true,
        ]);

        return back()->with('success', "Payment method '{$request->name}' created successfully!");
    }

    public function updatePaymentMethod(Request $request, \App\Models\PaymentMethod $method)
    {
        $this->ensureAdminAuth();
        $request->validate([
            'name' => 'required|string',
            'category' => 'required|string',
            'sort_order' => 'required|integer',
            'icon_svg' => 'required|string',
        ]);

        $method->update([
            'name' => $request->name,
            'category' => $request->category,
            'sort_order' => $request->sort_order,
            'icon_svg' => $request->icon_svg,
        ]);

        return back()->with('success', "Payment method '{$method->name}' updated!");
    }

    public function deletePaymentMethod(\App\Models\PaymentMethod $method)
    {
        $this->ensureAdminAuth();
        $name = $method->name;
        $method->delete();
        return back()->with('success', "Payment method '{$name}' deleted.");
    }
}
