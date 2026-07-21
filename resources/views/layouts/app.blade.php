<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? \App\Models\Setting::getByKey('seo_title', 'HostNinja | Lightning Fast Cloud Hosting') }}</title>
    <meta name="description" content="{{ \App\Models\Setting::getByKey('seo_description', 'HostNinja provides enterprise-grade cloud hosting, NVMe storage speed, domain registration, and 24/7 support.') }}">
    <meta name="keywords" content="{{ \App\Models\Setting::getByKey('seo_keywords', 'cloud hosting, nvme hosting, domain registration, cpanel, mpesa hosting kenya, hostninja') }}">
    <meta property="og:title" content="{{ \App\Models\Setting::getByKey('seo_title', 'HostNinja Cloud Infrastructure') }}">
    <meta property="og:description" content="{{ \App\Models\Setting::getByKey('seo_description', 'High performance cloud hosting and domain registration.') }}">

    <!-- Google Fonts & Material Symbols from Stitch -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;600;700;800&family=Inter:wght@400;600&family=JetBrains+Mono:wght@600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#f7f9fb] text-[#191c1e] font-sans antialiased min-h-screen flex flex-col">

    @if(!request()->routeIs('login', 'register', 'admin.login'))
        <!-- Stitch TopNavBar -->
        <nav x-data="{ mobileOpen: false }" class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-xl border-b border-slate-200/80 shadow-sm transition-all duration-300">
            <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
                <div class="flex items-center gap-12">
                    <a href="{{ route('home') }}" class="text-2xl font-extrabold font-['Hanken_Grotesk'] text-slate-900 tracking-tight flex items-center gap-2">
                        <span class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#0059bb] to-[#0070ea] flex items-center justify-center text-white text-lg font-bold shadow-md shadow-blue-500/20">H</span>
                        <span>HostNinja</span>
                    </a>

                    <!-- Desktop Public Menu -->
                    <div class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-600">
                        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-[#0059bb] font-bold' : 'hover:text-[#0059bb]' }} transition-colors">Home</a>
                        <a href="{{ route('hosting.index') }}" class="{{ request()->routeIs('hosting.*') ? 'text-[#0059bb] font-bold' : 'hover:text-[#0059bb]' }} transition-colors">Hosting Plans</a>
                        <a href="{{ route('domains.search') }}" class="{{ request()->routeIs('domains.*') ? 'text-[#0059bb] font-bold' : 'hover:text-[#0059bb]' }} transition-colors">Domains</a>
                    </div>
                </div>

                <!-- Right Profile, Cart & Auth Action Buttons -->
                <div class="hidden md:flex items-center gap-4">
                    @php
                        $cartPlanId = session('cart_plan_id');
                        $cartPlan = $cartPlanId ? \App\Models\HostingPlan::find($cartPlanId) : null;
                        $cartDomains = session('cart_domains', []);
                        $cartBillingCycle = session('cart_billing_cycle', 'monthly');
                        $cartItemCount = ($cartPlan ? 1 : 0) + count($cartDomains);
                        $cartPlanPrice = $cartPlan ? (($cartBillingCycle === 'yearly') ? $cartPlan->price_yearly : $cartPlan->price_monthly) : 0;
                        $cartSubtotal = $cartPlanPrice + array_sum($cartDomains);
                    @endphp

                    <!-- Persistent Header Shopping Cart Widget -->
                    <div class="relative" x-data="{ cartOpen: false }">
                        <a href="{{ route('cart.index') }}" @mouseenter="cartOpen = true" class="relative flex items-center justify-center w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 text-slate-700 hover:bg-slate-200 transition-colors">
                            <span class="material-symbols-outlined text-lg">shopping_cart</span>
                            @if($cartItemCount > 0)
                                <span class="absolute -top-1.5 -right-1.5 bg-[#0059bb] text-white text-[10px] font-extrabold w-5 h-5 rounded-full flex items-center justify-center shadow-md animate-pulse">
                                    {{ $cartItemCount }}
                                </span>
                            @endif
                        </a>

                        <!-- Cart Dropdown Preview -->
                        <div x-show="cartOpen" @mouseleave="cartOpen = false" @click.away="cartOpen = false" class="absolute right-0 mt-2 w-72 bg-white rounded-2xl shadow-2xl p-4 z-50 border border-slate-200" x-cloak>
                            <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-3">
                                <span class="font-['Hanken_Grotesk'] text-sm font-bold text-slate-900">Shopping Cart ({{ $cartItemCount }})</span>
                                <span class="text-xs font-bold text-[#0059bb]">KES {{ number_format($cartSubtotal, 2) }}</span>
                            </div>

                            @if($cartItemCount > 0)
                                <div class="space-y-2 max-h-48 overflow-y-auto divide-y divide-slate-100 text-xs">
                                    @if($cartPlan)
                                        <div class="pt-2 flex justify-between items-center">
                                            <div>
                                                <span class="font-bold text-slate-900 block">{{ $cartPlan->name }}</span>
                                                <span class="text-[10px] text-slate-400">Hosting Package</span>
                                            </div>
                                            <span class="font-bold text-[#0059bb]">KES {{ number_format($cartPlanPrice) }}</span>
                                        </div>
                                    @endif

                                    @foreach($cartDomains as $dName => $dPrice)
                                        <div class="pt-2 flex justify-between items-center">
                                            <div>
                                                <span class="font-bold text-slate-900 block">{{ $dName }}</span>
                                                <span class="text-[10px] text-emerald-600 font-semibold">1 Yr Domain</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-slate-800">KES {{ number_format($dPrice, 2) }}</span>
                                                <form method="POST" action="{{ route('cart.domain.remove') }}" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="domain" value="{{ $dName }}">
                                                    <button type="submit" title="Remove domain" class="text-slate-400 hover:text-rose-600 transition-colors">
                                                        <span class="material-symbols-outlined text-xs">close</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="pt-3 border-t border-slate-100 flex gap-2 mt-3">
                                    <a href="{{ route('cart.index') }}" class="flex-1 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 text-center font-bold text-xs rounded-xl transition-colors">View Cart</a>
                                    <a href="{{ route('checkout.index') }}" class="flex-1 py-2 bg-[#0059bb] hover:bg-blue-600 text-white text-center font-bold text-xs rounded-xl shadow transition-colors">Checkout</a>
                                </div>
                            @else
                                <p class="text-xs text-slate-400 text-center py-4">Your cart is empty.</p>
                            @endif
                        </div>
                    </div>

                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-3 bg-slate-100 border border-slate-200 px-3.5 py-1.5 rounded-xl hover:border-slate-300 transition-all">
                                <div class="w-8 h-8 rounded-lg bg-[#0059bb] flex items-center justify-center font-bold text-white text-xs">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                                <div class="text-left">
                                    <div class="text-xs font-bold text-slate-800 leading-none">{{ auth()->user()->name }}</div>
                                    <div class="text-[10px] text-[#0059bb] capitalize mt-0.5 font-semibold">{{ auth()->user()->role }}</div>
                                </div>
                                <span class="material-symbols-outlined text-slate-400 text-sm">expand_more</span>
                            </button>

                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl py-2 z-50 border border-slate-200" x-cloak>
                                <div class="px-4 py-2 border-b border-slate-100">
                                    <p class="text-[10px] text-slate-400 font-semibold uppercase">Signed in as</p>
                                    <p class="text-xs font-bold text-slate-800 truncate">{{ auth()->user()->email }}</p>
                                </div>
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">Customer Dashboard</a>
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-xs font-bold text-amber-600 hover:bg-amber-50">Admin Dashboard</a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50">Sign Out</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-700 hover:text-[#0059bb] transition-colors px-3 py-2">Log In</a>
                        <a href="{{ route('register') }}" class="bg-[#000000] text-white px-6 py-2.5 rounded-lg text-sm font-semibold hover:bg-[#0059bb] transition-all shadow-md">Sign Up</a>
                    @endauth
                </div>

                <!-- Mobile Menu Toggle -->
                <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 text-slate-700">
                    <span class="material-symbols-outlined">menu</span>
                </button>
            </div>

            <!-- Mobile Menu Dropdown -->
            <div x-show="mobileOpen" class="md:hidden bg-white border-b border-slate-200 px-6 py-4 space-y-3" x-cloak>
                <a href="{{ route('home') }}" class="block text-sm font-semibold text-slate-800 py-1">Home</a>
                <a href="{{ route('hosting.index') }}" class="block text-sm font-semibold text-slate-800 py-1">Hosting Plans</a>
                <a href="{{ route('domains.search') }}" class="block text-sm font-semibold text-slate-800 py-1">Domains</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="block text-sm font-semibold text-[#0059bb] py-1">Customer Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="pt-2 border-t border-slate-100">
                        @csrf
                        <button type="submit" class="w-full text-left text-sm font-semibold text-rose-600 py-1">Sign Out ({{ auth()->user()->name }})</button>
                    </form>
                @else
                    <div class="pt-2 border-t border-slate-100 flex flex-col gap-2">
                        <a href="{{ route('login') }}" class="w-full text-center py-2 text-sm font-semibold bg-slate-100 rounded-lg">Log In</a>
                        <a href="{{ route('register') }}" class="w-full text-center py-2 text-sm font-semibold bg-[#0059bb] text-white rounded-lg">Sign Up</a>
                    </div>
                @endauth
            </div>
        </nav>
    @endif

    <!-- Main Content -->
    <main class="{{ request()->routeIs('login', 'register', 'admin.login') ? 'flex-grow p-0' : 'flex-grow pt-20' }}">
        {{ $slot }}
    </main>

    @if(!request()->routeIs('login', 'register', 'admin.login'))
        <!-- Stitch Footer -->
        <footer class="bg-slate-900 text-slate-300 w-full py-16 border-t border-slate-800">
            <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-12">
                <div>
                    <div class="text-2xl font-extrabold font-['Hanken_Grotesk'] text-white mb-4">HostNinja</div>
                    <p class="text-slate-400 text-sm leading-relaxed mb-6">
                        Empowering developers and businesses with lightning-fast cloud solutions, NVMe SSD speed, and 24/7 reliability.
                    </p>
                </div>
                <div>
                    <h5 class="font-bold text-xs uppercase tracking-widest text-white mb-6 font-['JetBrains_Mono']">Platform</h5>
                    <ul class="space-y-3 text-sm">
                        <li><a href="{{ route('hosting.index') }}" class="hover:text-white transition-colors">NVMe Cloud Hosting</a></li>
                        <li><a href="{{ route('domains.search') }}" class="hover:text-white transition-colors">Domain Registration</a></li>
                        <li><a href="{{ route('domains.search') }}" class="hover:text-white transition-colors">Free SSL Certificates</a></li>
                        <li><a href="{{ route('dashboard') }}" class="hover:text-white transition-colors">cPanel Access</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="font-bold text-xs uppercase tracking-widest text-white mb-6 font-['JetBrains_Mono']">Marketplace</h5>
                    <ul class="space-y-3 text-sm">
                        <li><a href="{{ route('domains.search') }}" class="hover:text-white transition-colors">.COM ($9.99/yr)</a></li>
                        <li><a href="{{ route('domains.search') }}" class="hover:text-white transition-colors">.CO.KE ($15.00/yr)</a></li>
                        <li><a href="{{ route('domains.search') }}" class="hover:text-white transition-colors">.IO ($32.50/yr)</a></li>
                        <li><a href="{{ route('domains.search') }}" class="hover:text-white transition-colors">.AFRICA ($12.00/yr)</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="font-bold text-xs uppercase tracking-widest text-white mb-6 font-['JetBrains_Mono']">Support & Admin</h5>
                    <ul class="space-y-3 text-sm">
                        <li><a href="{{ route('dashboard') }}" class="hover:text-white transition-colors">Customer Portal</a></li>
                        <li><a href="{{ route('dashboard') }}" class="hover:text-white transition-colors">Submit Support Ticket</a></li>
                        <li><a href="{{ route('admin.login') }}" class="hover:text-amber-400 transition-colors">Admin Portal Login</a></li>
                    </ul>
                </div>
            </div>

            <!-- Supported Payment Gateways Footer Section -->
            <x-payment-methods-footer />

            <div class="max-w-7xl mx-auto px-6 mt-12 pt-8 border-t border-slate-800 flex flex-col md:flex-row justify-between items-center text-xs text-slate-500 gap-4">
                <div>© {{ date('Y') }} HostNinja Cloud. Enterprise-grade reliability.</div>
                <div class="flex gap-6 items-center">
                    <span class="flex items-center gap-2 text-emerald-400 font-semibold"><span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> System Operational</span>
                    <span>ISO 27001 Certified</span>
                </div>
            </div>
        </footer>
    @endif

    @livewireScripts
</body>
</html>
