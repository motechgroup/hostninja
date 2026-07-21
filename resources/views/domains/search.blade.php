<x-app-layout>
    <x-slot name="title">Domain Search & Registration — HostNinja Cloud</x-slot>

    <div class="py-16 bg-[#f7f9fb] min-h-screen">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-3xl mx-auto mb-10 space-y-3">
                <span class="text-xs font-bold text-[#0059bb] uppercase tracking-widest bg-[#0059bb]/10 px-4 py-1.5 rounded-full border border-[#0059bb]/20">Instant Domain Lookup</span>
                <h1 class="text-4xl sm:text-5xl font-extrabold font-['Hanken_Grotesk'] text-slate-900 tracking-tight">Search & Register Your Domain Name</h1>
                <p class="text-slate-600 text-sm font-medium">Check real-time availability across .co.ke, .com, .net, .org, .africa, and .io extensions.</p>
            </div>

            <div class="max-w-4xl mx-auto">
                @livewire('domain-search')
            </div>

            <!-- TLD Price Table Summary -->
            <div class="mt-20 max-w-4xl mx-auto space-y-6">
                <h3 class="text-2xl font-bold font-['Hanken_Grotesk'] text-slate-900 text-center">Top Domain Extension Pricing</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                    <div class="bg-white p-5 rounded-2xl text-center border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                        <div class="text-xl font-extrabold text-[#0059bb] font-['Hanken_Grotesk']">.co.ke</div>
                        <div class="text-sm font-extrabold text-slate-900 mt-1">KES 990<span class="text-[10px] text-slate-500 font-normal">/yr</span></div>
                        <div class="text-[10px] text-slate-500 font-semibold mt-1">Local Brand favorite</div>
                    </div>
                    <div class="bg-white p-5 rounded-2xl text-center border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                        <div class="text-xl font-extrabold text-[#0059bb] font-['Hanken_Grotesk']">.com</div>
                        <div class="text-sm font-extrabold text-slate-900 mt-1">KES 1,200<span class="text-[10px] text-slate-500 font-normal">/yr</span></div>
                        <div class="text-[10px] text-slate-500 font-semibold mt-1">Global Standard</div>
                    </div>
                    <div class="bg-white p-5 rounded-2xl text-center border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                        <div class="text-xl font-extrabold text-purple-600 font-['Hanken_Grotesk']">.net</div>
                        <div class="text-sm font-extrabold text-slate-900 mt-1">KES 1,450<span class="text-[10px] text-slate-500 font-normal">/yr</span></div>
                        <div class="text-[10px] text-slate-500 font-semibold mt-1">Networks & Tech</div>
                    </div>
                    <div class="bg-white p-5 rounded-2xl text-center border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                        <div class="text-xl font-extrabold text-emerald-600 font-['Hanken_Grotesk']">.org</div>
                        <div class="text-sm font-extrabold text-slate-900 mt-1">KES 1,500<span class="text-[10px] text-slate-500 font-normal">/yr</span></div>
                        <div class="text-[10px] text-slate-500 font-semibold mt-1">Organizations</div>
                    </div>
                    <div class="bg-white p-5 rounded-2xl text-center border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                        <div class="text-xl font-extrabold text-amber-600 font-['Hanken_Grotesk']">.africa</div>
                        <div class="text-sm font-extrabold text-slate-900 mt-1">KES 2,100<span class="text-[10px] text-slate-500 font-normal">/yr</span></div>
                        <div class="text-[10px] text-slate-500 font-semibold mt-1">Pan-African</div>
                    </div>
                    <div class="bg-white p-5 rounded-2xl text-center border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                        <div class="text-xl font-extrabold text-pink-600 font-['Hanken_Grotesk']">.io</div>
                        <div class="text-sm font-extrabold text-slate-900 mt-1">KES 4,500<span class="text-[10px] text-slate-500 font-normal">/yr</span></div>
                        <div class="text-[10px] text-slate-500 font-semibold mt-1">Startups & Devs</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
