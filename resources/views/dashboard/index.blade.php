<x-app-layout>
    <x-slot name="title">HostNinja | Customer Dashboard</x-slot>

    <div class="py-8 bg-[#f7f9fb] min-h-screen" x-data="{ currentTab: '{{ $tab }}', showNewTicket: false, showPayModal: false, selectedInvoice: null, showCpanelModal: false, selectedService: null, showDnsModal: false, selectedDomain: null, showUpgradeModal: false, showNsModal: false }">
        <div class="max-w-7xl mx-auto px-6 space-y-8">
            
            <!-- Alert Banner -->
            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 text-xs font-bold flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Stitch Header Banner -->
            <div class="bg-white p-8 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-[#0059bb] to-[#0070ea] flex items-center justify-center font-bold text-white text-xl shadow-lg shadow-blue-500/20">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-2xl font-extrabold font-['Hanken_Grotesk'] text-slate-900">Welcome back, {{ $user->name }}</h1>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#0059bb]/10 text-[#0059bb] uppercase">{{ $user->role }}</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">Company: <span class="text-slate-800 font-semibold">{{ $user->company ?? 'N/A' }}</span> | Phone: <span class="text-slate-800 font-semibold">{{ $user->phone ?? 'N/A' }}</span></p>
                    </div>
                </div>

                <div class="flex items-center gap-6 w-full md:w-auto justify-between md:justify-end">
                    <div class="text-right">
                        <span class="text-[10px] font-['JetBrains_Mono'] text-slate-400 uppercase tracking-wider block">Wallet Balance</span>
                        <span class="text-xl font-extrabold text-emerald-600">KES {{ number_format($user->balance, 2) }}</span>
                    </div>
                    <button @click="showNewTicket = true" class="px-5 py-2.5 bg-[#000000] hover:bg-[#0059bb] text-white font-bold text-xs rounded-xl transition-all shadow flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">add_circle</span>
                        <span>Open Ticket</span>
                    </button>
                </div>
            </div>

            <!-- Stitch Top Bento Row: Stats & Server Load -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Infrastructure Load Widget -->
                <div class="lg:col-span-2 glass-card rounded-3xl p-8 shadow-sm">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="font-['Hanken_Grotesk'] text-xl font-bold text-slate-900">Global Infrastructure Load</h3>
                            <p class="font-['JetBrains_Mono'] text-xs text-slate-500">Real-time Node Performance</p>
                        </div>
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full flex items-center gap-1.5 border border-emerald-200">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Optimal
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div class="space-y-2">
                            <div class="flex justify-between text-xs">
                                <span class="font-['JetBrains_Mono'] text-slate-500 text-[10px] uppercase">CPU Core Load</span>
                                <span class="text-[#0059bb] font-bold">24%</span>
                            </div>
                            <div class="h-2 w-full bg-slate-200 rounded-full overflow-hidden">
                                <div class="h-full bg-[#0059bb] w-[24%]"></div>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between text-xs">
                                <span class="font-['JetBrains_Mono'] text-slate-500 text-[10px] uppercase">RAM Reserved</span>
                                <span class="text-cyan-500 font-bold">68%</span>
                            </div>
                            <div class="h-2 w-full bg-slate-200 rounded-full overflow-hidden">
                                <div class="h-full bg-cyan-500 w-[68%]"></div>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between text-xs">
                                <span class="font-['JetBrains_Mono'] text-slate-500 text-[10px] uppercase">Bandwidth TX/RX</span>
                                <span class="text-slate-900 font-bold">41%</span>
                            </div>
                            <div class="h-2 w-full bg-slate-200 rounded-full overflow-hidden">
                                <div class="h-full bg-slate-900 w-[41%]"></div>
                            </div>
                        </div>
                    </div>

                    <div class="h-32 w-full bg-gradient-to-t from-blue-50 to-transparent rounded-2xl border border-slate-200/60 flex items-end p-4 gap-2">
                        <div class="flex-1 bg-[#0059bb]/20 rounded-t-lg h-[30%]"></div>
                        <div class="flex-1 bg-[#0059bb]/30 rounded-t-lg h-[50%]"></div>
                        <div class="flex-1 bg-[#0059bb]/20 rounded-t-lg h-[40%]"></div>
                        <div class="flex-1 bg-[#0059bb]/40 rounded-t-lg h-[70%]"></div>
                        <div class="flex-1 bg-[#0059bb]/30 rounded-t-lg h-[60%]"></div>
                        <div class="flex-1 bg-[#0059bb]/50 rounded-t-lg h-[85%]"></div>
                        <div class="flex-1 bg-[#0059bb]/30 rounded-t-lg h-[45%]"></div>
                        <div class="flex-1 bg-[#0059bb]/60 rounded-t-lg h-[90%]"></div>
                    </div>
                </div>

                <!-- Domain Expiry Alert Card from Stitch -->
                <div class="bg-[#0d1c32] rounded-3xl p-8 text-white shadow-xl flex flex-col justify-between relative overflow-hidden">
                    <div class="relative z-10">
                        <span class="material-symbols-outlined text-cyan-400 text-4xl mb-3">history_toggle_off</span>
                        <h3 class="font-['Hanken_Grotesk'] text-2xl font-bold mb-1">Domain Expiry Alert</h3>
                        <p class="text-slate-400 text-xs">Action required to prevent service interruption.</p>
                    </div>

                    <div class="space-y-4 relative z-10 my-6">
                        @foreach($domains as $dom)
                            <div class="flex flex-col gap-1">
                                <div class="flex justify-between text-xs">
                                    <span class="font-['JetBrains_Mono'] font-bold">{{ $dom->domain_name }}</span>
                                    <span class="text-emerald-400 font-bold">{{ $dom->expiry_date->format('M d, Y') }}</span>
                                </div>
                                <div class="h-1 bg-slate-700 rounded-full overflow-hidden">
                                    <div class="h-full bg-emerald-400 w-[85%]"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <a href="{{ route('domains.search') }}" class="bg-white text-slate-900 font-bold text-xs py-3 rounded-xl w-full text-center hover:bg-[#00F5FF] transition-colors block">
                        Renew / Search Domains
                    </a>
                </div>
            </div>

            <!-- Dedicated Navigation Page Links -->
            <div class="flex items-center gap-2 border-b border-slate-200 pb-2 overflow-x-auto">
                <a href="{{ route('dashboard') }}" :class="currentTab === 'overview' ? 'border-[#0059bb] text-[#0059bb] bg-[#0059bb]/10 font-bold' : 'border-transparent text-slate-600 hover:text-slate-900'" class="px-5 py-2.5 border-b-2 text-xs rounded-t-xl transition-all whitespace-nowrap">Overview</a>
                <a href="{{ route('dashboard.services') }}" :class="currentTab === 'services' ? 'border-[#0059bb] text-[#0059bb] bg-[#0059bb]/10 font-bold' : 'border-transparent text-slate-600 hover:text-slate-900'" class="px-5 py-2.5 border-b-2 text-xs rounded-t-xl transition-all whitespace-nowrap">Hosting Services ({{ count($hostingServices) }})</a>
                <a href="{{ route('dashboard.domains') }}" :class="currentTab === 'domains' ? 'border-[#0059bb] text-[#0059bb] bg-[#0059bb]/10 font-bold' : 'border-transparent text-slate-600 hover:text-slate-900'" class="px-5 py-2.5 border-b-2 text-xs rounded-t-xl transition-all whitespace-nowrap">Domains ({{ count($domains) }})</a>
                <a href="{{ route('dashboard.invoices') }}" :class="currentTab === 'billing' ? 'border-[#0059bb] text-[#0059bb] bg-[#0059bb]/10 font-bold' : 'border-transparent text-slate-600 hover:text-slate-900'" class="px-5 py-2.5 border-b-2 text-xs rounded-t-xl transition-all whitespace-nowrap">Invoices & Billing</a>
                <a href="{{ route('dashboard.tickets') }}" :class="currentTab === 'support' ? 'border-[#0059bb] text-[#0059bb] bg-[#0059bb]/10 font-bold' : 'border-transparent text-slate-600 hover:text-slate-900'" class="px-5 py-2.5 border-b-2 text-xs rounded-t-xl transition-all whitespace-nowrap">Support Desk</a>
            </div>

            <!-- OVERVIEW SECTION -->
            <div x-show="currentTab === 'overview'" class="space-y-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Active Services Card -->
                    <div class="lg:col-span-2 space-y-6">
                        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-['Hanken_Grotesk'] text-lg font-bold text-slate-900">Active Web Hosting Accounts</h3>
                                <a href="{{ route('dashboard.services') }}" class="text-xs text-[#0059bb] font-semibold hover:underline">View All &rarr;</a>
                            </div>
                            <div class="space-y-3">
                                @foreach($hostingServices as $service)
                                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-[#0059bb]/10 text-[#0059bb] flex items-center justify-center font-bold">
                                                <span class="material-symbols-outlined">dns</span>
                                            </div>
                                            <div>
                                                <div class="font-bold text-sm text-slate-900">{{ $service->domain_name }}</div>
                                                <div class="text-xs text-slate-500">{{ $service->hostingPlan->name }} • {{ $service->server->name ?? 'Nairobi Server' }}</div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold badge-active uppercase">{{ $service->status }}</span>
                                            <button @click="selectedService = {{ json_encode($service) }}; showCpanelModal = true" class="px-3.5 py-1.5 bg-slate-900 hover:bg-[#0059bb] text-white text-xs font-bold rounded-xl transition-colors">
                                                cPanel SSO
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Pending Billing -->
                    <div class="space-y-6">
                        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
                            <h3 class="font-['Hanken_Grotesk'] text-lg font-bold text-slate-900 mb-4">Pending Invoices</h3>
                            <div class="space-y-3">
                                @forelse($invoices->where('status', 'pending') as $inv)
                                    <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200">
                                        <div class="flex items-center justify-between">
                                            <span class="font-['JetBrains_Mono'] text-xs font-bold text-amber-700">{{ $inv->invoice_number }}</span>
                                            <span class="text-sm font-extrabold text-slate-900">KES {{ number_format($inv->total, 2) }}</span>
                                        </div>
                                        <p class="text-[11px] text-slate-600 mt-1 truncate">{{ $inv->description }}</p>
                                        <button @click="selectedInvoice = {{ json_encode($inv) }}; showPayModal = true" class="mt-3 w-full py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl shadow transition-all">
                                            Pay Now (M-Pesa / Card)
                                        </button>
                                    </div>
                                @empty
                                    <p class="text-xs text-slate-500">No pending invoices. All services current!</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MY SERVICES SECTION -->
            <div x-show="currentTab === 'services'" class="space-y-6" x-cloak>
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80">
                    <h2 class="font-['Hanken_Grotesk'] text-xl font-bold text-slate-900 mb-4">My Web Hosting Accounts</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($hostingServices as $service)
                            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="font-['Hanken_Grotesk'] text-lg font-bold text-slate-900">{{ $service->domain_name }}</span>
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold badge-active uppercase">{{ $service->status }}</span>
                                </div>
                                <div class="grid grid-cols-2 gap-2 text-xs">
                                    <div class="bg-white p-2.5 rounded-xl border border-slate-200">
                                        <span class="text-slate-400 block text-[10px]">Package</span>
                                        <span class="font-bold text-slate-900">{{ $service->hostingPlan->name }}</span>
                                    </div>
                                    <div class="bg-white p-2.5 rounded-xl border border-slate-200">
                                        <span class="text-slate-400 block text-[10px]">Server Node</span>
                                        <span class="font-bold text-slate-900">{{ $service->server->name ?? 'Nairobi-Edge-01' }}</span>
                                    </div>
                                    <div class="bg-white p-2.5 rounded-xl border border-slate-200">
                                        <span class="text-slate-400 block text-[10px]">cPanel Username</span>
                                        <span class="font-mono font-bold text-[#0059bb]">{{ $service->username }}</span>
                                    </div>
                                    <div class="bg-white p-2.5 rounded-xl border border-slate-200">
                                        <span class="text-slate-400 block text-[10px]">Next Due</span>
                                        <span class="font-bold text-slate-900">{{ $service->next_due_date->format('M d, Y') }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button @click="selectedService = {{ json_encode($service) }}; showCpanelModal = true" class="flex-1 py-2.5 bg-[#0059bb] hover:bg-blue-600 text-white font-bold text-xs rounded-xl shadow">
                                        cPanel SSO
                                    </button>
                                    <button @click="selectedService = {{ json_encode($service) }}; showUpgradeModal = true" class="px-3 py-2.5 bg-slate-900 text-white font-bold text-xs rounded-xl shadow">
                                        Upgrade
                                    </button>
                                    <form method="POST" action="{{ route('dashboard.services.cancel', $service->id) }}">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Are you sure you want to request cancellation for {{ $service->domain_name }}?')" class="px-3 py-2.5 bg-rose-100 text-rose-700 font-bold text-xs rounded-xl hover:bg-rose-200">
                                            Cancel
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- DOMAINS SECTION ENHANCED WITH REGISTRAR INTEGRATIONS -->
            <div x-show="currentTab === 'domains'" class="space-y-6" x-cloak>
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="font-['Hanken_Grotesk'] text-xl font-bold text-slate-900">Domain Management Portal</h2>
                            <p class="text-xs text-slate-500 font-medium">Manage WHOIS Privacy, Domain Lock, Nameservers & API Registrar integrations.</p>
                        </div>
                        <a href="{{ route('domains.search') }}" class="px-4 py-2 bg-[#000000] hover:bg-[#0059bb] text-white font-bold text-xs rounded-xl shadow transition-all">+ Register Domain</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr class="border-b border-slate-200 text-slate-500 font-['JetBrains_Mono']">
                                    <th class="py-3 px-4">Domain Name</th>
                                    <th class="py-3 px-4">Registrar Provider</th>
                                    <th class="py-3 px-4">Expiry Date</th>
                                    <th class="py-3 px-4">WHOIS Privacy</th>
                                    <th class="py-3 px-4">Transfer Lock</th>
                                    <th class="py-3 px-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($domains as $dom)
                                    <tr>
                                        <td class="py-4 px-4 font-bold text-slate-900 text-sm">
                                            <span>{{ $dom->domain_name }}</span>
                                            <span class="block text-[10px] font-normal text-slate-400">ID: {{ $dom->registrar_domain_id ?? 'REG-' . $dom->id }}</span>
                                        </td>

                                        <td class="py-4 px-4 font-semibold text-slate-700">
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-[#0059bb] border border-blue-200">
                                                {{ $dom->registrarRecord?->name ?? $dom->registrar }}
                                            </span>
                                        </td>

                                        <td class="py-4 px-4 text-slate-700 font-semibold">
                                            <span>{{ $dom->expiry_date->format('Y-m-d') }}</span>
                                            <span class="block text-[10px] text-emerald-600 font-bold">({{ $dom->expiry_date->diffForHumans() }})</span>
                                        </td>

                                        <td class="py-4 px-4">
                                            <form method="POST" action="{{ route('dashboard.domains.whois-privacy', $dom->id) }}">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1 rounded-full text-[10px] font-bold border transition-colors {{ $dom->whois_privacy_enabled ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-500 border-slate-200' }}">
                                                    {{ $dom->whois_privacy_enabled ? '🔒 PRIVACY ON' : '🔓 PRIVACY OFF' }}
                                                </button>
                                            </form>
                                        </td>

                                        <td class="py-4 px-4">
                                            <form method="POST" action="{{ route('dashboard.domains.lock', $dom->id) }}">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1 rounded-full text-[10px] font-bold border transition-colors {{ $dom->is_locked ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'bg-amber-100 text-amber-700 border-amber-200' }}">
                                                    {{ $dom->is_locked ? 'LOCKED' : 'UNLOCKED' }}
                                                </button>
                                            </form>
                                        </td>

                                        <td class="py-4 px-4 text-right flex items-center justify-end gap-2">
                                            <button @click="selectedDomain = {{ json_encode($dom) }}; showNsModal = true" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 font-bold text-slate-800 text-xs rounded-xl transition-colors">
                                                NS Config
                                            </button>
                                            <button @click="selectedDomain = {{ json_encode($dom) }}; showDnsModal = true" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 font-bold text-slate-800 text-xs rounded-xl transition-colors">
                                                DNS Manager
                                            </button>
                                            <form method="POST" action="{{ route('dashboard.domains.renew', $dom->id) }}">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 bg-emerald-600 text-white font-bold text-xs rounded-xl hover:bg-emerald-500 shadow transition-colors">
                                                    Renew
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- BILLING SECTION -->
            <div x-show="currentTab === 'billing'" class="space-y-6" x-cloak>
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80">
                    <h2 class="font-['Hanken_Grotesk'] text-xl font-bold text-slate-900 mb-6">Invoices & Billing</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr class="border-b border-slate-200 text-slate-500 font-['JetBrains_Mono']">
                                    <th class="py-3 px-4">Invoice #</th>
                                    <th class="py-3 px-4">Description</th>
                                    <th class="py-3 px-4">Total Amount</th>
                                    <th class="py-3 px-4">Due Date</th>
                                    <th class="py-3 px-4">Status</th>
                                    <th class="py-3 px-4 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($invoices as $inv)
                                    <tr>
                                        <td class="py-4 px-4 font-mono font-bold text-[#0059bb]">{{ $inv->invoice_number }}</td>
                                        <td class="py-4 px-4 text-slate-700 font-semibold">{{ $inv->description }}</td>
                                        <td class="py-4 px-4 font-extrabold text-slate-900">KES {{ number_format($inv->total, 2) }}</td>
                                        <td class="py-4 px-4 text-slate-500">{{ $inv->due_date->format('Y-m-d') }}</td>
                                        <td class="py-4 px-4">
                                            @if($inv->status === 'paid')
                                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold badge-active uppercase">Paid</span>
                                            @else
                                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold badge-pending uppercase">Pending</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4 text-right">
                                            @if($inv->status === 'pending')
                                                <button @click="selectedInvoice = {{ json_encode($inv) }}; showPayModal = true" class="px-4 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl shadow">
                                                    Pay Now
                                                </button>
                                            @else
                                                <span class="text-[10px] text-slate-400 font-semibold">Receipt Paid</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- SUPPORT SECTION -->
            <div x-show="currentTab === 'support'" class="space-y-6" x-cloak>
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="font-['Hanken_Grotesk'] text-xl font-bold text-slate-900">Support Ticket Desk</h2>
                        <button @click="showNewTicket = true" class="px-4 py-2 bg-[#0059bb] text-white font-bold text-xs rounded-xl">+ Create Ticket</button>
                    </div>

                    <div class="space-y-6">
                        @foreach($tickets as $t)
                            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <span class="font-mono text-xs font-bold text-[#0059bb] bg-[#0059bb]/10 px-2.5 py-1 rounded-lg border border-[#0059bb]/20">{{ $t->ticket_number }}</span>
                                        <h3 class="font-['Hanken_Grotesk'] text-base font-bold text-slate-900">{{ $t->subject }}</h3>
                                    </div>
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold badge-active uppercase">{{ $t->status }}</span>
                                </div>

                                <div class="bg-white rounded-xl p-4 border border-slate-200 space-y-3">
                                    @foreach($t->messages as $msg)
                                        <div class="p-3 rounded-lg text-xs {{ $msg->user_id === auth()->id() ? 'bg-blue-50 border border-blue-100 ml-4' : 'bg-slate-100 mr-4' }}">
                                            <div class="flex justify-between mb-1">
                                                <span class="font-bold {{ $msg->user_id === auth()->id() ? 'text-[#0059bb]' : 'text-amber-700' }}">{{ $msg->user->name }}</span>
                                                <span class="text-[10px] text-slate-400">{{ $msg->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-slate-800 leading-relaxed">{{ $msg->message }}</p>
                                        </div>
                                    @endforeach
                                </div>

                                <form method="POST" action="{{ route('dashboard.tickets.reply', $t->id) }}" class="flex gap-3">
                                    @csrf
                                    <input type="text" name="message" required placeholder="Type your reply to support..." class="flex-1 px-4 py-2 bg-white border border-slate-300 rounded-xl text-xs text-slate-900">
                                    <button type="submit" class="px-4 py-2 bg-[#0059bb] text-white font-bold text-xs rounded-xl shadow">Reply</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL: PAY INVOICE -->
        <div x-show="showPayModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak>
            <div class="bg-white p-8 rounded-3xl border border-slate-200 max-w-md w-full shadow-2xl" @click.away="showPayModal = false">
                <h3 class="font-['Hanken_Grotesk'] text-xl font-bold text-slate-900 mb-2">Pay Invoice</h3>
                <template x-if="selectedInvoice">
                    <div>
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 mb-6">
                            <div class="text-xs text-slate-500">Invoice Number</div>
                            <div class="font-mono text-sm font-bold text-[#0059bb]" x-text="selectedInvoice.invoice_number"></div>
                            <div class="text-lg font-extrabold text-slate-900 mt-2">Total Due: KES <span x-text="selectedInvoice.total"></span></div>
                        </div>

                        <form method="POST" :action="'/dashboard/invoices/' + selectedInvoice.id + '/pay'" class="space-y-4">
                            @csrf
                            <div>
                                <label class="text-xs font-semibold text-slate-700 block mb-2">Payment Gateway</label>
                                <select name="payment_method" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900">
                                    <option value="mpesa">M-Pesa STK Push (Kenya)</option>
                                    <option value="airtel_money">Airtel Money</option>
                                    <option value="stripe">Credit / Debit Card (Stripe)</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-slate-700 block mb-1">Mobile Number</label>
                                <input type="text" name="phone" value="{{ auth()->user()->phone ?? '+254712345678' }}" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900">
                            </div>
                            <button type="submit" class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl shadow-lg">
                                Send M-Pesa STK Push / Pay Now
                            </button>
                        </form>
                    </div>
                </template>
            </div>
        </div>

        <!-- MODAL: NAMESERVERS EDITOR -->
        <div x-show="showNsModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak>
            <div class="bg-white p-8 rounded-3xl border border-slate-200 max-w-md w-full shadow-2xl" @click.away="showNsModal = false">
                <h3 class="font-['Hanken_Grotesk'] text-xl font-bold text-slate-900 mb-2">Edit Nameservers</h3>
                <template x-if="selectedDomain">
                    <form method="POST" :action="'/dashboard/domains/' + selectedDomain.id + '/nameservers'" class="space-y-4">
                        @csrf
                        <div>
                            <label class="text-xs font-semibold text-slate-700 block mb-1">Primary Nameserver (NS1)</label>
                            <input type="text" name="ns1" value="ns1.hostninja.cloud" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-slate-900">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-700 block mb-1">Secondary Nameserver (NS2)</label>
                            <input type="text" name="ns2" value="ns2.hostninja.cloud" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-slate-900">
                        </div>
                        <button type="submit" class="w-full py-3 bg-[#0059bb] hover:bg-blue-600 text-white font-bold text-xs rounded-xl shadow">
                            Save Nameservers
                        </button>
                    </form>
                </template>
            </div>
        </div>

        <!-- MODAL: HOSTING SERVICE UPGRADE -->
        <div x-show="showUpgradeModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak>
            <div class="bg-white p-8 rounded-3xl border border-slate-200 max-w-md w-full shadow-2xl" @click.away="showUpgradeModal = false">
                <h3 class="font-['Hanken_Grotesk'] text-xl font-bold text-slate-900 mb-2">Upgrade Hosting Package</h3>
                <template x-if="selectedService">
                    <form method="POST" :action="'/dashboard/services/' + selectedService.id + '/upgrade'" class="space-y-4">
                        @csrf
                        <div>
                            <label class="text-xs font-semibold text-slate-700 block mb-2">Target Package</label>
                            <select name="new_plan_id" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900">
                                @foreach($hostingPlans as $hp)
                                    <option value="{{ $hp->id }}">{{ $hp->name }} - KES {{ number_format($hp->price_monthly) }}/mo ({{ $hp->storage_gb }}GB NVMe)</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="w-full py-3 bg-[#0059bb] hover:bg-blue-600 text-white font-bold text-xs rounded-xl shadow">
                            Confirm Upgrade
                        </button>
                    </form>
                </template>
            </div>
        </div>

        <!-- MODAL: CREATE TICKET -->
        <div x-show="showNewTicket" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak>
            <div class="bg-white p-8 rounded-3xl border border-slate-200 max-w-lg w-full shadow-2xl" @click.away="showNewTicket = false">
                <h3 class="font-['Hanken_Grotesk'] text-xl font-bold text-slate-900 mb-4">Open Support Ticket</h3>
                <form method="POST" action="{{ route('dashboard.tickets.create') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="text-xs font-semibold text-slate-700 block mb-1">Subject</label>
                        <input type="text" name="subject" required placeholder="Summary of issue..." class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-semibold text-slate-700 block mb-1">Category</label>
                            <select name="category" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900">
                                <option value="technical">Technical Support</option>
                                <option value="billing">Billing & Payments</option>
                                <option value="domain">Domain Registration</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-700 block mb-1">Priority</label>
                            <select name="priority" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-700 block mb-1">Message Detail</label>
                        <textarea name="message" rows="4" required placeholder="Describe request..." class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900"></textarea>
                    </div>
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="button" @click="showNewTicket = false" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-semibold rounded-xl">Cancel</button>
                        <button type="submit" class="px-6 py-2 bg-[#0059bb] text-white font-bold text-xs rounded-xl shadow">Submit Ticket</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL: cPANEL SSO -->
        <div x-show="showCpanelModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak>
            <div class="bg-white p-8 rounded-3xl border border-slate-200 max-w-md w-full shadow-2xl" @click.away="showCpanelModal = false">
                <h3 class="font-['Hanken_Grotesk'] text-xl font-bold text-slate-900 mb-2">cPanel Control Panel</h3>
                <template x-if="selectedService">
                    <div class="space-y-4">
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 text-xs space-y-2">
                            <div><span class="text-slate-500">Domain:</span> <span class="font-bold text-slate-900" x-text="selectedService.domain_name"></span></div>
                            <div><span class="text-slate-500">cPanel Host:</span> <span class="font-mono text-[#0059bb]">https://cpanel.hostninja.cloud:2083</span></div>
                            <div><span class="text-slate-500">Username:</span> <span class="font-mono text-emerald-600 font-bold" x-text="selectedService.username"></span></div>
                        </div>

                        <button @click="alert('Redirecting to cPanel SSO Control Panel session...'); showCpanelModal = false" class="w-full py-3 bg-[#0059bb] hover:bg-blue-600 text-white font-bold text-xs rounded-xl shadow-lg">
                            Launch cPanel SSO &rarr;
                        </button>
                    </div>
                </template>
            </div>
        </div>

        <!-- MODAL: DNS MANAGER -->
        <div x-show="showDnsModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak>
            <div class="bg-white p-8 rounded-3xl border border-slate-200 max-w-lg w-full shadow-2xl" @click.away="showDnsModal = false">
                <h3 class="font-['Hanken_Grotesk'] text-xl font-bold text-slate-900 mb-2">DNS Records Manager</h3>
                <template x-if="selectedDomain">
                    <div class="space-y-4">
                        <div class="font-bold text-sm text-[#0059bb]" x-text="selectedDomain.domain_name"></div>
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 text-xs space-y-2 font-mono">
                            <div class="flex justify-between border-b border-slate-200 pb-1 text-slate-400">
                                <span>TYPE</span> <span>NAME</span> <span>VALUE</span> <span>TTL</span>
                            </div>
                            <div class="flex justify-between text-slate-800">
                                <span>A</span> <span>@</span> <span>197.248.0.12</span> <span>3600</span>
                            </div>
                            <div class="flex justify-between text-slate-800">
                                <span>CNAME</span> <span>www</span> <span>@</span> <span>3600</span>
                            </div>
                            <div class="flex justify-between text-slate-800">
                                <span>MX</span> <span>@</span> <span>mail.domain.com</span> <span>3600</span>
                            </div>
                        </div>

                        <button @click="alert('DNS Records saved to nameservers ns1.hostninja.cloud!'); showDnsModal = false" class="w-full py-3 bg-slate-900 hover:bg-[#0059bb] text-white font-bold text-xs rounded-xl shadow">
                            Save DNS Configuration
                        </button>
                    </div>
                </template>
            </div>
        </div>

    </div>
</x-app-layout>
