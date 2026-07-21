<x-admin-layout>
    <x-slot name="title">HostNinja Admin | Packages</x-slot>

    <div class="space-y-8" x-data="{ showNewPlanModal: false }">
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 text-xs font-bold flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-['Hanken_Grotesk'] text-2xl font-extrabold text-slate-900">Hosting Packages & Pricing Tiers</h1>
                <p class="text-xs text-slate-500 mt-1">Manage product specifications, NVMe storage allocations, and pricing.</p>
            </div>
            <button @click="showNewPlanModal = true" class="px-5 py-2.5 bg-[#0059bb] hover:bg-blue-600 text-white font-bold text-xs rounded-xl shadow transition-all">+ Add New Package</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($hostingPlans as $hp)
                <div class="glass-card p-6 rounded-3xl border border-slate-200 flex flex-col justify-between space-y-4">
                    <div>
                        <div class="flex items-center justify-between">
                            <h3 class="font-['Hanken_Grotesk'] text-xl font-bold text-slate-900">{{ $hp->name }}</h3>
                            @if($hp->is_featured)
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#0059bb] text-white uppercase">POPULAR</span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-500 mt-1">{{ $hp->tagline }}</p>
                        <div class="text-2xl font-extrabold text-slate-900 mt-4">KES {{ number_format($hp->price_monthly) }} <span class="text-xs font-normal text-slate-400">/mo</span></div>
                    </div>

                    <div class="space-y-2 border-t border-slate-100 pt-4 text-xs text-slate-700">
                        <div><strong>Storage:</strong> {{ $hp->storage_gb }}GB NVMe SSD</div>
                        <div><strong>Bandwidth:</strong> {{ $hp->bandwidth_gb }}GB Monthly</div>
                        <div><strong>Email Accounts:</strong> {{ $hp->email_accounts }}</div>
                        <div><strong>Free SSL:</strong> {{ $hp->ssl_free ? 'Included' : 'No' }}</div>
                    </div>
                </div>
            @endforeach
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
</x-admin-layout>
