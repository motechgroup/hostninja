<x-app-layout>
    <x-slot name="title">HostNinja | Reseller Partner Portal</x-slot>

    <div class="py-8 bg-[#f7f9fb] min-h-screen" x-data="{ showNewClientModal: false }">
        <div class="max-w-7xl mx-auto px-6 space-y-8">
            
            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 text-xs font-bold flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Reseller Header Card -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 p-8 rounded-3xl text-white shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-[#00F5FF]/10 border border-[#00F5FF]/30 text-[#00F5FF] flex items-center justify-center font-bold text-2xl shadow-lg">
                        <span class="material-symbols-outlined text-3xl">handshake</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-2xl font-extrabold font-['Hanken_Grotesk'] text-white">Reseller Partner Console</h1>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#00F5FF]/20 text-[#00F5FF] uppercase">VIP Reseller</span>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">Manage white-label hosting accounts, set custom markups, and track commissions.</p>
                    </div>
                </div>

                <div class="flex items-center gap-6 w-full md:w-auto justify-between md:justify-end">
                    <div class="text-right">
                        <span class="text-[10px] font-['JetBrains_Mono'] text-slate-400 uppercase tracking-wider block">Total Commission Earned</span>
                        <span class="text-2xl font-extrabold text-[#00F5FF]">KES {{ number_format($totalEarned, 2) }}</span>
                    </div>
                    <button @click="showNewClientModal = true" class="px-5 py-2.5 bg-[#0059bb] hover:bg-blue-600 text-white font-bold text-xs rounded-xl transition-all shadow flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">person_add</span>
                        <span>Add Sub-Client</span>
                    </button>
                </div>
            </div>

            <!-- Stats Row -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="glass-card p-6 rounded-2xl border border-slate-200">
                    <span class="text-xs font-['JetBrains_Mono'] text-slate-400 uppercase tracking-wider block">Sub-Clients Managed</span>
                    <h3 class="text-2xl font-bold font-['Hanken_Grotesk'] text-slate-900 mt-1">{{ count($subClients) }} Accounts</h3>
                </div>
                <div class="glass-card p-6 rounded-2xl border border-slate-200">
                    <span class="text-xs font-['JetBrains_Mono'] text-slate-400 uppercase tracking-wider block">Reseller Markup Discount</span>
                    <h3 class="text-2xl font-bold font-['Hanken_Grotesk'] text-[#0059bb] mt-1">20% Wholesale</h3>
                </div>
                <div class="glass-card p-6 rounded-2xl border border-slate-200">
                    <span class="text-xs font-['JetBrains_Mono'] text-slate-400 uppercase tracking-wider block">Payout Wallet Balance</span>
                    <h3 class="text-2xl font-bold font-['Hanken_Grotesk'] text-emerald-600 mt-1">KES {{ number_format($reseller->balance, 2) }}</h3>
                </div>
            </div>

            <!-- Sub-Clients & Commissions Table -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Sub-Clients List -->
                <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-['Hanken_Grotesk'] text-lg font-bold text-slate-900">White-Label Sub-Clients</h3>
                        <span class="text-xs font-['JetBrains_Mono'] text-slate-400">Total: {{ count($subClients) }}</span>
                    </div>
                    <div class="space-y-3">
                        @foreach($subClients as $client)
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between text-xs">
                                <div>
                                    <div class="font-bold text-slate-900">{{ $client->name }}</div>
                                    <div class="text-slate-500">{{ $client->email }} • {{ $client->company }}</div>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold badge-active uppercase">Active</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Commission History -->
                <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
                    <h3 class="font-['Hanken_Grotesk'] text-lg font-bold text-slate-900 mb-4">Commission Earnings History</h3>
                    <div class="space-y-3">
                        @foreach($commissions as $comm)
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between text-xs">
                                <div>
                                    <div class="font-bold text-slate-900">{{ $comm->service_name }}</div>
                                    <div class="text-slate-500">Client: {{ $comm->client->name ?? 'Sub-Client' }}</div>
                                </div>
                                <div class="text-right">
                                    <span class="font-bold text-emerald-600 block">+KES {{ number_format($comm->commission_amount, 2) }}</span>
                                    <span class="text-[10px] text-slate-400 uppercase font-semibold">20% Margin</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL: ADD SUB-CLIENT -->
        <div x-show="showNewClientModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak>
            <div class="bg-white p-8 rounded-3xl border border-slate-200 max-w-md w-full shadow-2xl" @click.away="showNewClientModal = false">
                <h3 class="font-['Hanken_Grotesk'] text-xl font-bold text-slate-900 mb-4">Add Sub-Client Account</h3>
                <form method="POST" action="{{ route('reseller.client.add') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="text-xs font-semibold text-slate-700 block mb-1">Client Full Name</label>
                        <input type="text" name="name" required placeholder="Jane Doe" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-700 block mb-1">Client Email Address</label>
                        <input type="email" name="email" required placeholder="jane@clientcompany.com" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-700 block mb-1">Company / Organization</label>
                        <input type="text" name="company" placeholder="Acme Ltd" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900">
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="button" @click="showNewClientModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-semibold rounded-xl">Cancel</button>
                        <button type="submit" class="px-6 py-2 bg-[#0059bb] text-white font-bold text-xs rounded-xl shadow">Create Sub-Client</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
