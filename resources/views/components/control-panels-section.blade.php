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
    <section class="py-20 relative overflow-hidden bg-slate-950 text-white border-t border-slate-800/80" x-data="{ showModal: false, activePanel: null }">
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
                    Connect your preferred hosting control panel and automate account provisioning, management, billing, and domain services from one powerful platform. Click any logo to view details.
                </p>
            </div>

            <!-- Clean Logo Grid (2 cols mobile, 3 cols tablet, 5 cols desktop) -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-5">
                @foreach($controlPanels as $panel)
                    <button type="button" 
                            data-panel="{{ json_encode([
                                'id' => $panel->id,
                                'name' => $panel->name,
                                'description' => $panel->description,
                                'official_url' => $panel->official_url,
                                'featured' => $panel->featured,
                                'logo_html' => $panel->logo_html,
                            ]) }}"
                            @click="activePanel = JSON.parse($el.dataset.panel); showModal = true"
                            class="group relative bg-slate-900/60 hover:bg-slate-900 border border-slate-800/90 hover:border-[#0059bb]/70 rounded-2xl p-6 shadow-xl hover:shadow-2xl hover:shadow-blue-500/20 transition-all duration-300 hover:-translate-y-1.5 hover:scale-[1.03] flex flex-col items-center justify-center space-y-3 cursor-pointer text-center min-h-[120px]">
                        
                        @if($panel->featured)
                            <span class="absolute top-2.5 right-2.5 px-2 py-0.5 rounded-full text-[8px] font-extrabold font-['JetBrains_Mono'] bg-gradient-to-r from-amber-500/20 to-orange-500/20 border border-amber-500/40 text-amber-300 uppercase tracking-wider">
                                FEATURED
                            </span>
                        @endif

                        <!-- Clean Uniform Logo Display -->
                        <div class="h-12 flex items-center justify-center filter group-hover:brightness-110 transition-all duration-300">
                            {!! $panel->logo_html !!}
                        </div>

                        <!-- Minimal Title & Click Action Indicator -->
                        <div class="flex items-center gap-1 text-slate-400 group-hover:text-[#00F5FF] text-xs font-bold transition-colors">
                            <span>{{ $panel->name }}</span>
                            <span class="material-symbols-outlined text-sm opacity-0 group-hover:opacity-100 transition-opacity">info</span>
                        </div>
                    </button>
                @endforeach
            </div>
        </div>

        <!-- CONTROL PANEL DETAILS MODAL -->
        <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md" x-cloak>
            <div class="bg-slate-900 border border-slate-700/80 rounded-3xl p-6 md:p-8 max-w-lg w-full shadow-2xl space-y-6 relative text-white" @click.away="showModal = false">
                <!-- Close Button -->
                <button type="button" @click="showModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-white p-2 rounded-xl hover:bg-slate-800 transition-colors">
                    <span class="material-symbols-outlined text-xl">close</span>
                </button>

                <template x-if="activePanel">
                    <div class="space-y-6">
                        <!-- Top Header with Logo & Title -->
                        <div class="flex items-center gap-4 border-b border-slate-800 pb-5">
                            <div class="p-3 bg-slate-950 rounded-2xl border border-slate-800 shadow-inner flex items-center justify-center shrink-0" x-html="activePanel.logo_html"></div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="font-['Hanken_Grotesk'] text-xl font-extrabold text-white" x-text="activePanel.name"></h3>
                                    <template x-if="activePanel.featured">
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold font-['JetBrains_Mono'] bg-gradient-to-r from-amber-500/20 to-orange-500/20 border border-amber-500/40 text-amber-300 uppercase tracking-wider">
                                            FEATURED
                                        </span>
                                    </template>
                                </div>
                                <div class="mt-1 inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 text-[10px] font-bold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                    <span>Supported & Fully Integrated</span>
                                </div>
                            </div>
                        </div>

                        <!-- Description Body -->
                        <div class="space-y-2">
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider font-['JetBrains_Mono']">Overview & Compatibility</h4>
                            <p class="text-slate-300 text-sm leading-relaxed" x-text="activePanel.description || 'Seamlessly connects with HostNinja for automated provisioning, server management, billing, and DNS synchronization.'"></p>
                        </div>

                        <!-- Action Footer -->
                        <div class="pt-4 border-t border-slate-800 flex items-center justify-between gap-3">
                            <button type="button" @click="showModal = false" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold rounded-xl transition-colors">
                                Close
                            </button>

                            <template x-if="activePanel.official_url">
                                <a :href="activePanel.official_url" target="_blank" rel="noopener noreferrer" class="px-5 py-2.5 bg-[#0059bb] hover:bg-blue-600 text-white text-xs font-bold rounded-xl shadow-lg shadow-blue-500/20 flex items-center gap-1.5 transition-all">
                                    <span>Visit Provider Website</span>
                                    <span class="material-symbols-outlined text-sm">open_in_new</span>
                                </a>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </section>
@endif
