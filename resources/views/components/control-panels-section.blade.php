@php
    $controlPanels = collect();
    if (\Illuminate\Support\Facades\Schema::hasTable('hosting_control_panels')) {
        try {
            $controlPanels = \App\Models\HostingControlPanel::getEnabledOrdered();
        } catch (\Throwable $e) {
            $controlPanels = collect();
        }
    }
@endphp

@if($controlPanels->count() > 0)
    <section class="py-20 relative overflow-hidden bg-slate-950 text-white border-t border-slate-800/80">
        <!-- Subtle Radial Background Accents -->
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-emerald-600/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 space-y-12 relative z-10">
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto space-y-4">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-500/10 border border-blue-500/20 text-[#00F5FF] text-xs font-bold font-['JetBrains_Mono'] uppercase tracking-wider">
                    <span class="w-2 h-2 rounded-full bg-[#00F5FF] animate-pulse"></span>
                    <span>Universal Compatibility</span>
                </div>

                <h2 class="font-['Hanken_Grotesk'] text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight text-white">
                    Supported Hosting Control Panels
                </h2>

                <p class="text-slate-400 text-sm md:text-base leading-relaxed">
                    Connect your preferred hosting control panel and automate account provisioning, management, billing, and domain services from one powerful platform.
                </p>
            </div>

            <!-- Grid Layout (2 cols mobile, 3 cols tablet, 5 cols desktop) -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-5">
                @foreach($controlPanels as $panel)
                    <div class="group relative bg-slate-900/70 hover:bg-slate-900 border border-slate-800 hover:border-[#0059bb]/60 rounded-2xl p-5 shadow-xl hover:shadow-2xl hover:shadow-blue-500/10 transition-all duration-300 hover:-translate-y-1 hover:scale-[1.02] flex flex-col justify-between space-y-4">
                        
                        <!-- Top Bar: Logo & Featured Badge -->
                        <div class="space-y-3">
                            <div class="flex items-start justify-between gap-2">
                                <div class="p-2 rounded-xl bg-slate-950 border border-slate-800/80 group-hover:border-slate-700 transition-colors flex items-center justify-center">
                                    {!! $panel->logo_html !!}
                                </div>

                                @if($panel->featured)
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold font-['JetBrains_Mono'] bg-gradient-to-r from-amber-500/20 to-orange-500/20 border border-amber-500/40 text-amber-300 uppercase tracking-wider shrink-0">
                                        FEATURED
                                    </span>
                                @endif
                            </div>

                            <!-- Panel Title & Description -->
                            <div>
                                <h3 class="font-['Hanken_Grotesk'] text-base font-bold text-white group-hover:text-[#00F5FF] transition-colors">
                                    {{ $panel->name }}
                                </h3>
                                @if($panel->description)
                                    <p class="text-[11px] text-slate-400 leading-snug mt-1 line-clamp-2">
                                        {{ $panel->description }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Card Footer: Status & Learn More Link -->
                        <div class="pt-3 border-t border-slate-800/80 flex items-center justify-between gap-2">
                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 text-[10px] font-bold">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                <span>Supported</span>
                            </span>

                            @if($panel->official_url)
                                <a href="{{ $panel->official_url }}" target="_blank" rel="noopener noreferrer" class="text-[10px] font-bold text-slate-400 hover:text-white flex items-center gap-0.5 transition-colors" title="Visit {{ $panel->name }} official website">
                                    <span>Learn More</span>
                                    <span class="material-symbols-outlined text-xs">north_east</span>
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
