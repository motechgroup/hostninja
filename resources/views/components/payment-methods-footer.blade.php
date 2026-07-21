@php
    $showFooter = \App\Models\Setting::getByKey('show_footer_payment_methods', '1') === '1';
    $paymentMethods = collect();
    
    if ($showFooter && \Illuminate\Support\Facades\Schema::hasTable('payment_methods')) {
        try {
            $paymentMethods = \App\Models\PaymentMethod::getEnabledForFooter();
        } catch (\Throwable $e) {
            $paymentMethods = collect();
        }
    }
@endphp

@if($showFooter && $paymentMethods->count() > 0)
    <div class="max-w-7xl mx-auto px-6 pt-12 border-t border-slate-800/80 mt-12">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="text-center md:text-left space-y-1">
                <h5 class="font-bold text-xs uppercase tracking-widest text-white font-['JetBrains_Mono'] flex items-center justify-center md:justify-start gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span>Accepted Payment Methods</span>
                </h5>
                <p class="text-[11px] text-slate-400">
                    Secure payments powered by trusted global and local payment providers.
                </p>
            </div>

            <div class="flex flex-wrap items-center justify-center md:justify-end gap-3" role="region" aria-label="Supported Payment Methods">
                @foreach($paymentMethods as $method)
                    <div title="{{ $method->name }}" aria-label="{{ $method->name }} payment method" class="group relative flex items-center justify-center p-1 rounded-xl bg-slate-800/60 hover:bg-slate-800 border border-slate-700/60 hover:border-slate-500 shadow-sm transition-all duration-300 hover:scale-105 cursor-pointer">
                        <div class="opacity-90 group-hover:opacity-100 transition-opacity">
                            {!! $method->icon_svg !!}
                        </div>

                        <!-- Tooltip Popup -->
                        <span class="absolute -top-9 left-1/2 -translate-x-1/2 hidden group-hover:block bg-slate-950 text-white text-[10px] font-bold font-['Hanken_Grotesk'] px-2.5 py-1 rounded-lg shadow-xl border border-slate-700 whitespace-nowrap z-50 pointer-events-none transition-all">
                            {{ $method->name }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
