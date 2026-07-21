<x-app-layout>
    <x-slot name="title">Order Confirmed #{{ $invoice->invoice_number }} — HostNinja Cloud</x-slot>

    <div class="py-16 bg-[#f7f9fb] min-h-screen">
        <div class="max-w-3xl mx-auto px-6 space-y-8 text-center">
            
            <!-- Success Icon Badge -->
            <div class="w-20 h-20 mx-auto rounded-full bg-emerald-500/10 text-emerald-600 flex items-center justify-center border-2 border-emerald-500/30 shadow-lg shadow-emerald-500/20">
                <span class="material-symbols-outlined text-4xl">task_alt</span>
            </div>

            <!-- Title & Subtitle -->
            <div class="space-y-2">
                <span class="px-3.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200 uppercase font-['JetBrains_Mono']">
                    Payment Successful & Provisioned
                </span>
                <h1 class="text-4xl font-extrabold font-['Hanken_Grotesk'] text-slate-900">Thank You for Your Order!</h1>
                <p class="text-slate-600 text-sm">Your web hosting service and domain name are now active on our high-speed server cluster.</p>
            </div>

            <!-- Receipt Summary Bento Box -->
            <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-xl text-left space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <span class="text-[10px] font-['JetBrains_Mono'] text-slate-400 uppercase tracking-wider block">Official Tax Invoice</span>
                        <span class="text-lg font-extrabold text-[#0059bb] font-mono">{{ $invoice->invoice_number }}</span>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] font-['JetBrains_Mono'] text-slate-400 uppercase tracking-wider block">Payment Reference</span>
                        <span class="text-sm font-bold text-slate-900 font-mono">{{ $payment->transaction_reference ?? 'QHK' . rand(10000,99999) }}</span>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-2">
                        <div class="flex justify-between text-xs">
                            <span class="text-slate-500">Service Description:</span>
                            <span class="font-bold text-slate-900">{{ $invoice->description }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-slate-500">Amount Paid:</span>
                            <span class="font-extrabold text-emerald-600">KES {{ number_format($invoice->total, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-slate-500">Paid At:</span>
                            <span class="font-semibold text-slate-700">{{ $invoice->paid_at->format('M d, Y H:i A') }}</span>
                        </div>
                    </div>

                    @if($hostingService)
                        <div class="p-5 rounded-2xl bg-blue-50 border border-blue-200 space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[#0059bb]">dns</span>
                                    <span class="font-bold text-sm text-slate-900">Active Hosting Account</span>
                                </div>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">ACTIVE</span>
                            </div>

                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <div><span class="text-slate-500">Domain:</span> <span class="font-bold text-slate-900">{{ $hostingService->domain_name }}</span></div>
                                <div><span class="text-slate-500">cPanel Username:</span> <span class="font-mono text-[#0059bb] font-bold">{{ $hostingService->username }}</span></div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row items-center gap-4 pt-2">
                    <a href="{{ route('dashboard') }}" class="w-full sm:flex-1 py-3.5 bg-[#0059bb] hover:bg-blue-600 text-white font-bold text-xs rounded-xl text-center shadow-lg transition-colors">
                        Go to Customer Dashboard &rarr;
                    </a>
                    <a href="{{ route('dashboard.services') }}" class="w-full sm:flex-1 py-3.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl text-center shadow transition-colors">
                        Manage Services & cPanel
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
