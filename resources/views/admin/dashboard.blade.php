<x-admin-layout>
    <x-slot name="title">HostNinja Admin Dashboard</x-slot>

    <div class="space-y-8" x-data="{ currentTab: '{{ $tab }}', showNewPlanModal: false }">

        @if(session('success'))
            <div class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-700 text-xs font-bold flex items-center gap-2">
                <span class="material-symbols-outlined text-amber-600">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Quick Admin Nav -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1 class="font-['Hanken_Grotesk'] text-2xl font-extrabold text-slate-900">Infrastructure & System Overview</h1>
                <p class="text-xs text-slate-500">Real-time enterprise metrics, servers, client accounts, and billing management.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.payment-gateways') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl shadow transition-all flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-sm">payments</span>
                    <span>Payment Gateways</span>
                </a>
                <a href="{{ route('admin.settings') }}" class="px-4 py-2 bg-slate-900 hover:bg-[#0059bb] text-white font-bold text-xs rounded-xl shadow transition-all flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-sm">settings</span>
                    <span>Settings</span>
                </a>
                <a href="{{ route('reseller.dashboard') }}" class="px-4 py-2 bg-[#0059bb] hover:bg-blue-600 text-white font-bold text-xs rounded-xl shadow transition-all flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-sm">handshake</span>
                    <span>Reseller</span>
                </a>
            </div>
        </div>

        <!-- Instant Alpine.js Navigation Filter Tabs -->
        <div class="flex items-center gap-2 border-b border-slate-200 pb-2 overflow-x-auto">
            <button type="button" @click="currentTab = 'overview'" :class="currentTab === 'overview' ? 'border-[#0059bb] text-[#0059bb] bg-[#0059bb]/10 font-bold' : 'border-transparent text-slate-600 hover:text-slate-900'" class="px-5 py-2.5 border-b-2 text-xs rounded-t-xl transition-all whitespace-nowrap">Overview Analytics</button>
            <button type="button" @click="currentTab = 'users'" :class="currentTab === 'users' ? 'border-[#0059bb] text-[#0059bb] bg-[#0059bb]/10 font-bold' : 'border-transparent text-slate-600 hover:text-slate-900'" class="px-5 py-2.5 border-b-2 text-xs rounded-t-xl transition-all whitespace-nowrap">Users & Accounts ({{ $customerCount }})</button>
            <button type="button" @click="currentTab = 'servers'" :class="currentTab === 'servers' ? 'border-[#0059bb] text-[#0059bb] bg-[#0059bb]/10 font-bold' : 'border-transparent text-slate-600 hover:text-slate-900'" class="px-5 py-2.5 border-b-2 text-xs rounded-t-xl transition-all whitespace-nowrap">Server Nodes ({{ count($servers) }})</button>
            <button type="button" @click="currentTab = 'plans'" :class="currentTab === 'plans' ? 'border-[#0059bb] text-[#0059bb] bg-[#0059bb]/10 font-bold' : 'border-transparent text-slate-600 hover:text-slate-900'" class="px-5 py-2.5 border-b-2 text-xs rounded-t-xl transition-all whitespace-nowrap">Packages ({{ count($hostingPlans) }})</button>
            <button type="button" @click="currentTab = 'tickets'" :class="currentTab === 'tickets' ? 'border-[#0059bb] text-[#0059bb] bg-[#0059bb]/10 font-bold' : 'border-transparent text-slate-600 hover:text-slate-900'" class="px-5 py-2.5 border-b-2 text-xs rounded-t-xl transition-all whitespace-nowrap">Support Queue ({{ $openTicketsCount }})</button>
            <button type="button" @click="currentTab = 'invoices'" :class="currentTab === 'invoices' ? 'border-[#0059bb] text-[#0059bb] bg-[#0059bb]/10 font-bold' : 'border-transparent text-slate-600 hover:text-slate-900'" class="px-5 py-2.5 border-b-2 text-xs rounded-t-xl transition-all whitespace-nowrap">Invoices & Billing</button>
        </div>

        <!-- Financial Summary Cards from Stitch -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Card 1: Revenue -->
            <div class="glass-card p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-blue-500/10 rounded-xl text-[#0059bb]">
                        <span class="material-symbols-outlined">payments</span>
                    </div>
                    <span class="text-emerald-600 font-['JetBrains_Mono'] text-[11px] font-bold bg-emerald-500/10 px-2 py-0.5 rounded">+12.5%</span>
                </div>
                <p class="text-slate-500 font-['JetBrains_Mono'] text-[11px] uppercase tracking-wider">Monthly Recurring Revenue</p>
                <h3 class="font-['Hanken_Grotesk'] text-2xl font-extrabold text-slate-900 mt-1">KES {{ number_format($totalRevenue, 2) }}</h3>
            </div>

            <!-- Card 2: Subscriptions -->
            <div class="glass-card p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-indigo-500/10 rounded-xl text-indigo-600">
                        <span class="material-symbols-outlined">group_add</span>
                    </div>
                    <span class="text-emerald-600 font-['JetBrains_Mono'] text-[11px] font-bold bg-emerald-500/10 px-2 py-0.5 rounded">+4.2%</span>
                </div>
                <p class="text-slate-500 font-['JetBrains_Mono'] text-[11px] uppercase tracking-wider">Active Customers</p>
                <h3 class="font-['Hanken_Grotesk'] text-2xl font-extrabold text-slate-900 mt-1">{{ number_format($customerCount) }}</h3>
            </div>

            <!-- Card 3: Nodes -->
            <div class="glass-card p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-slate-900/10 rounded-xl text-slate-900">
                        <span class="material-symbols-outlined">dns</span>
                    </div>
                    <span class="text-slate-500 font-['JetBrains_Mono'] text-[11px] font-bold bg-slate-100 px-2 py-0.5 rounded">Stable</span>
                </div>
                <p class="text-slate-500 font-['JetBrains_Mono'] text-[11px] uppercase tracking-wider">Nodes Online</p>
                <h3 class="font-['Hanken_Grotesk'] text-2xl font-extrabold text-slate-900 mt-1">{{ count($servers) }} Nodes</h3>
            </div>

            <!-- Card 4: Tickets -->
            <div class="glass-card p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow border-t-2 border-cyan-400">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-rose-500/10 rounded-xl text-rose-600">
                        <span class="material-symbols-outlined">confirmation_number</span>
                    </div>
                    <span class="text-rose-600 font-['JetBrains_Mono'] text-[11px] font-bold bg-rose-500/10 px-2 py-0.5 rounded">Action Required</span>
                </div>
                <p class="text-slate-500 font-['JetBrains_Mono'] text-[11px] uppercase tracking-wider">Open Ticket Queue</p>
                <h3 class="font-['Hanken_Grotesk'] text-2xl font-extrabold text-slate-900 mt-1">{{ number_format($openTicketsCount) }}</h3>
            </div>
        </div>

        <!-- OVERVIEW TAB CONTENT -->
        <div x-show="currentTab === 'overview'" class="space-y-8">
            <div class="grid grid-cols-12 gap-6">
                <!-- Revenue Area Chart -->
                <div class="col-span-12 lg:col-span-8 glass-card p-8 rounded-3xl flex flex-col justify-between">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h4 class="font-['Hanken_Grotesk'] text-xl font-bold text-slate-900">Revenue Growth</h4>
                            <p class="text-slate-500 text-xs">Real-time financial performance tracking (KES)</p>
                        </div>
                        <span class="px-3 py-1 font-['JetBrains_Mono'] text-xs font-bold bg-[#0059bb] text-white rounded-lg">Monthly</span>
                    </div>
                    <div class="h-64 relative">
                        <canvas id="revenueChartCanvas"></canvas>
                    </div>
                </div>

                <!-- Customer Acquisition Bar Chart -->
                <div class="col-span-12 lg:col-span-4 glass-card p-8 rounded-3xl flex flex-col justify-between">
                    <div>
                        <h4 class="font-['Hanken_Grotesk'] text-xl font-bold text-slate-900 mb-1">New Customers</h4>
                        <p class="text-slate-500 text-xs mb-6">Acquisition by channel</p>
                    </div>
                    <div class="flex items-end justify-between gap-3 px-2">
                        <div class="flex-1 space-y-2 text-center">
                            <div class="bg-[#0059bb] w-full h-24 rounded-t-lg"></div>
                            <span class="font-['JetBrains_Mono'] text-[10px] text-slate-500 block">Direct</span>
                        </div>
                        <div class="flex-1 space-y-2 text-center">
                            <div class="bg-indigo-600 w-full h-40 rounded-t-lg"></div>
                            <span class="font-['JetBrains_Mono'] text-[10px] text-slate-500 block">Ads</span>
                        </div>
                        <div class="flex-1 space-y-2 text-center">
                            <div class="bg-cyan-500 w-full h-32 rounded-t-lg"></div>
                            <span class="font-['JetBrains_Mono'] text-[10px] text-slate-500 block">Social</span>
                        </div>
                        <div class="flex-1 space-y-2 text-center">
                            <div class="bg-slate-900 w-full h-48 rounded-t-lg"></div>
                            <span class="font-['JetBrains_Mono'] text-[10px] text-slate-500 block">Referral</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SERVERS TAB CONTENT -->
        <div x-show="currentTab === 'servers' || currentTab === 'overview'" class="space-y-6">
            <section class="glass-card rounded-3xl overflow-hidden border border-slate-200 shadow-sm p-6 space-y-4">
                <div class="flex justify-between items-center">
                    <h4 class="font-['Hanken_Grotesk'] text-xl font-bold text-slate-900">Infrastructure Logs & Server Nodes</h4>
                    <div class="flex items-center bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-xs font-['JetBrains_Mono'] font-bold">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2 animate-pulse"></span> Live Cluster Stream
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-slate-50 border-b border-slate-200 font-['JetBrains_Mono'] text-slate-500 uppercase">
                            <tr>
                                <th class="px-6 py-4">Node Name</th>
                                <th class="px-6 py-4">IP Address</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Active Accounts</th>
                                <th class="px-6 py-4">Disk Load</th>
                                <th class="px-6 py-4">CPU Load</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($servers as $srv)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 font-bold text-slate-900">{{ $srv->name }}</td>
                                    <td class="px-6 py-4 font-['JetBrains_Mono'] text-slate-600">{{ $srv->ip_address }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold badge-active uppercase">{{ $srv->status }}</span>
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-slate-800">{{ $srv->active_accounts }} / {{ $srv->max_accounts }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-24 bg-slate-200 h-1.5 rounded-full overflow-hidden">
                                                <div class="bg-[#0059bb] h-1.5 rounded-full" style="width: {{ $srv->disk_usage_percent }}%"></div>
                                            </div>
                                            <span class="font-bold text-slate-700">{{ $srv->disk_usage_percent }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-emerald-600">{{ $srv->cpu_usage_percent }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pt-4 border-t border-slate-200">
                    <h5 class="font-['Hanken_Grotesk'] text-base font-bold text-slate-900 mb-3">Hosted Client cPanel Accounts Control Panel</h5>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr class="border-b border-slate-200 text-slate-500 font-['JetBrains_Mono']">
                                    <th class="py-3 px-4">Client</th>
                                    <th class="py-3 px-4">Domain</th>
                                    <th class="py-3 px-4">Package</th>
                                    <th class="py-3 px-4">Server</th>
                                    <th class="py-3 px-4">Status</th>
                                    <th class="py-3 px-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($allServices as $svc)
                                    <tr>
                                        <td class="py-3 px-4 font-bold text-slate-900">{{ $svc->user->name ?? 'User' }}</td>
                                        <td class="py-3 px-4 font-mono text-[#0059bb] font-bold">{{ $svc->domain_name }}</td>
                                        <td class="py-3 px-4 text-slate-700">{{ $svc->hostingPlan->name }}</td>
                                        <td class="py-3 px-4 text-slate-500">{{ $svc->server->name ?? 'Nairobi Node' }}</td>
                                        <td class="py-3 px-4">
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold badge-active uppercase">{{ $svc->status }}</span>
                                        </td>
                                        <td class="py-3 px-4 text-right flex items-center justify-end gap-2">
                                            @if($svc->status === 'active')
                                                <form method="POST" action="{{ route('admin.services.suspend', $svc->id) }}">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1 bg-amber-500 text-white font-bold text-[10px] rounded-lg hover:bg-amber-600">
                                                        Suspend
                                                    </button>
                                                </form>
                                            @else
                                                <form method="POST" action="{{ route('admin.services.unsuspend', $svc->id) }}">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1 bg-emerald-600 text-white font-bold text-[10px] rounded-lg hover:bg-emerald-500">
                                                        Reactivate
                                                    </button>
                                                </form>
                                            @endif

                                            <form method="POST" action="{{ route('admin.services.terminate', $svc->id) }}">
                                                @csrf
                                                <button type="submit" onclick="return confirm('Terminate account {{ $svc->domain_name }}?')" class="px-3 py-1 bg-rose-600 text-white font-bold text-[10px] rounded-lg hover:bg-rose-500">
                                                    Terminate
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>

        <!-- USERS TAB CONTENT -->
        <div x-show="currentTab === 'users' || currentTab === 'overview'" class="space-y-6">
            <div class="glass-card p-6 rounded-3xl border border-slate-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-['Hanken_Grotesk'] text-lg font-bold text-slate-900">Registered Client Accounts</h3>
                    <span class="text-xs text-slate-500 font-['JetBrains_Mono']">Total: {{ count($recentUsers) }}</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200 text-slate-500 font-['JetBrains_Mono']">
                                <th class="py-2.5 px-3">Client Name</th>
                                <th class="py-2.5 px-3">Email</th>
                                <th class="py-2.5 px-3">Role</th>
                                <th class="py-2.5 px-3">Company</th>
                                <th class="py-2.5 px-3">Balance</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($recentUsers as $u)
                                <tr>
                                    <td class="py-3 px-3 font-bold text-slate-900">{{ $u->name }}</td>
                                    <td class="py-3 px-3 font-mono text-slate-600">{{ $u->email }}</td>
                                    <td class="py-3 px-3"><span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-blue-600 uppercase">{{ $u->role }}</span></td>
                                    <td class="py-3 px-3 text-slate-700 font-semibold">{{ $u->company ?? 'Personal' }}</td>
                                    <td class="py-3 px-3 font-semibold text-emerald-600">KES {{ number_format($u->balance, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- PLANS TAB CONTENT -->
        <div x-show="currentTab === 'plans' || currentTab === 'overview'" class="space-y-6">
            <div class="glass-card p-6 rounded-3xl border border-slate-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-['Hanken_Grotesk'] text-lg font-bold text-slate-900">Hosting Packages & Pricing</h3>
                    <button @click="showNewPlanModal = true" class="px-3.5 py-1.5 bg-slate-900 hover:bg-[#0059bb] text-white text-xs font-bold rounded-xl transition-all">+ Add Package</button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($hostingPlans as $hp)
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 flex items-center justify-between text-xs">
                            <div>
                                <div class="font-bold text-slate-900 text-sm">{{ $hp->name }}</div>
                                <div class="text-slate-500">{{ $hp->storage_gb }}GB NVMe • {{ $hp->bandwidth_gb }}GB Bandwidth • Free SSL</div>
                            </div>
                            <span class="font-bold text-[#0059bb] text-sm">KES {{ number_format($hp->price_monthly) }}/mo</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- TICKETS TAB CONTENT -->
        <div x-show="currentTab === 'tickets'" class="space-y-6" x-cloak>
            <div class="glass-card p-6 rounded-3xl border border-slate-200">
                <h3 class="font-['Hanken_Grotesk'] text-lg font-bold text-slate-900 mb-4">Support Ticket Queue</h3>
                <div class="space-y-4">
                    @foreach($ticketQueue as $t)
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 flex items-center justify-between text-xs">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-mono font-bold text-[#0059bb]">{{ $t->ticket_number }}</span>
                                    <span class="font-bold text-slate-900">{{ $t->subject }}</span>
                                </div>
                                <div class="text-slate-500 mt-1">From: {{ $t->user->name ?? 'Customer' }} • Category: {{ ucfirst($t->category) }}</div>
                            </div>
                            <span class="px-2 py-1 rounded text-[10px] font-bold uppercase bg-amber-100 text-amber-800">{{ $t->status }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- INVOICES TAB CONTENT -->
        <div x-show="currentTab === 'invoices'" class="space-y-6" x-cloak>
            <div class="glass-card p-6 rounded-3xl border border-slate-200">
                <h3 class="font-['Hanken_Grotesk'] text-lg font-bold text-slate-900 mb-4">Billing & Recent Invoices</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200 text-slate-500 font-['JetBrains_Mono']">
                                <th class="py-3 px-4">Invoice #</th>
                                <th class="py-3 px-4">Client</th>
                                <th class="py-3 px-4">Description</th>
                                <th class="py-3 px-4">Total Amount</th>
                                <th class="py-3 px-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($recentInvoices as $inv)
                                <tr>
                                    <td class="py-3 px-4 font-mono font-bold text-[#0059bb]">{{ $inv->invoice_number }}</td>
                                    <td class="py-3 px-4 font-bold text-slate-900">{{ $inv->user->name ?? 'Client' }}</td>
                                    <td class="py-3 px-4 text-slate-600">{{ $inv->description }}</td>
                                    <td class="py-3 px-4 font-extrabold text-slate-900">KES {{ number_format($inv->total, 2) }}</td>
                                    <td class="py-3 px-4">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $inv->status === 'paid' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }} uppercase">{{ $inv->status }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- MODAL: ADD NEW PACKAGE -->
        <div x-show="showNewPlanModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak>
            <div class="bg-white p-8 rounded-3xl border border-slate-200 max-w-md w-full shadow-2xl" @click.away="showNewPlanModal = false">
                <h3 class="font-['Hanken_Grotesk'] text-xl font-bold text-slate-900 mb-4">Create Hosting Package</h3>
                <form method="POST" action="{{ route('admin.plans.create') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="text-xs font-semibold text-slate-700 block mb-1">Plan Name</label>
                        <input type="text" name="name" required placeholder="e.g. Developer Turbo Plan" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-semibold text-slate-700 block mb-1">Monthly Price (KES)</label>
                            <input type="number" name="price_monthly" required placeholder="799" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-700 block mb-1">Yearly Price (KES)</label>
                            <input type="number" name="price_yearly" required placeholder="7990" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-semibold text-slate-700 block mb-1">Storage (GB NVMe)</label>
                            <input type="number" name="storage_gb" required placeholder="30" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-700 block mb-1">Bandwidth (GB)</label>
                            <input type="number" name="bandwidth_gb" required placeholder="300" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="button" @click="showNewPlanModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-semibold rounded-xl">Cancel</button>
                        <button type="submit" class="px-6 py-2 bg-[#0059bb] text-white font-bold text-xs rounded-xl shadow">Save Plan</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- Chart.js Script Initialization -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const canvasEl = document.getElementById('revenueChartCanvas');
            if (canvasEl) {
                const revCtx = canvasEl.getContext('2d');
                new Chart(revCtx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($revenueChart['labels']) !!},
                        datasets: [{
                            label: 'Monthly Revenue (KES)',
                            data: {!! json_encode($revenueChart['data']) !!},
                            borderColor: '#0059bb',
                            backgroundColor: 'rgba(0, 89, 187, 0.12)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { grid: { color: 'rgba(0,0,0,0.04)' }, ticks: { color: '#64748b' } },
                            y: { grid: { color: 'rgba(0,0,0,0.04)' }, ticks: { color: '#64748b' } }
                        }
                    }
                });
            }
        });
    </script>
</x-admin-layout>
