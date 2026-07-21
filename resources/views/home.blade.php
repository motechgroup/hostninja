<x-app-layout>
    <x-slot name="title">HostNinja | Lightning Fast Cloud Hosting</x-slot>

    <!-- Centered Hero Section from Stitch -->
    <section class="relative overflow-hidden hero-gradient pt-16 pb-24 border-b border-slate-200/60">
        <div class="max-w-5xl mx-auto px-6 text-center space-y-8 relative z-10">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#0059bb]/10 border border-[#0059bb]/20 text-[#0059bb] text-xs font-bold uppercase tracking-widest mx-auto">
                <span class="w-2 h-2 rounded-full bg-[#0059bb] animate-pulse"></span>
                <span>Next-Generation NVMe Cloud Infrastructure</span>
            </div>

            <h1 class="font-['Hanken_Grotesk'] text-4xl sm:text-6xl font-extrabold text-slate-900 leading-tight max-w-3xl mx-auto">
                Launch Your Website With <span class="text-[#0059bb]">Lightning Fast</span> Hosting
            </h1>

            <p class="font-['Inter'] text-base sm:text-lg text-slate-600 max-w-2xl mx-auto leading-relaxed">
                Register domain names, deploy websites, and manage your online business from one powerful platform. Experience enterprise-grade reliability with futuristic innovation.
            </p>

            <!-- Centered Domain Search Livewire Component -->
            <div class="pt-4 max-w-3xl mx-auto">
                @livewire('domain-search')
            </div>
        </div>

        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-blue-500/10 rounded-full blur-3xl -z-10 pointer-events-none"></div>
    </section>

    <!-- Trust Features (Stitch Bento Grid Style) -->
    <section class="py-20 px-6 max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="glass-card p-8 rounded-3xl group hover:-translate-y-2 transition-all duration-300 border border-slate-200/80">
                <div class="w-12 h-12 bg-blue-500/10 rounded-2xl flex items-center justify-center mb-6 text-[#0059bb] group-hover:bg-[#0059bb] group-hover:text-white transition-colors">
                    <span class="material-symbols-outlined text-2xl">bolt</span>
                </div>
                <h3 class="font-['Hanken_Grotesk'] text-lg font-bold text-slate-900 mb-2">99.9% Uptime</h3>
                <p class="text-slate-600 text-sm leading-relaxed">Enterprise SLA ensuring your business stays online 24/7/365.</p>
            </div>

            <div class="glass-card p-8 rounded-3xl group hover:-translate-y-2 transition-all duration-300 border border-slate-200/80">
                <div class="w-12 h-12 bg-emerald-500/10 rounded-2xl flex items-center justify-center mb-6 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                    <span class="material-symbols-outlined text-2xl">lock</span>
                </div>
                <h3 class="font-['Hanken_Grotesk'] text-lg font-bold text-slate-900 mb-2">Free SSL Certificates</h3>
                <p class="text-slate-600 text-sm leading-relaxed">Automated Let's Encrypt certificates for every single domain hosted.</p>
            </div>

            <div class="glass-card p-8 rounded-3xl group hover:-translate-y-2 transition-all duration-300 border border-slate-200/80">
                <div class="w-12 h-12 bg-cyan-500/10 rounded-2xl flex items-center justify-center mb-6 text-cyan-600 group-hover:bg-cyan-600 group-hover:text-white transition-colors">
                    <span class="material-symbols-outlined text-2xl">support_agent</span>
                </div>
                <h3 class="font-['Hanken_Grotesk'] text-lg font-bold text-slate-900 mb-2">24/7 Expert Support</h3>
                <p class="text-slate-600 text-sm leading-relaxed">Human technical experts available instantly via chat or ticket.</p>
            </div>

            <div class="glass-card p-8 rounded-3xl group hover:-translate-y-2 transition-all duration-300 border border-slate-200/80">
                <div class="w-12 h-12 bg-slate-900/5 rounded-2xl flex items-center justify-center mb-6 text-slate-900 group-hover:bg-slate-900 group-hover:text-white transition-colors">
                    <span class="material-symbols-outlined text-2xl">dns</span>
                </div>
                <h3 class="font-['Hanken_Grotesk'] text-lg font-bold text-slate-900 mb-2">Fast NVMe Servers</h3>
                <p class="text-slate-600 text-sm leading-relaxed">NVMe SSD storage providing 10x faster website loading speeds.</p>
            </div>
        </div>
    </section>

    <!-- Global Domain Marketplace from Stitch -->
    <section class="py-24 bg-slate-100/60 border-y border-slate-200/80">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
                <div>
                    <span class="font-['JetBrains_Mono'] text-xs text-[#0059bb] uppercase tracking-widest mb-2 block font-semibold">Global Reach</span>
                    <h2 class="font-['Hanken_Grotesk'] text-3xl md:text-4xl font-extrabold text-slate-900">Global Domain Marketplace</h2>
                </div>
                <a href="{{ route('domains.search') }}" class="text-[#0059bb] font-bold text-sm flex items-center gap-2 hover:gap-3 transition-all">
                    Explore all extensions <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Extension Card .com -->
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200/80 hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <span class="font-['Hanken_Grotesk'] text-6xl font-extrabold text-slate-100 block mb-2">.com</span>
                        <h4 class="font-['Hanken_Grotesk'] text-xl font-bold text-slate-900 mb-1">.com</h4>
                        <p class="text-slate-500 text-xs mb-6">The world's most recognized TLD.</p>
                    </div>
                    <div>
                        <div class="flex items-baseline gap-1 mb-6">
                            <span class="text-2xl font-bold text-slate-900">KES 1,200</span>
                            <span class="text-slate-500 text-xs">/yr</span>
                        </div>
                        <a href="{{ route('domains.search') }}" class="w-full py-2.5 rounded-xl border border-[#0059bb] text-[#0059bb] font-bold text-xs hover:bg-[#0059bb] hover:text-white transition-colors block text-center">Register</a>
                    </div>
                </div>

                <!-- Extension Card .io -->
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200/80 hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <span class="font-['Hanken_Grotesk'] text-6xl font-extrabold text-slate-100 block mb-2">.io</span>
                        <h4 class="font-['Hanken_Grotesk'] text-xl font-bold text-slate-900 mb-1">.io</h4>
                        <p class="text-slate-500 text-xs mb-6">The destination for tech startups.</p>
                    </div>
                    <div>
                        <div class="flex items-baseline gap-1 mb-6">
                            <span class="text-2xl font-bold text-slate-900">KES 4,500</span>
                            <span class="text-slate-500 text-xs">/yr</span>
                        </div>
                        <a href="{{ route('domains.search') }}" class="w-full py-2.5 rounded-xl border border-[#0059bb] text-[#0059bb] font-bold text-xs hover:bg-[#0059bb] hover:text-white transition-colors block text-center">Register</a>
                    </div>
                </div>

                <!-- Extension Card .africa -->
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200/80 hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <span class="font-['Hanken_Grotesk'] text-6xl font-extrabold text-slate-100 block mb-2">.africa</span>
                        <h4 class="font-['Hanken_Grotesk'] text-xl font-bold text-slate-900 mb-1">.africa</h4>
                        <p class="text-slate-500 text-xs mb-6">Empowering the digital continent.</p>
                    </div>
                    <div>
                        <div class="flex items-baseline gap-1 mb-6">
                            <span class="text-2xl font-bold text-slate-900">KES 2,100</span>
                            <span class="text-slate-500 text-xs">/yr</span>
                        </div>
                        <a href="{{ route('domains.search') }}" class="w-full py-2.5 rounded-xl border border-[#0059bb] text-[#0059bb] font-bold text-xs hover:bg-[#0059bb] hover:text-white transition-colors block text-center">Register</a>
                    </div>
                </div>

                <!-- Extension Card .co.ke -->
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200/80 hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <span class="font-['Hanken_Grotesk'] text-6xl font-extrabold text-slate-100 block mb-2">.co.ke</span>
                        <h4 class="font-['Hanken_Grotesk'] text-xl font-bold text-slate-900 mb-1">.co.ke</h4>
                        <p class="text-slate-500 text-xs mb-6">Regional focus for Kenyan businesses.</p>
                    </div>
                    <div>
                        <div class="flex items-baseline gap-1 mb-6">
                            <span class="text-2xl font-bold text-slate-900">KES 990</span>
                            <span class="text-slate-500 text-xs">/yr</span>
                        </div>
                        <a href="{{ route('domains.search') }}" class="w-full py-2.5 rounded-xl border border-[#0059bb] text-[#0059bb] font-bold text-xs hover:bg-[#0059bb] hover:text-white transition-colors block text-center">Register</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section from Stitch -->
    <section class="py-24 px-6 max-w-7xl mx-auto">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="font-['Hanken_Grotesk'] text-3xl sm:text-5xl font-extrabold text-slate-900 mb-4">Simple, Scalable Pricing</h2>
            <p class="text-slate-600 text-base">No hidden fees. Choose the plan that scales with your growth from startup to enterprise.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
            @foreach($hostingPlans as $plan)
                <div class="relative bg-white p-8 rounded-3xl border {{ $plan->is_featured ? 'border-2 border-[#0059bb] shadow-2xl scale-105 z-10' : 'border-slate-200/80 hover:border-[#0059bb]' }} transition-all duration-300 flex flex-col justify-between">
                    @if($plan->is_featured)
                        <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-[#0059bb] text-white px-4 py-1 rounded-full text-xs font-bold uppercase tracking-widest shadow">
                            Most Popular
                        </div>
                    @endif

                    <div>
                        <h3 class="font-['Hanken_Grotesk'] text-2xl font-bold text-slate-900 mb-1">{{ $plan->name }}</h3>
                        <p class="text-slate-500 text-xs mb-6 min-h-[36px]">{{ $plan->tagline }}</p>

                        <div class="flex items-baseline gap-2 mb-8">
                            <span class="text-4xl font-extrabold text-slate-900">KES {{ number_format($plan->price_monthly) }}</span>
                            <span class="text-slate-500 text-xs">/month</span>
                        </div>

                        <ul class="space-y-3 mb-8 text-xs text-slate-700 border-t border-slate-100 pt-6">
                            <li class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-emerald-600 text-base">check_circle</span>
                                <span><strong>{{ $plan->storage_gb }}GB</strong> NVMe SSD Storage</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-emerald-600 text-base">check_circle</span>
                                <span><strong>{{ $plan->bandwidth_gb }}GB</strong> Monthly Bandwidth</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-emerald-600 text-base">check_circle</span>
                                <span><strong>{{ $plan->email_accounts }}</strong> Business Email Accounts</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-emerald-600 text-base">check_circle</span>
                                <span><strong>{{ $plan->databases }}</strong> Databases & Free SSL</span>
                            </li>
                        </ul>
                    </div>

                    <a href="{{ route('dashboard') }}" class="w-full py-3 rounded-xl font-bold text-xs text-center transition-all block {{ $plan->is_featured ? 'bg-[#0059bb] text-white shadow-lg shadow-blue-500/20' : 'border border-slate-900 text-slate-900 hover:bg-slate-900 hover:text-white' }}">
                        Get Started
                    </a>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Stitch CTA Section -->
    <section class="py-16 px-6 max-w-7xl mx-auto mb-20">
        <div class="bg-black text-white rounded-3xl p-12 relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="relative z-10 max-w-xl">
                <h2 class="font-['Hanken_Grotesk'] text-3xl md:text-4xl font-bold mb-4">Ready to scale your digital presence?</h2>
                <p class="text-slate-400 text-sm">Join 50,000+ businesses running on HostNinja Cloud infrastructure today.</p>
            </div>
            <div class="relative z-10 flex gap-4">
                <a href="{{ route('register') }}" class="bg-white text-slate-900 px-8 py-3.5 rounded-xl font-bold text-xs hover:bg-[#00F5FF] transition-all">Start Free Trial</a>
                <a href="{{ route('hosting.index') }}" class="bg-transparent border border-white/20 text-white px-8 py-3.5 rounded-xl font-bold text-xs hover:bg-white/10 transition-all">View Packages</a>
            </div>
            <div class="absolute right-0 bottom-0 w-96 h-96 bg-[#0059bb]/20 blur-[100px] -z-0"></div>
        </div>
    </section>
</x-app-layout>
