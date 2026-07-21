<div class="w-full space-y-12">
    <!-- Mode Switcher Tabs (Direct vs AI Domain Generator) -->
    <div class="flex items-center justify-center gap-3">
        <button type="button" wire:click="$set('isAiActive', false)" class="px-5 py-2.5 rounded-full text-xs font-bold transition-all flex items-center gap-2 {{ !$isAiActive ? 'bg-slate-900 text-white shadow-md' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            <span class="material-symbols-outlined text-sm">search</span>
            <span>Direct Domain Lookup</span>
        </button>

        <button type="button" wire:click="$set('isAiActive', true)" class="px-5 py-2.5 rounded-full text-xs font-bold transition-all flex items-center gap-2 {{ $isAiActive ? 'bg-[#0059bb] text-white shadow-lg shadow-blue-500/30 border border-[#0059bb]' : 'bg-white text-[#0059bb] border border-[#0059bb]/30 hover:bg-blue-50' }}">
            <span class="material-symbols-outlined text-sm text-[#00F5FF]">auto_awesome</span>
            <span>AI Domain Generator</span>
        </button>
    </div>

    <!-- 1. AI DOMAIN GENERATOR ASSISTANT PANEL -->
    @if($isAiActive)
        <div class="bg-white p-8 rounded-3xl shadow-xl space-y-6 border-2 border-[#0059bb]/30 relative overflow-hidden text-left max-w-4xl mx-auto">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-[#0059bb]/10 flex items-center justify-center text-[#0059bb] font-bold shadow-sm border border-[#0059bb]/20">
                        <span class="material-symbols-outlined text-2xl">psychology</span>
                    </div>
                    <div>
                        <h3 class="font-['Hanken_Grotesk'] text-2xl font-extrabold text-slate-900">AI Domain Naming Assistant</h3>
                        <p class="text-xs text-slate-600 font-medium mt-0.5">Describe your business idea or brand concept to generate available domain names across all extensions.</p>
                    </div>
                </div>
                <span class="px-3.5 py-1.5 bg-[#0059bb]/10 text-[#0059bb] rounded-full text-xs font-extrabold border border-[#0059bb]/20 font-['JetBrains_Mono'] self-start sm:self-center">
                    ⚡ POWERED BY NINJA AI
                </span>
            </div>

            <form wire:submit.prevent="generateAiSuggestions" class="space-y-4">
                <div class="relative">
                    <input type="text" wire:model.defer="aiPrompt" placeholder="e.g., Organic Kenyan coffee shop in Nairobi, or SaaS invoicing app..." class="w-full pl-5 pr-36 py-4 bg-slate-50 border border-slate-300 rounded-2xl text-xs font-bold text-slate-900 placeholder:text-slate-400 placeholder:font-normal focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0059bb]">
                    <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 px-6 py-2.5 bg-[#0059bb] hover:bg-blue-600 text-white font-extrabold text-xs rounded-xl transition-all shadow-md flex items-center gap-1.5">
                        <span wire:loading.remove wire:target="generateAiSuggestions">Generate</span>
                        <span wire:loading wire:target="generateAiSuggestions" class="material-symbols-outlined text-sm animate-spin">refresh</span>
                    </button>
                </div>

                <!-- Quick Prompts Preset Chips -->
                <div class="flex flex-wrap items-center gap-2.5 text-xs">
                    <span class="text-slate-600 font-bold">Try ideas:</span>
                    <button type="button" wire:click="$set('aiPrompt', 'Kenyan organic coffee brand'); generateAiSuggestions()" class="px-4 py-1.5 bg-slate-100 hover:bg-[#0059bb] hover:text-white rounded-full border border-slate-200 text-slate-700 font-semibold transition-colors">☕ Kenyan Coffee</button>
                    <button type="button" wire:click="$set('aiPrompt', 'Fintech M-Pesa payments app'); generateAiSuggestions()" class="px-4 py-1.5 bg-slate-100 hover:bg-[#0059bb] hover:text-white rounded-full border border-slate-200 text-slate-700 font-semibold transition-colors">⚡ M-Pesa Fintech</button>
                    <button type="button" wire:click="$set('aiPrompt', 'Nairobi tech software agency'); generateAiSuggestions()" class="px-4 py-1.5 bg-slate-100 hover:bg-[#0059bb] hover:text-white rounded-full border border-slate-200 text-slate-700 font-semibold transition-colors">🚀 Tech Agency</button>
                </div>
            </form>

            <!-- Generated AI Suggestions Grid -->
            @if($hasAiGenerated && !empty($aiSuggestions))
                <div class="space-y-4 pt-5 border-t border-slate-100">
                    <h4 class="font-['Hanken_Grotesk'] text-sm font-bold text-slate-900 uppercase tracking-wider">Recommended Brandable Domains:</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($aiSuggestions as $sug)
                            <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200 flex items-center justify-between gap-3 hover:border-[#0059bb] transition-all">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-['Hanken_Grotesk'] text-base font-extrabold text-slate-900">{{ $sug['full_domain'] }}</span>
                                        @if($sug['available'])
                                            <span class="px-2.5 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">AVAILABLE</span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-700 border border-rose-200">TAKEN</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-[11px] text-slate-500 font-['JetBrains_Mono']">{{ $sug['tag'] }}</span>
                                        <span class="text-xs font-extrabold text-[#0059bb]">${{ number_format($sug['price'], 2) }}/yr</span>
                                    </div>
                                </div>

                                <button type="button" wire:click="selectAiDomain('{{ $sug['full_domain'] }}')" class="px-4 py-2.5 bg-[#0059bb] hover:bg-blue-600 text-white font-bold text-xs rounded-xl transition-all shadow-md">
                                    Check & Register
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- 2. DIRECT SEARCH BAR COMPONENT - Matching Image 3 Exactly -->
    <div class="relative max-w-4xl mx-auto">
        <form wire:submit.prevent="search" class="flex items-center bg-white p-2 pl-6 rounded-full border border-slate-200 shadow-sm">
            <input type="text" wire:model.defer="query" placeholder="hostninja.com" class="w-full bg-transparent border-none focus:outline-none focus:ring-0 text-lg font-bold text-slate-900 placeholder:text-slate-400">

            <button type="submit" class="bg-black hover:bg-slate-900 text-white px-8 py-4 rounded-full font-bold text-sm transition-all flex items-center gap-2 shrink-0 shadow">
                <span>Search</span>
                <span class="material-symbols-outlined text-lg">search</span>
            </button>
        </form>

        <!-- Popular Extensions Row below input -->
        <div class="mt-4 flex flex-wrap gap-2 text-xs font-bold text-slate-400 justify-center items-center">
            <span class="uppercase tracking-widest font-['JetBrains_Mono'] text-[10px]">Popular:</span>
            <a href="#" wire:click="$set('query', 'hostninja.com'); search(app(\App\Services\Registrars\RegistrarManager::class))" class="text-[#0059bb] hover:underline font-bold font-['JetBrains_Mono']">.COM $9.99</a>
            <a href="#" wire:click="$set('query', 'hostninja.io'); search(app(\App\Services\Registrars\RegistrarManager::class))" class="text-[#0059bb] hover:underline font-bold font-['JetBrains_Mono']">.IO $32.00</a>
            <a href="#" wire:click="$set('query', 'hostninja.tech'); search(app(\App\Services\Registrars\RegistrarManager::class))" class="text-[#0059bb] hover:underline font-bold font-['JetBrains_Mono']">.TECH $4.99</a>
            <a href="#" wire:click="$set('query', 'hostninja.africa'); search(app(\App\Services\Registrars\RegistrarManager::class))" class="text-[#0059bb] hover:underline font-bold font-['JetBrains_Mono']">.AFRICA $12.50</a>
        </div>
    </div>

    <!-- Cart Notification Banner -->
    @if($cartMessage)
        <div class="max-w-4xl mx-auto p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 text-xs font-bold flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                <span>{{ $cartMessage }}</span>
            </div>
            <a href="{{ route('checkout.index') }}" class="px-4 py-1.5 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-500 transition-colors shadow">Go to Checkout &rarr;</a>
        </div>
    @endif

    <!-- 3. TWO COLUMN FILTER & RESULTS GRID - Matching Image 3 Exactly -->
    @if($hasSearched && !empty($results))
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 text-left max-w-7xl mx-auto items-start">
            
            <!-- Left Column: Filters -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Filters Card -->
                <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-6">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <span class="font-['Hanken_Grotesk'] text-base font-extrabold text-slate-900">Filters</span>
                        <span class="material-symbols-outlined text-slate-400 text-lg">tune</span>
                    </div>

                    <!-- Extensions -->
                    <div class="space-y-3">
                        <span class="block font-['JetBrains_Mono'] text-[10px] font-bold text-slate-400 tracking-wider uppercase">Extensions</span>
                        <div class="space-y-2 text-xs font-bold text-slate-700">
                            <label class="flex items-center gap-2.5 cursor-pointer">
                                <input type="checkbox" checked class="w-4 h-4 rounded text-[#0059bb] border-slate-300 focus:ring-0">
                                <span>Popular (.com, .net)</span>
                            </label>
                            <label class="flex items-center gap-2.5 cursor-pointer">
                                <input type="checkbox" class="w-4 h-4 rounded text-[#0059bb] border-slate-300 focus:ring-0">
                                <span>Tech (.io, .ai, .dev)</span>
                            </label>
                            <label class="flex items-center gap-2.5 cursor-pointer">
                                <input type="checkbox" class="w-4 h-4 rounded text-[#0059bb] border-slate-300 focus:ring-0">
                                <span>Regional (.co.ke, .africa)</span>
                            </label>
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div class="space-y-3 pt-4 border-t border-slate-100">
                        <span class="block font-['JetBrains_Mono'] text-[10px] font-bold text-slate-400 tracking-wider uppercase">Price Range</span>
                        <div class="space-y-2">
                            <input type="range" min="0" max="100" value="100" class="w-full accent-[#0059bb] h-1 bg-slate-200 rounded-lg appearance-none cursor-pointer">
                            <div class="flex justify-between text-[11px] font-['JetBrains_Mono'] text-slate-400 font-bold">
                                <span>$0</span>
                                <span>$100+</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hosting Promo Card -->
                <div class="bg-gradient-to-tr from-[#f2f4f6] to-[#eceef0] p-6 rounded-3xl border border-slate-200/80 shadow-sm relative overflow-hidden flex flex-col justify-between">
                    <div>
                        <span class="px-2.5 py-0.5 rounded bg-amber-400/20 text-amber-800 text-[9px] font-bold uppercase tracking-wider">Bundle Promo</span>
                        <h4 class="font-['Hanken_Grotesk'] text-sm font-extrabold text-slate-900 mt-2 leading-snug">Bundle with hosting and save 20% on your domain!</h4>
                    </div>
                    <!-- Mockup image -->
                    <div class="mt-4 rounded-xl overflow-hidden border border-slate-300 shadow-sm">
                        <img class="w-full aspect-video object-cover" alt="Hosting bundles" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBZVHUnZ8k_R_rUzXO-MWr9bwmoL-LJmWYHv5zcLpqAqTPDftXGEO2hocRX73AkG07fTK2qC9VFscg4LsBghFShEHMr7HUtCRaqPbVH8yTtROnawQFyUrrticCT0gD0vbIkMuiGPyNlNgKvV6ZRQhgRO8kuyrOO9yPqRsT8UxHUhjgRNdQJ3YUVx0NrXkPbHc_PCTnbp3YhKLyoy6zTQ0Us6-wZwR18p_B8b_TIQeMu_tJiMxB-5D9k">
                    </div>
                </div>
            </div>

            <!-- Right Column: Results content -->
            <div class="lg:col-span-3 space-y-8">
                
                <!-- Main Exact Match Domain Header Card -->
                @if(isset($results['requested']))
                    <div class="bg-white p-8 rounded-3xl border border-slate-200/90 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative overflow-hidden">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2.5">
                                <h3 class="font-['Hanken_Grotesk'] text-3xl font-extrabold text-slate-900 tracking-tight">{{ $results['requested']['domain'] }}</h3>
                                @if($results['requested']['available'])
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200 flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        <span>AVAILABLE</span>
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-700 border border-rose-200 flex items-center gap-1">
                                        <span>TAKEN</span>
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs font-semibold text-slate-500">Secure the foundation of your digital empire today.</p>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="text-right">
                                <div class="flex items-baseline gap-0.5">
                                    <span class="text-2xl font-extrabold text-[#0059bb] font-['Hanken_Grotesk']">${{ number_format($results['requested']['price'], 2) }}</span>
                                    <span class="text-slate-500 text-xs font-semibold">/yr</span>
                                </div>
                            </div>
                            <button type="button" wire:click="addToCart('{{ $results['requested']['domain'] }}', {{ $results['requested']['price'] }})" class="px-6 py-3 bg-[#0059bb] hover:bg-blue-600 text-white font-extrabold text-xs rounded-xl shadow-md flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">add_shopping_cart</span>
                                <span>Add to Cart</span>
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Featured Extensions Section -->
                <div class="space-y-4">
                    <h4 class="font-['Hanken_Grotesk'] text-base font-extrabold text-slate-900">Featured Extensions</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        
                        <!-- .io -->
                        <div class="bg-white p-6 rounded-3xl border border-slate-200/90 shadow-sm hover:shadow-md transition-all flex items-center justify-between">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-['Hanken_Grotesk'] text-xl font-extrabold text-slate-900">{{ $results['base_name'] }}.io</span>
                                    <span class="px-2 py-0.5 bg-[#f2f4f6] text-slate-600 rounded text-[9px] font-extrabold font-['JetBrains_Mono']">TECH FAV</span>
                                </div>
                                <div class="text-xs font-bold text-slate-400 font-['JetBrains_Mono']">{{ $results['base_name'] }}.io</div>
                                <div class="font-extrabold text-slate-900 text-sm">$32.00<span class="text-slate-500 font-normal text-xs">/yr</span></div>
                            </div>
                            <button type="button" wire:click="addToCart('{{ $results['base_name'] }}.io', 32.00)" class="w-10 h-10 rounded-full border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-[#0059bb] hover:text-white transition-colors">
                                <span class="material-symbols-outlined text-lg">add_shopping_cart</span>
                            </button>
                        </div>

                        <!-- .co.ke -->
                        <div class="bg-white p-6 rounded-3xl border border-slate-200/90 shadow-sm hover:shadow-md transition-all flex items-center justify-between">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-['Hanken_Grotesk'] text-xl font-extrabold text-slate-900">{{ $results['base_name'] }}.co.ke</span>
                                    <span class="px-2 py-0.5 bg-[#f2f4f6] text-slate-600 rounded text-[9px] font-extrabold font-['JetBrains_Mono']">REGIONAL</span>
                                </div>
                                <div class="text-xs font-bold text-slate-400 font-['JetBrains_Mono']">{{ $results['base_name'] }}.co.ke</div>
                                <div class="font-extrabold text-slate-900 text-sm">$18.50<span class="text-slate-500 font-normal text-xs">/yr</span></div>
                            </div>
                            <button type="button" wire:click="addToCart('{{ $results['base_name'] }}.co.ke', 18.50)" class="w-10 h-10 rounded-full border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-[#0059bb] hover:text-white transition-colors">
                                <span class="material-symbols-outlined text-lg">add_shopping_cart</span>
                            </button>
                        </div>

                        <!-- .africa -->
                        <div class="bg-white p-6 rounded-3xl border border-slate-200/90 shadow-sm hover:shadow-md transition-all flex items-center justify-between">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-['Hanken_Grotesk'] text-xl font-extrabold text-slate-900">{{ $results['base_name'] }}.africa</span>
                                    <span class="px-2 py-0.5 bg-[#f2f4f6] text-slate-600 rounded text-[9px] font-extrabold font-['JetBrains_Mono']">EMERGING</span>
                                </div>
                                <div class="text-xs font-bold text-slate-400 font-['JetBrains_Mono']">{{ $results['base_name'] }}.africa</div>
                                <div class="font-extrabold text-slate-900 text-sm">$12.50<span class="text-slate-500 font-normal text-xs">/yr</span></div>
                            </div>
                            <button type="button" wire:click="addToCart('{{ $results['base_name'] }}.africa', 12.50)" class="w-10 h-10 rounded-full border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-[#0059bb] hover:text-white transition-colors">
                                <span class="material-symbols-outlined text-lg">add_shopping_cart</span>
                            </button>
                        </div>

                        <!-- .org -->
                        <div class="bg-white p-6 rounded-3xl border border-slate-200/90 shadow-sm hover:shadow-md transition-all flex items-center justify-between">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-['Hanken_Grotesk'] text-xl font-extrabold text-slate-900">{{ $results['base_name'] }}.org</span>
                                    <span class="px-2 py-0.5 bg-[#f2f4f6] text-slate-600 rounded text-[9px] font-extrabold font-['JetBrains_Mono']">TRUSTED</span>
                                </div>
                                <div class="text-xs font-bold text-slate-400 font-['JetBrains_Mono']">{{ $results['base_name'] }}.org</div>
                                <div class="font-extrabold text-slate-900 text-sm">$14.99<span class="text-slate-500 font-normal text-xs">/yr</span></div>
                            </div>
                            <button type="button" wire:click="addToCart('{{ $results['base_name'] }}.org', 14.99)" class="w-10 h-10 rounded-full border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-[#0059bb] hover:text-white transition-colors">
                                <span class="material-symbols-outlined text-lg">add_shopping_cart</span>
                            </button>
                        </div>

                    </div>
                </div>

                <!-- You Might Also Like Section -->
                <div class="space-y-4">
                    <h4 class="font-['Hanken_Grotesk'] text-base font-extrabold text-slate-900">You might also like</h4>
                    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm divide-y divide-slate-100 overflow-hidden">
                        
                        <!-- Row 1 -->
                        <div class="p-5 flex items-center justify-between text-xs hover:bg-slate-50 transition-colors">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-[#0059bb]">auto_awesome</span>
                                <div>
                                    <span class="font-bold text-sm text-slate-900">get{{ $results['base_name'] }}.com</span>
                                    <span class="block text-[10px] text-slate-400 font-medium">High relevance match</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 font-bold">
                                <span class="text-slate-900">$11.99</span>
                                <button type="button" wire:click="addToCart('get{{ $results['base_name'] }}.com', 11.99)" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-xl">
                                    Add +
                                </button>
                            </div>
                        </div>

                        <!-- Row 2 -->
                        <div class="p-5 flex items-center justify-between text-xs hover:bg-slate-50 transition-colors">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-[#0059bb]">auto_awesome</span>
                                <div>
                                    <span class="font-bold text-sm text-slate-900">{{ $results['base_name'] }}.net</span>
                                    <span class="block text-[10px] text-slate-400 font-medium">Classic alternative</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 font-bold">
                                <span class="text-slate-900">$12.99</span>
                                <button type="button" wire:click="addToCart('{{ $results['base_name'] }}.net', 12.99)" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-xl">
                                    Add +
                                </button>
                            </div>
                        </div>

                        <!-- Row 3 -->
                        <div class="p-5 flex items-center justify-between text-xs hover:bg-slate-50 transition-colors">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-[#0059bb]">auto_awesome</span>
                                <div>
                                    <span class="font-bold text-sm text-slate-900">{{ $results['base_name'] }}-ninja.cloud</span>
                                    <span class="block text-[10px] text-slate-400 font-medium">Modern & descriptive</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 font-bold">
                                <span class="text-slate-900">$8.50</span>
                                <button type="button" wire:click="addToCart('{{ $results['base_name'] }}-ninja.cloud', 8.50)" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-xl">
                                    Add +
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    @endif
</div>
