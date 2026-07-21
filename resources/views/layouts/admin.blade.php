<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'HostNinja Admin Dashboard' }}</title>

    <!-- Google Fonts & Material Symbols from Stitch -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;600;700;800&family=Inter:wght@400;600&family=JetBrains+Mono:wght@600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#f7f9fb] text-[#191c1e] font-sans antialiased min-h-screen">

    <!-- Stitch SideNavBar -->
    <aside class="hidden md:flex flex-col h-screen py-6 bg-white border-r border-slate-200 shadow-lg w-64 fixed left-0 top-0 z-50 overflow-y-auto">
        <div class="px-6 mb-8">
            <h1 class="font-['Hanken_Grotesk'] text-2xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                <span>HostNinja</span><span class="text-[#0059bb]">Dash</span>
            </h1>
            <p class="font-['JetBrains_Mono'] text-[10px] text-slate-400 uppercase tracking-widest mt-1">Cloud Infrastructure</p>
        </div>

        <nav class="flex-1 space-y-1 px-3">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 {{ request()->routeIs('admin.dashboard') ? 'bg-[#0059bb] text-white shadow-md shadow-blue-500/20' : 'text-slate-600 hover:bg-slate-100' }} rounded-xl px-4 py-3 font-semibold text-xs transition-colors">
                <span class="material-symbols-outlined text-lg">dashboard</span>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.users') }}" class="flex items-center gap-3 {{ request()->routeIs('admin.users') ? 'bg-[#0059bb] text-white shadow-md shadow-blue-500/20' : 'text-slate-600 hover:bg-slate-100' }} rounded-xl px-4 py-2.5 font-semibold text-xs transition-colors">
                <span class="material-symbols-outlined text-lg">group</span>
                <span>Users & Accounts</span>
            </a>
            <a href="{{ route('admin.servers') }}" class="flex items-center gap-3 {{ request()->routeIs('admin.servers') ? 'bg-[#0059bb] text-white shadow-md shadow-blue-500/20' : 'text-slate-600 hover:bg-slate-100' }} rounded-xl px-4 py-2.5 font-semibold text-xs transition-colors">
                <span class="material-symbols-outlined text-lg">dns</span>
                <span>Server Nodes</span>
            </a>
            <a href="{{ route('admin.plans') }}" class="flex items-center gap-3 {{ request()->routeIs('admin.plans') ? 'bg-[#0059bb] text-white shadow-md shadow-blue-500/20' : 'text-slate-600 hover:bg-slate-100' }} rounded-xl px-4 py-2.5 font-semibold text-xs transition-colors">
                <span class="material-symbols-outlined text-lg">inventory_2</span>
                <span>Packages</span>
            </a>
            <a href="{{ route('admin.tickets') }}" class="flex items-center gap-3 {{ request()->routeIs('admin.tickets') ? 'bg-[#0059bb] text-white shadow-md shadow-blue-500/20' : 'text-slate-600 hover:bg-slate-100' }} rounded-xl px-4 py-2.5 font-semibold text-xs transition-colors">
                <span class="material-symbols-outlined text-lg">confirmation_number</span>
                <span>Support Queue</span>
            </a>
            <a href="{{ route('admin.invoices') }}" class="flex items-center gap-3 {{ request()->routeIs('admin.invoices') ? 'bg-[#0059bb] text-white shadow-md shadow-blue-500/20' : 'text-slate-600 hover:bg-slate-100' }} rounded-xl px-4 py-2.5 font-semibold text-xs transition-colors">
                <span class="material-symbols-outlined text-lg">receipt_long</span>
                <span>Invoices & Billing</span>
            </a>

            <!-- Integrations Section -->
            <div class="pt-3 pb-1 px-4">
                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider font-['JetBrains_Mono']">Integrations</span>
            </div>

            <a href="{{ route('admin.registrars') }}" class="flex items-center gap-3 {{ request()->routeIs('admin.registrars*') ? 'bg-[#0059bb] text-white shadow-md shadow-blue-500/20' : 'text-slate-600 hover:bg-slate-100' }} rounded-xl px-4 py-2.5 font-semibold text-xs transition-colors">
                <span class="material-symbols-outlined text-lg">domain_verification</span>
                <span>Registrars</span>
            </a>

            <a href="{{ route('admin.registrar-logs') }}" class="flex items-center gap-3 {{ request()->routeIs('admin.registrar-logs') ? 'bg-[#0059bb] text-white shadow-md shadow-blue-500/20' : 'text-slate-600 hover:bg-slate-100' }} rounded-xl px-4 py-2.5 font-semibold text-xs transition-colors">
                <span class="material-symbols-outlined text-lg">description</span>
                <span>API Audit Logs</span>
            </a>

            <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 {{ request()->routeIs('admin.settings') ? 'bg-[#0059bb] text-white shadow-md shadow-blue-500/20' : 'text-slate-600 hover:bg-slate-100' }} rounded-xl px-4 py-2.5 font-semibold text-xs transition-colors">
                <span class="material-symbols-outlined text-lg">settings</span>
                <span>System Settings</span>
            </a>
            <a href="{{ route('reseller.dashboard') }}" class="flex items-center gap-3 {{ request()->routeIs('reseller.*') ? 'bg-[#0059bb] text-white shadow-md shadow-blue-500/20' : 'text-slate-600 hover:bg-slate-100' }} rounded-xl px-4 py-2.5 font-semibold text-xs transition-colors">
                <span class="material-symbols-outlined text-lg">handshake</span>
                <span>Reseller Console</span>
            </a>
            <a href="{{ route('home') }}" class="flex items-center gap-3 text-slate-500 hover:bg-slate-100 rounded-xl px-4 py-2.5 font-semibold text-xs transition-colors mt-4">
                <span class="material-symbols-outlined text-lg">public</span>
                <span>Public Site</span>
            </a>
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 text-slate-500 hover:bg-slate-100 rounded-xl px-4 py-2.5 font-semibold text-xs transition-colors">
                <span class="material-symbols-outlined text-lg">account_circle</span>
                <span>Customer Portal</span>
            </a>
        </nav>

        <div class="px-4 mt-auto border-t border-slate-100 pt-4">
            <div class="flex items-center gap-3 px-2">
                <div class="w-10 h-10 rounded-full bg-[#0059bb]/10 flex items-center justify-center text-[#0059bb] font-bold text-sm">
                    <span class="material-symbols-outlined text-xl">admin_panel_settings</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-xs font-bold text-slate-900 truncate">{{ auth()->user()->name ?? 'Admin User' }}</span>
                    <span class="text-[10px] font-['JetBrains_Mono'] text-amber-600 font-semibold">System Root</span>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="md:ml-64 min-h-screen">
        <!-- Stitch Admin Header -->
        <header class="flex justify-between items-center h-20 px-8 bg-white/80 backdrop-blur-xl sticky top-0 z-40 border-b border-slate-200">
            <div>
                <h2 class="font-['Hanken_Grotesk'] text-xl font-bold text-slate-900 leading-tight">Analytics & Infrastructure</h2>
                <p class="text-slate-500 text-xs">Enterprise infrastructure health and financial metrics.</p>
            </div>
            <div class="flex items-center gap-4">
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 flex items-center gap-1.5 border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Nodes Optimal</span>
                </span>
                <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-slate-900 hover:bg-[#0059bb] text-white text-xs font-bold rounded-xl transition-all shadow">
                    Customer View
                </a>
            </div>
        </header>

        <div class="p-8 max-w-7xl mx-auto space-y-8">
            {{ $slot }}
        </div>
    </main>

    @livewireScripts
</body>
</html>
