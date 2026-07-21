<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\HostingPlan;
use App\Models\HostingService;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    private function ensureCustomerAuth(): void
    {
        if (!Auth::check()) {
            redirect()->route('login')->send();
            exit;
        }
    }

    public function index(Request $request)
    {
        $this->ensureCustomerAuth();

        $user = Auth::user();
        $tab = 'overview';

        $domains = Domain::where('user_id', $user->id)->get();
        $hostingServices = HostingService::with(['hostingPlan', 'server'])->where('user_id', $user->id)->get();
        $invoices = Invoice::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        $tickets = Ticket::with(['messages', 'assignedAgent'])->where('user_id', $user->id)->orderBy('updated_at', 'desc')->get();
        $hostingPlans = HostingPlan::where('is_active', true)->get();

        return view('dashboard.index', compact('user', 'tab', 'domains', 'hostingServices', 'invoices', 'tickets', 'hostingPlans'));
    }

    public function services()
    {
        $this->ensureCustomerAuth();
        $user = Auth::user();
        $tab = 'services';

        $domains = Domain::where('user_id', $user->id)->get();
        $hostingServices = HostingService::with(['hostingPlan', 'server'])->where('user_id', $user->id)->get();
        $invoices = Invoice::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        $tickets = Ticket::with(['messages', 'assignedAgent'])->where('user_id', $user->id)->orderBy('updated_at', 'desc')->get();
        $hostingPlans = HostingPlan::where('is_active', true)->get();

        return view('dashboard.index', compact('user', 'tab', 'domains', 'hostingServices', 'invoices', 'tickets', 'hostingPlans'));
    }

    public function domains()
    {
        $this->ensureCustomerAuth();
        $user = Auth::user();
        $tab = 'domains';

        $domains = Domain::where('user_id', $user->id)->get();
        $hostingServices = HostingService::with(['hostingPlan', 'server'])->where('user_id', $user->id)->get();
        $invoices = Invoice::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        $tickets = Ticket::with(['messages', 'assignedAgent'])->where('user_id', $user->id)->orderBy('updated_at', 'desc')->get();
        $hostingPlans = HostingPlan::where('is_active', true)->get();

        return view('dashboard.index', compact('user', 'tab', 'domains', 'hostingServices', 'invoices', 'tickets', 'hostingPlans'));
    }

    public function invoices()
    {
        $this->ensureCustomerAuth();
        $user = Auth::user();
        $tab = 'billing';

        $domains = Domain::where('user_id', $user->id)->get();
        $hostingServices = HostingService::with(['hostingPlan', 'server'])->where('user_id', $user->id)->get();
        $invoices = Invoice::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        $tickets = Ticket::with(['messages', 'assignedAgent'])->where('user_id', $user->id)->orderBy('updated_at', 'desc')->get();
        $hostingPlans = HostingPlan::where('is_active', true)->get();

        return view('dashboard.index', compact('user', 'tab', 'domains', 'hostingServices', 'invoices', 'tickets', 'hostingPlans'));
    }

    public function tickets()
    {
        $this->ensureCustomerAuth();
        $user = Auth::user();
        $tab = 'support';

        $domains = Domain::where('user_id', $user->id)->get();
        $hostingServices = HostingService::with(['hostingPlan', 'server'])->where('user_id', $user->id)->get();
        $invoices = Invoice::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        $tickets = Ticket::with(['messages', 'assignedAgent'])->where('user_id', $user->id)->orderBy('updated_at', 'desc')->get();
        $hostingPlans = HostingPlan::where('is_active', true)->get();

        return view('dashboard.index', compact('user', 'tab', 'domains', 'hostingServices', 'invoices', 'tickets', 'hostingPlans'));
    }

    public function renewDomain(Domain $domain)
    {
        $this->ensureCustomerAuth();
        if ($domain->user_id !== Auth::id()) {
            abort(403);
        }

        $newExpiry = $domain->expiry_date->addYear();
        $domain->update(['expiry_date' => $newExpiry]);

        Invoice::create([
            'user_id' => Auth::id(),
            'invoice_number' => 'INV-2026-' . rand(100, 999),
            'description' => "1-Year Renewal for Domain: {$domain->domain_name}",
            'amount' => $domain->price,
            'tax' => round($domain->price * 0.16, 2),
            'total' => round($domain->price * 1.16, 2),
            'status' => 'paid',
            'due_date' => now(),
            'paid_at' => now(),
        ]);

        return back()->with('success', "Domain {$domain->domain_name} renewed for 1 year! New expiry: " . $newExpiry->format('M d, Y'));
    }

    public function updateNameservers(Request $request, Domain $domain)
    {
        $this->ensureCustomerAuth();
        if ($domain->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'ns1' => 'required|string',
            'ns2' => 'required|string',
        ]);

        $domain->update([
            'nameservers' => [$request->ns1, $request->ns2],
        ]);

        return back()->with('success', "Nameservers for {$domain->domain_name} updated successfully!");
    }

    public function toggleDomainLock(Domain $domain)
    {
        $this->ensureCustomerAuth();
        if ($domain->user_id !== Auth::id()) {
            abort(403);
        }

        $domain->update(['is_locked' => !$domain->is_locked]);

        $status = $domain->is_locked ? 'Locked' : 'Unlocked';
        return back()->with('success', "Domain {$domain->domain_name} registrar status: {$status}.");
    }

    public function toggleWhoisPrivacy(Domain $domain)
    {
        $this->ensureCustomerAuth();
        if ($domain->user_id !== Auth::id()) {
            abort(403);
        }

        $domain->update(['whois_privacy_enabled' => !$domain->whois_privacy_enabled]);

        $status = $domain->whois_privacy_enabled ? 'ENABLED' : 'DISABLED';
        return back()->with('success', "WHOIS ID Privacy for {$domain->domain_name} is now {$status}.");
    }

    public function upgradeService(Request $request, HostingService $service)
    {
        $this->ensureCustomerAuth();
        if ($service->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'new_plan_id' => 'required|exists:hosting_plans,id',
        ]);

        $newPlan = HostingPlan::findOrFail($request->new_plan_id);
        $service->update([
            'hosting_plan_id' => $newPlan->id,
            'amount' => $newPlan->price_monthly,
        ]);

        return back()->with('success', "Hosting plan for {$service->domain_name} upgraded to {$newPlan->name}!");
    }

    public function cancelService(HostingService $service)
    {
        $this->ensureCustomerAuth();
        if ($service->user_id !== Auth::id()) {
            abort(403);
        }

        $service->update(['status' => 'cancelled']);

        return back()->with('success', "Hosting service for {$service->domain_name} has been cancelled.");
    }

    public function createTicket(Request $request)
    {
        $this->ensureCustomerAuth();
        $request->validate([
            'subject' => 'required|string|max:255',
            'category' => 'required|string',
            'priority' => 'required|string',
            'message' => 'required|string',
        ]);

        $ticket = Ticket::create([
            'user_id' => Auth::id(),
            'ticket_number' => 'TICK-' . rand(1000, 9999),
            'subject' => $request->subject,
            'category' => $request->category,
            'priority' => $request->priority,
            'status' => 'open',
        ]);

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        return redirect()->route('dashboard.tickets')->with('success', 'Support ticket #' . $ticket->ticket_number . ' submitted successfully!');
    }

    public function replyTicket(Request $request, Ticket $ticket)
    {
        $this->ensureCustomerAuth();
        $request->validate([
            'message' => 'required|string',
        ]);

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        $ticket->update([
            'status' => Auth::user()->isSupportAgent() ? 'answered' : 'customer_reply',
        ]);

        return back()->with('success', 'Reply submitted!');
    }

    public function payInvoice(Request $request, Invoice $invoice)
    {
        $this->ensureCustomerAuth();
        $request->validate([
            'payment_method' => 'required|string',
            'phone' => 'nullable|string',
        ]);

        $ref = strtoupper($request->payment_method) . '-' . strtoupper(substr(md5(uniqid()), 0, 8));

        Payment::create([
            'invoice_id' => $invoice->id,
            'user_id' => Auth::id(),
            'payment_method' => $request->payment_method,
            'transaction_reference' => $ref,
            'amount' => $invoice->total,
            'status' => 'completed',
        ]);

        $invoice->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        return redirect()->route('dashboard.invoices')->with('success', 'Invoice #' . $invoice->invoice_number . ' successfully paid via ' . strtoupper($request->payment_method) . '! (Ref: ' . $ref . ')');
    }
}
