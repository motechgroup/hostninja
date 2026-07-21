<?php

namespace App\Http\Controllers;

use App\Models\HostingPlan;
use App\Models\ResellerCommission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResellerController extends Controller
{
    public function index()
    {
        // Auto login reseller demo if not logged in
        if (!Auth::check()) {
            $resellerUser = User::where('role', 'reseller')->first() ?? User::first();
            if ($resellerUser) {
                Auth::login($resellerUser);
            }
        }

        $reseller = Auth::user();
        $commissions = ResellerCommission::with('client')->where('reseller_id', $reseller->id)->get();
        $totalEarned = $commissions->sum('commission_amount');
        $hostingPlans = HostingPlan::where('is_active', true)->get();
        $subClients = User::where('role', 'customer')->get();

        return view('reseller.index', compact('reseller', 'commissions', 'totalEarned', 'hostingPlans', 'subClients'));
    }

    public function addClient(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'company' => 'nullable|string',
        ]);

        $client = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'company' => $request->company ?? 'Sub-Client',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        // Record 20% reseller commission entry
        ResellerCommission::create([
            'reseller_id' => Auth::id(),
            'client_id' => $client->id,
            'service_name' => 'Reseller Sub-Account Activation',
            'sale_amount' => 1200.00,
            'commission_amount' => 240.00,
            'status' => 'paid',
        ]);

        return back()->with('success', "Sub-client account {$client->name} created under your reseller partner account!");
    }
}
