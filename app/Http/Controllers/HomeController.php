<?php

namespace App\Http\Controllers;

use App\Models\HostingPlan;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $hostingPlans = HostingPlan::where('is_active', true)->get();
        return view('home', compact('hostingPlans'));
    }

    public function hosting()
    {
        $hostingPlans = HostingPlan::where('is_active', true)->get();
        return view('hosting.index', compact('hostingPlans'));
    }

    public function domainSearch()
    {
        return view('domains.search');
    }
}
