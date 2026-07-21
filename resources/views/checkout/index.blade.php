<x-app-layout>
    <x-slot name="title">Checkout & Configure Services — HostNinja Cloud</x-slot>

    <div class="py-12 bg-[#f7f9fb] min-h-screen">
        <div class="max-w-7xl mx-auto px-6 space-y-8">
            
            <!-- Header Step Banner -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-6">
                <div>
                    <span class="text-xs font-bold text-[#0059bb] uppercase tracking-widest bg-[#0059bb]/10 px-3 py-1 rounded-full border border-[#0059bb]/20">Secure Order Process</span>
                    <h1 class="text-3xl sm:text-4xl font-extrabold font-['Hanken_Grotesk'] text-slate-900 mt-2">Checkout & Activate Services</h1>
                </div>
                
                <div class="flex items-center gap-3 text-xs font-bold font-['JetBrains_Mono']">
                    <span class="px-3 py-1.5 rounded-xl bg-emerald-100 text-emerald-700 border border-emerald-200 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-sm">lock</span>
                        <span>256-Bit SSL Encrypted</span>
                    </span>
                    <span class="px-3 py-1.5 rounded-xl bg-blue-50 text-[#0059bb] border border-blue-200 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-sm">bolt</span>
                        <span>Instant Auto-Provisioning</span>
                    </span>
                </div>
            </div>

            <!-- Session Alerts -->
            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 text-xs font-bold flex items-center gap-2">
                    <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-700 text-xs font-bold flex items-center gap-2">
                    <span class="material-symbols-outlined text-rose-600">error</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <form id="checkout_form" method="POST" action="{{ route('checkout.process') }}" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                @csrf

                <!-- Left 8 Columns: Configuration & Details -->
                <div class="lg:col-span-8 space-y-8">
                    
                    <!-- 1. Hosting Package Selector Card -->
                    <div class="bg-white p-8 rounded-3xl border border-slate-200/90 shadow-sm space-y-6">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-[#0059bb]/10 text-[#0059bb] flex items-center justify-center font-bold">
                                    <span class="material-symbols-outlined">dns</span>
                                </div>
                                <div>
                                    <h3 class="font-['Hanken_Grotesk'] text-xl font-bold text-slate-900">1. Web Hosting Package</h3>
                                    <p class="text-xs text-slate-500">Choose your server specifications & billing cycle</p>
                                </div>
                            </div>
                        </div>

                        <!-- Hosting Plan Selection Radio Cards -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($hostingPlans as $hp)
                                <label class="p-5 rounded-2xl border-2 transition-all cursor-pointer flex flex-col justify-between space-y-3 {{ $selectedPlan->id === $hp->id ? 'border-[#0059bb] bg-blue-50/40 shadow-sm' : 'border-slate-200 bg-white hover:border-slate-300' }}">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <span class="font-['Hanken_Grotesk'] text-lg font-bold text-slate-900 block">{{ $hp->name }}</span>
                                            <span class="text-[11px] text-slate-500">{{ $hp->storage_gb }}GB NVMe Storage • {{ $hp->bandwidth_gb }}GB BW</span>
                                        </div>
                                        <input type="radio" name="plan_id_option" value="{{ $hp->id }}" {{ $selectedPlan->id === $hp->id ? 'checked' : '' }} onclick="document.getElementById('plan_id_form_{{ $hp->id }}').submit()" class="w-4 h-4 text-[#0059bb] focus:ring-0">
                                    </div>
                                    <div class="flex items-baseline justify-between pt-2 border-t border-slate-200/60 text-xs">
                                        <span class="font-extrabold text-[#0059bb] text-base">KES {{ number_format($hp->price_monthly) }}<span class="text-slate-500 text-[10px] font-normal">/mo</span></span>
                                        <span class="text-emerald-600 font-bold text-[10px]">✓ Free SSL & cPanel</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- 2. Domain Configuration Card -->
                    <div class="bg-white p-8 rounded-3xl border border-slate-200/90 shadow-sm space-y-6">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-purple-500/10 text-purple-600 flex items-center justify-center font-bold">
                                    <span class="material-symbols-outlined">language</span>
                                </div>
                                <div>
                                    <h3 class="font-['Hanken_Grotesk'] text-xl font-bold text-slate-900">2. Linked Domain Names</h3>
                                    <p class="text-xs text-slate-500">Domains added to your order for instant registration & WHOIS lock</p>
                                </div>
                            </div>
                            <a href="{{ route('domains.search') }}" class="text-xs font-bold text-[#0059bb] hover:underline">+ Search More Domains</a>
                        </div>

                        @if(!empty($cartDomains))
                            <div class="space-y-3">
                                @foreach($cartDomains as $domName => $domPrice)
                                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between gap-4">
                                        <div class="flex items-center gap-3">
                                            <span class="material-symbols-outlined text-[#0059bb]">public</span>
                                            <div>
                                                <span class="font-bold text-sm text-slate-900">{{ $domName }}</span>
                                                <span class="block text-[10px] text-emerald-600 font-bold">✓ Free WHOIS Privacy & Auto-renew included</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <span class="font-extrabold text-slate-900 text-sm">KES {{ number_format($domPrice, 2) }}</span>
                                            <button type="button" onclick="document.getElementById('remove_domain_form_{{ Str::slug($domName) }}').submit()" class="text-rose-500 hover:text-rose-700 text-xs font-bold">Remove</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-6 rounded-2xl bg-amber-50 border border-amber-200 text-center space-y-2">
                                <p class="text-xs text-amber-800 font-semibold">No domain currently attached to this order.</p>
                                <a href="{{ route('domains.search') }}" class="inline-block px-4 py-2 bg-[#0059bb] text-white font-bold text-xs rounded-xl shadow">+ Register a Domain (e.g. .co.ke, .com)</a>
                            </div>
                        @endif
                    </div>

                    <!-- 3. Customer Account Information Card -->
                    <div class="bg-white p-8 rounded-3xl border border-slate-200/90 shadow-sm space-y-6">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center font-bold">
                                    <span class="material-symbols-outlined">person</span>
                                </div>
                                <div>
                                    <h3 class="font-['Hanken_Grotesk'] text-xl font-bold text-slate-900">3. Account & Billing Details</h3>
                                    <p class="text-xs text-slate-500">Specify owner details for cPanel & registrar ownership</p>
                                </div>
                            </div>
                        </div>

                        @auth
                            <div class="p-5 rounded-2xl bg-blue-50/50 border border-blue-200/80 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-[#0059bb] text-white font-bold flex items-center justify-center text-sm">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-sm text-slate-900">Logged in as {{ auth()->user()->name }}</div>
                                        <div class="text-xs text-slate-500">{{ auth()->user()->email }} • {{ auth()->user()->phone ?? 'No phone' }}</div>
                                    </div>
                                </div>
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-[10px] font-bold uppercase border border-emerald-200">Account Verified</span>
                            </div>
                        @else
                            <div class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-xs font-semibold text-slate-700 block mb-1">Full Name</label>
                                        <input type="text" name="name" required placeholder="David Kamau" value="{{ old('name') }}" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#0059bb]">
                                    </div>
                                    <div>
                                        <label class="text-xs font-semibold text-slate-700 block mb-1">Email Address</label>
                                        <input type="email" name="email" required placeholder="david@company.co.ke" value="{{ old('email') }}" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#0059bb]">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-xs font-semibold text-slate-700 block mb-1">Mobile Phone (for M-Pesa)</label>
                                        <input type="text" name="phone" required placeholder="+254712345678" value="{{ old('phone', '+254') }}" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#0059bb]">
                                    </div>
                                    <div>
                                        <label class="text-xs font-semibold text-slate-700 block mb-1">Account Password</label>
                                        <input type="password" name="password" required placeholder="••••••••" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#0059bb]">
                                    </div>
                                </div>
                            </div>
                        @endauth
                    </div>

                    <!-- 4. Payment Gateway Option Card -->
                    <div class="bg-white p-8 rounded-3xl border border-slate-200/90 shadow-sm space-y-6">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center font-bold">
                                    <span class="material-symbols-outlined">payments</span>
                                </div>
                                <div>
                                    <h3 class="font-['Hanken_Grotesk'] text-xl font-bold text-slate-900">4. Select Payment Method</h3>
                                    <p class="text-xs text-slate-500">Instant confirmation via STK Push or Credit Card</p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4" x-data="{ selectedMethod: '{{ $paymentMethods->first()?->code ?? 'mpesa' }}' }">
                            @if(isset($paymentMethods) && $paymentMethods->count() > 0)
                                @foreach($paymentMethods as $index => $method)
                                    <label :class="selectedMethod === '{{ $method->code }}' ? 'border-[#0059bb] bg-blue-50/40 ring-1 ring-[#0059bb]' : 'border-slate-200 bg-white hover:border-slate-300'" class="p-4 rounded-2xl border-2 cursor-pointer flex flex-col justify-between space-y-3 transition-all">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2 overflow-hidden">
                                                <div class="shrink-0 p-1 bg-slate-900 rounded-lg flex items-center justify-center">
                                                    {!! $method->logo_html !!}
                                                </div>
                                                <span class="font-bold text-xs text-slate-900 truncate">{{ $method->name }}</span>
                                            </div>
                                            <input type="radio" name="payment_method" value="{{ $method->code }}" x-model="selectedMethod" {{ $index === 0 ? 'checked' : '' }} class="w-4 h-4 text-[#0059bb] focus:ring-0">
                                        </div>
                                        <span class="text-[10px] text-slate-500 capitalize font-mono">{{ $method->category }} gateway</span>
                                    </label>
                                @endforeach
                            @else
                                <label class="p-4 rounded-2xl border-2 border-emerald-500 bg-emerald-50/40 cursor-pointer flex flex-col justify-between space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-xs text-slate-900">M-Pesa Express</span>
                                        <input type="radio" name="payment_method" value="mpesa" checked class="w-4 h-4 text-emerald-600 focus:ring-0">
                                    </div>
                                    <span class="text-[10px] text-slate-500">STK Push to Safaricom Phone</span>
                                </label>

                                <label class="p-4 rounded-2xl border-2 border-slate-200 bg-white cursor-pointer flex flex-col justify-between space-y-2 hover:border-slate-300">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-xs text-slate-900">Credit / Debit Card</span>
                                        <input type="radio" name="payment_method" value="stripe" class="w-4 h-4 text-[#0059bb] focus:ring-0">
                                    </div>
                                    <span class="text-[10px] text-slate-500">Visa, Mastercard, Amex</span>
                                </label>
                            @endif

                            <!-- Account Wallet Option -->
                            <label :class="selectedMethod === 'wallet' ? 'border-[#0059bb] bg-blue-50/40 ring-1 ring-[#0059bb]' : 'border-slate-200 bg-white hover:border-slate-300'" class="p-4 rounded-2xl border-2 cursor-pointer flex flex-col justify-between space-y-3 transition-all">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="p-1.5 bg-indigo-600 text-white rounded-lg flex items-center justify-center">
                                            <span class="material-symbols-outlined text-sm">account_balance_wallet</span>
                                        </div>
                                        <span class="font-bold text-xs text-slate-900">Account Wallet</span>
                                    </div>
                                    <input type="radio" name="payment_method" value="wallet" x-model="selectedMethod" class="w-4 h-4 text-indigo-600 focus:ring-0">
                                </div>
                                <span class="text-[10px] text-slate-500">Available: KES {{ number_format(auth()->user()->balance ?? 0, 2) }}</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Right 4 Columns: Order Summary Bento Box -->
                <div class="lg:col-span-4 space-y-6 sticky top-8">
                    <div class="bg-[#0d1c32] text-white p-8 rounded-3xl shadow-xl space-y-6 relative overflow-hidden">
                        
                        <h3 class="font-['Hanken_Grotesk'] text-2xl font-bold border-b border-slate-700/60 pb-4">Order Summary</h3>

                        <!-- Itemized Breakdown -->
                        <div class="space-y-3 text-xs">
                            <div class="flex justify-between items-center text-slate-300">
                                <span>{{ $selectedPlan->name }} (Monthly)</span>
                                <span class="font-bold text-white font-['JetBrains_Mono']">KES {{ number_format($selectedPlan->price_monthly, 2) }}</span>
                            </div>

                            @foreach($cartDomains as $dName => $dPrice)
                                <div class="flex justify-between items-center text-slate-300">
                                    <span>Domain: {{ $dName }}</span>
                                    <span class="font-bold text-white font-['JetBrains_Mono']">KES {{ number_format($dPrice, 2) }}</span>
                                </div>
                            @endforeach

                            <div class="flex justify-between items-center text-emerald-400">
                                <span>Free SSL & cPanel License</span>
                                <span class="font-bold font-['JetBrains_Mono']">KES 0.00</span>
                            </div>

                            @if($discountAmount > 0)
                                <div class="flex justify-between items-center text-amber-400">
                                    <span>Discount Promo ({{ $couponCode }})</span>
                                    <span class="font-bold font-['JetBrains_Mono']">- KES {{ number_format($discountAmount, 2) }}</span>
                                </div>
                            @endif

                            <div class="border-t border-slate-700/60 pt-3 flex justify-between text-slate-400">
                                <span>Subtotal</span>
                                <span class="font-bold font-['JetBrains_Mono']">KES {{ number_format($subtotal - $discountAmount, 2) }}</span>
                            </div>

                            <div class="flex justify-between text-slate-400">
                                <span>VAT Tax (16%)</span>
                                <span class="font-bold font-['JetBrains_Mono']">KES {{ number_format($tax, 2) }}</span>
                            </div>

                            <div class="border-t border-slate-700/80 pt-4 flex justify-between items-baseline">
                                <span class="text-sm font-bold">Total Due Today:</span>
                                <span class="text-2xl font-extrabold text-[#00F5FF] font-['Hanken_Grotesk']">KES {{ number_format($total, 2) }}</span>
                            </div>
                        </div>

                        <!-- Action Submit Button -->
                        <button type="submit" class="w-full py-4 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-extrabold text-sm rounded-2xl shadow-lg transition-all flex items-center justify-center gap-2 cursor-pointer">
                            <span>Complete Order & Activate Services 🚀</span>
                        </button>

                        <p class="text-[10px] text-slate-400 text-center">By clicking Complete Order, you agree to HostNinja Cloud Terms of Service & Privacy Policy.</p>
                    </div>

                    <!-- Promo Coupon Code Box -->
                    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-3">
                        <span class="text-xs font-bold text-slate-800 block">Have a Promo Coupon Code?</span>
                        <div class="flex gap-2">
                            <input type="text" form="coupon_form" name="coupon_code" placeholder="e.g. SAVE20" value="{{ $couponCode }}" class="flex-1 px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs uppercase font-mono text-slate-900">
                            <button type="submit" form="coupon_form" class="px-4 py-2 bg-slate-900 text-white font-bold text-xs rounded-xl hover:bg-[#0059bb] transition-colors">Apply</button>
                        </div>
                        <span class="text-[10px] text-slate-400 block">Try <code class="text-[#0059bb] font-bold">SAVE20</code> for 20% instant discount!</span>
                    </div>

                </div>
            </form>

            <!-- Auxiliary Forms OUTSIDE main form -->
            @foreach($hostingPlans as $hp)
                <form id="plan_id_form_{{ $hp->id }}" method="POST" action="{{ route('checkout.plan') }}" class="hidden">
                    @csrf
                    <input type="hidden" name="plan_id" value="{{ $hp->id }}">
                </form>
            @endforeach

            @foreach($cartDomains as $domName => $domPrice)
                <form id="remove_domain_form_{{ Str::slug($domName) }}" method="POST" action="{{ route('checkout.domain.remove') }}" class="hidden">
                    @csrf
                    <input type="hidden" name="domain" value="{{ $domName }}">
                </form>
            @endforeach

            <form id="coupon_form" method="POST" action="{{ route('checkout.coupon') }}" class="hidden">
                @csrf
            </form>

        </div>
    </div>
</x-app-layout>
