<x-app-layout>
    <x-slot name="title">Hosting Plans & Pricing — HostNinja Cloud</x-slot>

    <div class="py-16 bg-[#f7f9fb] min-h-screen">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
                <span class="text-xs font-bold text-[#0059bb] uppercase tracking-widest bg-[#0059bb]/10 px-4 py-1.5 rounded-full border border-[#0059bb]/20">Clustered NVMe Storage</span>
                <h1 class="text-4xl sm:text-5xl font-extrabold font-['Hanken_Grotesk'] text-slate-900 tracking-tight">Web Hosting Plans & Pricing</h1>
                <p class="text-slate-600 text-sm font-medium">All plans include cPanel control panel, free SSL certificates, instant setup, and 99.9% uptime SLA.</p>
            </div>

            <!-- Full Spec Comparison Matrix Table -->
            <div class="bg-white rounded-3xl p-6 md:p-8 border border-slate-200/80 shadow-xl overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[700px]">
                    <thead>
                        <tr class="border-b border-slate-200">
                            <th class="py-5 px-6 text-sm font-bold text-slate-900 font-['Hanken_Grotesk']">Feature Breakdown</th>
                            @foreach($hostingPlans as $plan)
                                <th class="py-5 px-6 text-center {{ $plan->is_featured ? 'bg-[#0059bb]/10 border-x border-[#0059bb]/30' : '' }}">
                                    <div class="text-lg font-bold font-['Hanken_Grotesk'] text-slate-900">{{ $plan->name }}</div>
                                    <div class="text-2xl font-extrabold text-[#0059bb] mt-1">KES {{ number_format($plan->price_monthly) }}<span class="text-xs font-normal text-slate-500">/mo</span></div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-4 px-6 font-bold text-slate-700">NVMe SSD Storage</td>
                            @foreach($hostingPlans as $plan)
                                <td class="py-4 px-6 text-center text-slate-900 font-bold {{ $plan->is_featured ? 'bg-[#0059bb]/5 border-x border-[#0059bb]/20' : '' }}">{{ $plan->storage_gb }} GB</td>
                            @endforeach
                        </tr>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-4 px-6 font-bold text-slate-700">Monthly Bandwidth</td>
                            @foreach($hostingPlans as $plan)
                                <td class="py-4 px-6 text-center text-slate-900 font-bold {{ $plan->is_featured ? 'bg-[#0059bb]/5 border-x border-[#0059bb]/20' : '' }}">{{ number_format($plan->bandwidth_gb) }} GB</td>
                            @endforeach
                        </tr>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-4 px-6 font-bold text-slate-700">Email Accounts</td>
                            @foreach($hostingPlans as $plan)
                                <td class="py-4 px-6 text-center text-slate-800 font-semibold {{ $plan->is_featured ? 'bg-[#0059bb]/5 border-x border-[#0059bb]/20' : '' }}">{{ $plan->email_accounts }}</td>
                            @endforeach
                        </tr>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-4 px-6 font-bold text-slate-700">Databases (MySQL)</td>
                            @foreach($hostingPlans as $plan)
                                <td class="py-4 px-6 text-center text-slate-800 font-semibold {{ $plan->is_featured ? 'bg-[#0059bb]/5 border-x border-[#0059bb]/20' : '' }}">{{ $plan->databases }}</td>
                            @endforeach
                        </tr>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-4 px-6 font-bold text-slate-700">Free SSL Certificate</td>
                            @foreach($hostingPlans as $plan)
                                <td class="py-4 px-6 text-center text-emerald-600 font-bold {{ $plan->is_featured ? 'bg-[#0059bb]/5 border-x border-[#0059bb]/20' : '' }}">✓ Included</td>
                            @endforeach
                        </tr>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-4 px-6 font-bold text-slate-700">cPanel Control Panel</td>
                            @foreach($hostingPlans as $plan)
                                <td class="py-4 px-6 text-center text-emerald-600 font-bold {{ $plan->is_featured ? 'bg-[#0059bb]/5 border-x border-[#0059bb]/20' : '' }}">✓ Included</td>
                            @endforeach
                        </tr>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-4 px-6 font-bold text-slate-700">1-Click WordPress Installer</td>
                            @foreach($hostingPlans as $plan)
                                <td class="py-4 px-6 text-center text-emerald-600 font-bold {{ $plan->is_featured ? 'bg-[#0059bb]/5 border-x border-[#0059bb]/20' : '' }}">✓ Included</td>
                            @endforeach
                        </tr>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-4 px-6 font-bold text-slate-700">Daily Backups</td>
                            @foreach($hostingPlans as $plan)
                                <td class="py-4 px-6 text-center text-emerald-600 font-bold {{ $plan->is_featured ? 'bg-[#0059bb]/5 border-x border-[#0059bb]/20' : '' }}">✓ Included</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td class="py-4 px-6"></td>
                            @foreach($hostingPlans as $plan)
                                <td class="py-6 px-6 text-center {{ $plan->is_featured ? 'bg-[#0059bb]/5 border-x border-[#0059bb]/20' : '' }}">
                                    <a href="{{ route('checkout.index', ['plan' => $plan->slug]) }}" class="py-3 px-5 rounded-xl text-xs font-bold block transition-all {{ $plan->is_featured ? 'bg-[#0059bb] hover:bg-blue-600 text-white shadow-lg shadow-blue-500/25' : 'bg-slate-900 hover:bg-[#0059bb] text-white' }}">
                                        Choose {{ $plan->name }}
                                    </a>
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
