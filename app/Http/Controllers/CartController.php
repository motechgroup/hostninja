<?php

namespace App\Http\Controllers;

use App\Models\HostingPlan;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $hostingPlans = HostingPlan::where('is_active', true)->get();

        $selectedPlanId = session('cart_plan_id');
        $selectedPlan = $selectedPlanId ? HostingPlan::find($selectedPlanId) : null;

        $cartDomains = session('cart_domains', []);
        $billingCycle = session('cart_billing_cycle', 'monthly');
        $couponCode = session('cart_coupon');

        $planPrice = $selectedPlan ? (($billingCycle === 'yearly') ? $selectedPlan->price_yearly : $selectedPlan->price_monthly) : 0.00;
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

        $itemCount = ($selectedPlan ? 1 : 0) + count($cartDomains);

        return view('cart.index', compact(
            'hostingPlans',
            'selectedPlan',
            'cartDomains',
            'billingCycle',
            'couponCode',
            'subtotal',
            'discountAmount',
            'tax',
            'total',
            'itemCount'
        ));
    }

    public function removePlan()
    {
        session()->forget('cart_plan_id');
        return back()->with('success', 'Hosting plan removed from cart.');
    }

    public function removeDomain(Request $request)
    {
        $domain = $request->input('domain');
        $cartDomains = session()->get('cart_domains', []);
        unset($cartDomains[$domain]);
        session()->put('cart_domains', $cartDomains);

        return back()->with('success', 'Domain removed from cart.');
    }

    public function clear()
    {
        session()->forget(['cart_plan_id', 'cart_domains', 'cart_billing_cycle', 'cart_coupon']);
        return back()->with('success', 'Shopping cart cleared.');
    }
}
