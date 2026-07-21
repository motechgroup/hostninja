<x-app-layout>
    <x-slot name="title">Shopping Cart — HostNinja Cloud</x-slot>

    <div class="py-12 bg-[#f7f9fb] min-h-screen">
        <div class="max-w-7xl mx-auto px-6 space-y-8">
            
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-6">
                <div>
                    <span class="text-xs font-bold text-[#0059bb] uppercase tracking-widest bg-[#0059bb]/10 px-3 py-1 rounded-full border border-[#0059bb]/20">Order Summary</span>
                    <h1 class="text-3xl sm:text-4xl font-extrabold font-['Hanken_Grotesk'] text-slate-900 mt-2">Your Shopping Cart</h1>
                </div>
                @if($itemCount > 0)
                    <form method="POST" action="{{ route('cart.clear') }}">
                        @csrf
                        <button type="submit" onclick="return confirm('Clear all items from your shopping cart?')" class="px-4 py-2 bg-rose-100 hover:bg-rose-200 text-rose-700 font-bold text-xs rounded-xl transition-colors">
                            Clear Cart
                        </button>
                    </form>
                @endif
            </div>

            <!-- Session Alerts -->
            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 text-xs font-bold flex items-center gap-2">
                    <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($itemCount > 0)
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    <!-- Left 8 Columns: Cart Items List -->
                    <div class="lg:col-span-8 space-y-6">
                        
                        <!-- Hosting Plan Item -->
                        @if($selectedPlan)
                            <div class="bg-white p-6 rounded-3xl border border-slate-200/90 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-[#0059bb]/10 text-[#0059bb] flex items-center justify-center font-bold">
                                        <span class="material-symbols-outlined text-2xl">dns</span>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h3 class="font-['Hanken_Grotesk'] text-lg font-bold text-slate-900">{{ $selectedPlan->name }}</h3>
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-[#0059bb] border border-blue-200 uppercase">{{ $billingCycle }}</span>
                                        </div>
                                        <p class="text-xs text-slate-500 mt-1">{{ $selectedPlan->storage_gb }}GB NVMe SSD • {{ $selectedPlan->bandwidth_gb }}GB Bandwidth • Free SSL</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-4 w-full sm:w-auto justify-between sm:justify-end border-t sm:border-t-0 pt-3 sm:pt-0 border-slate-100">
                                    <span class="font-extrabold text-[#0059bb] text-lg font-['Hanken_Grotesk']">
                                        KES {{ number_format(($billingCycle === 'yearly') ? $selectedPlan->price_yearly : $selectedPlan->price_monthly, 2) }}
                                    </span>
                                    <form method="POST" action="{{ route('cart.plan.remove') }}">
                                        @csrf
                                        <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 rounded-xl transition-colors">
                                            <span class="material-symbols-outlined text-sm">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif

                        <!-- Domain Items -->
                        @foreach($cartDomains as $domName => $domPrice)
                            <div class="bg-white p-6 rounded-3xl border border-slate-200/90 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-purple-500/10 text-purple-600 flex items-center justify-center font-bold">
                                        <span class="material-symbols-outlined text-2xl">public</span>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h3 class="font-['Hanken_Grotesk'] text-lg font-bold text-slate-900">{{ $domName }}</h3>
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">1 YEAR REGISTRATION</span>
                                        </div>
                                        <p class="text-xs text-emerald-600 font-semibold mt-1">✓ Free WHOIS Privacy Protection & DNS Management</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-4 w-full sm:w-auto justify-between sm:justify-end border-t sm:border-t-0 pt-3 sm:pt-0 border-slate-100">
                                    <span class="font-extrabold text-slate-900 text-lg font-['Hanken_Grotesk']">
                                        KES {{ number_format($domPrice, 2) }}
                                    </span>
                                    <form method="POST" action="{{ route('cart.domain.remove') }}">
                                        @csrf
                                        <input type="hidden" name="domain" value="{{ $domName }}">
                                        <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 rounded-xl transition-colors">
                                            <span class="material-symbols-outlined text-sm">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach

                        <!-- Add More Items Prompt -->
                        <div class="flex flex-wrap items-center justify-between gap-4 p-6 rounded-3xl bg-slate-100 border border-slate-200">
                            <span class="text-xs text-slate-600 font-semibold">Want to add more web hosting packages or domain names?</span>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('hosting.index') }}" class="px-4 py-2 bg-white hover:bg-slate-50 border border-slate-300 text-slate-900 font-bold text-xs rounded-xl shadow-sm">+ Hosting Plans</a>
                                <a href="{{ route('domains.search') }}" class="px-4 py-2 bg-white hover:bg-slate-50 border border-slate-300 text-slate-900 font-bold text-xs rounded-xl shadow-sm">+ Search Domains</a>
                            </div>
                        </div>

                    </div>

                    <!-- Right 4 Columns: Summary Box -->
                    <div class="lg:col-span-4 space-y-6">
                        <div class="bg-[#0d1c32] text-white p-8 rounded-3xl shadow-xl space-y-6">
                            <h3 class="font-['Hanken_Grotesk'] text-2xl font-bold border-b border-slate-700/60 pb-4">Cart Breakdown</h3>

                            <div class="space-y-3 text-xs">
                                <div class="flex justify-between text-slate-300">
                                    <span>Subtotal</span>
                                    <span class="font-bold font-['JetBrains_Mono']">KES {{ number_format($subtotal, 2) }}</span>
                                </div>

                                @if($discountAmount > 0)
                                    <div class="flex justify-between text-amber-400">
                                        <span>Promo Discount ({{ $couponCode }})</span>
                                        <span class="font-bold font-['JetBrains_Mono']">- KES {{ number_format($discountAmount, 2) }}</span>
                                    </div>
                                @endif

                                <div class="flex justify-between text-slate-400">
                                    <span>VAT Tax (16%)</span>
                                    <span class="font-bold font-['JetBrains_Mono']">KES {{ number_format($tax, 2) }}</span>
                                </div>

                                <div class="border-t border-slate-700/80 pt-4 flex justify-between items-baseline">
                                    <span class="text-sm font-bold">Estimated Total:</span>
                                    <span class="text-2xl font-extrabold text-[#00F5FF] font-['Hanken_Grotesk']">KES {{ number_format($total, 2) }}</span>
                                </div>
                            </div>

                            <a href="{{ route('checkout.index') }}" class="w-full py-4 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-extrabold text-sm rounded-2xl shadow-lg transition-all text-center block">
                                Proceed to Checkout 🚀
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white p-12 rounded-3xl border border-slate-200 text-center space-y-4 max-w-xl mx-auto shadow-sm">
                    <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-2xl mx-auto flex items-center justify-center">
                        <span class="material-symbols-outlined text-3xl">shopping_cart</span>
                    </div>
                    <h3 class="font-['Hanken_Grotesk'] text-2xl font-bold text-slate-900">Your shopping cart is currently empty</h3>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto">Explore our high-speed NVMe web hosting packages or search for your brand domain name to get started.</p>
                    <div class="flex justify-center gap-3 pt-2">
                        <a href="{{ route('hosting.index') }}" class="px-5 py-2.5 bg-[#0059bb] text-white font-bold text-xs rounded-xl shadow">Explore Hosting Plans</a>
                        <a href="{{ route('domains.search') }}" class="px-5 py-2.5 bg-slate-900 text-white font-bold text-xs rounded-xl shadow">Search Domains</a>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
