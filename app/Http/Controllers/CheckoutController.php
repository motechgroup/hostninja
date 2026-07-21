<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\HostingPlan;
use App\Models\HostingService;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Server;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        // Handle query parameter for quick plan selection: /checkout?plan=business or /checkout?plan_id=2
        if ($request->has('plan')) {
            $plan = HostingPlan::where('slug', $request->query('plan'))->first();
            if ($plan) {
                session()->put('cart_plan_id', $plan->id);
            }
        } elseif ($request->has('plan_id')) {
            session()->put('cart_plan_id', $request->query('plan_id'));
        }

        // Handle domain query parameter: /checkout?domain=mycompany.co.ke
        if ($request->has('domain')) {
            $cartDomains = session()->get('cart_domains', []);
            $cartDomains[$request->query('domain')] = 990.00;
            session()->put('cart_domains', $cartDomains);
        }

        $hostingPlans = HostingPlan::where('is_active', true)->get();

        $selectedPlanId = session('cart_plan_id', $hostingPlans->first()?->id);
        $selectedPlan = HostingPlan::find($selectedPlanId) ?? $hostingPlans->first();

        $cartDomains = session('cart_domains', []);
        $billingCycle = session('cart_billing_cycle', 'monthly');
        $couponCode = session('cart_coupon', null);
        $discountAmount = 0.00;

        $planPrice = ($billingCycle === 'yearly') ? $selectedPlan->price_yearly : $selectedPlan->price_monthly;
        $domainsTotal = array_sum($cartDomains);
        $subtotal = $planPrice + $domainsTotal;

        if ($couponCode === 'SAVE20') {
            $discountAmount = $subtotal * 0.20;
        } elseif ($couponCode === 'NINJA100') {
            $discountAmount = min(100.00, $subtotal);
        }

        $taxableAmount = max(0, $subtotal - $discountAmount);
        $tax = $taxableAmount * 0.16; // 16% VAT
        $total = $taxableAmount + $tax;

        return view('checkout.index', compact(
            'hostingPlans',
            'selectedPlan',
            'cartDomains',
            'billingCycle',
            'couponCode',
            'subtotal',
            'discountAmount',
            'tax',
            'total'
        ));
    }

    public function selectPlan(Request $request)
    {
        $request->validate(['plan_id' => 'required|exists:hosting_plans,id']);
        session()->put('cart_plan_id', $request->plan_id);
        if ($request->has('billing_cycle')) {
            session()->put('cart_billing_cycle', $request->billing_cycle);
        }
        return back()->with('success', 'Cart updated successfully.');
    }

    public function removeDomain(Request $request)
    {
        $domain = $request->input('domain');
        $cartDomains = session()->get('cart_domains', []);
        unset($cartDomains[$domain]);
        session()->put('cart_domains', $cartDomains);
        return back()->with('success', 'Domain removed from cart.');
    }

    public function applyCoupon(Request $request)
    {
        $code = strtoupper(trim($request->input('coupon_code')));
        if (in_array($code, ['SAVE20', 'NINJA100'])) {
            session()->put('cart_coupon', $code);
            return back()->with('success', "Coupon '{$code}' applied successfully!");
        }

        return back()->with('error', 'Invalid coupon code. Try SAVE20 for 20% discount!');
    }

    public function removeCoupon()
    {
        session()->forget('cart_coupon');
        return back()->with('success', 'Coupon removed.');
    }

    public function process(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'phone' => 'required|string|max:30',
                'password' => 'required|string|min:6',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'company' => $request->input('company', 'Personal'),
                'role' => 'customer',
                'password' => Hash::make($request->password),
                'balance' => 0.00,
            ]);

            Auth::login($user);
        }

        $hostingPlans = HostingPlan::where('is_active', true)->get();
        $selectedPlanId = session('cart_plan_id', $hostingPlans->first()?->id);
        $selectedPlan = HostingPlan::findOrFail($selectedPlanId);

        $cartDomains = session('cart_domains', []);
        $billingCycle = session('cart_billing_cycle', 'monthly');
        $couponCode = session('cart_coupon', null);

        $planPrice = ($billingCycle === 'yearly') ? $selectedPlan->price_yearly : $selectedPlan->price_monthly;
        $domainsTotal = array_sum($cartDomains);
        $subtotal = $planPrice + $domainsTotal;

        $discountAmount = 0.00;
        if ($couponCode === 'SAVE20') {
            $discountAmount = $subtotal * 0.20;
        } elseif ($couponCode === 'NINJA100') {
            $discountAmount = min(100.00, $subtotal);
        }

        $taxableAmount = max(0, $subtotal - $discountAmount);
        $tax = $taxableAmount * 0.16;
        $total = $taxableAmount + $tax;

        $invoiceCount = Invoice::count() + 1;
        $invoiceNumber = 'INV-2026-' . str_pad($invoiceCount, 4, '0', STR_PAD_LEFT);

        $primaryDomain = !empty($cartDomains) ? array_key_first($cartDomains) : 'site' . rand(100,999) . '.co.ke';

        $invoice = Invoice::create([
            'user_id' => $user->id,
            'invoice_number' => $invoiceNumber,
            'description' => "{$selectedPlan->name} ({$billingCycle}) + Domain {$primaryDomain}",
            'amount' => $subtotal - $discountAmount,
            'tax' => $tax,
            'total' => $total,
            'status' => 'paid',
            'due_date' => now()->addDays(7),
            'paid_at' => now(),
        ]);

        $txRef = 'QHK' . rand(1000000, 9999999) . 'XX';
        Payment::create([
            'invoice_id' => $invoice->id,
            'user_id' => $user->id,
            'payment_method' => $request->input('payment_method', 'mpesa'),
            'transaction_reference' => $txRef,
            'amount' => $total,
            'status' => 'completed',
        ]);

        $server = Server::where('status', 'online')->first() ?? Server::first();
        $cleanUser = preg_replace('/[^a-z0-9]/', '', strtolower(substr(explode('.', $primaryDomain)[0], 0, 8)));

        $hostingService = HostingService::create([
            'user_id' => $user->id,
            'hosting_plan_id' => $selectedPlan->id,
            'server_id' => $server?->id,
            'domain_name' => $primaryDomain,
            'username' => $cleanUser ?: 'hnuser' . rand(10,99),
            'status' => 'active',
            'billing_cycle' => $billingCycle,
            'amount' => $planPrice,
            'next_due_date' => ($billingCycle === 'yearly') ? now()->addYear() : now()->addMonth(),
        ]);

        foreach ($cartDomains as $domName => $domPrice) {
            $extParts = explode('.', $domName);
            $ext = '.' . implode('.', array_slice($extParts, 1));

            Domain::create([
                'user_id' => $user->id,
                'domain_name' => $domName,
                'extension' => $ext,
                'registration_date' => now(),
                'expiry_date' => now()->addYear(),
                'status' => 'active',
                'registrar' => 'HostNinja LogicBoxes API',
                'price' => $domPrice,
                'auto_renew' => true,
                'is_locked' => true,
                'whois_privacy_enabled' => true,
                'nameservers' => ['ns1.hostninja.cloud', 'ns2.hostninja.cloud'],
            ]);
        }

        // Automated Mail Notifications
        try {
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\InvoicePaidMail($invoice, $user));
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\ServiceProvisionedMail($hostingService, $user, 'password123'));
        } catch (\Throwable $e) {
            // Log mail exception if offline/local
            \Illuminate\Support\Facades\Log::warning("Checkout Mail dispatch warning: " . $e->getMessage());
        }

        session()->forget(['cart_domains', 'cart_plan_id', 'cart_billing_cycle', 'cart_coupon']);

        return redirect()->route('checkout.success', $invoice->id)->with('success', 'Order completed and services provisioned successfully!');
    }

    public function success(Invoice $invoice)
    {
        $user = Auth::user();
        if (!$user || $invoice->user_id !== $user->id) {
            abort(403);
        }

        $payment = Payment::where('invoice_id', $invoice->id)->first();
        $hostingService = HostingService::where('user_id', $user->id)->latest()->first();
        $domains = Domain::where('user_id', $user->id)->latest()->take(3)->get();

        return view('checkout.success', compact('invoice', 'payment', 'hostingService', 'domains'));
    }
}
