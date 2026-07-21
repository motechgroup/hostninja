<div class="space-y-8">

    <!-- Flash Message Notification -->
    @if($message)
        <div class="p-4 rounded-2xl border text-xs font-bold flex items-center justify-between shadow-sm {{ $messageType === 'success' ? 'bg-emerald-500/10 border-emerald-500/30 text-emerald-700' : 'bg-rose-500/10 border-rose-500/30 text-rose-700' }}">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">{{ $messageType === 'success' ? 'check_circle' : 'error' }}</span>
                <span>{{ $message }}</span>
            </div>
            <button wire:click="$set('message', '')" class="text-slate-400 hover:text-slate-600">
                <span class="material-symbols-outlined text-sm">close</span>
            </button>
        </div>
    @endif

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-200 pb-6">
        <div>
            <div class="flex items-center gap-2 text-xs text-[#0059bb] font-bold uppercase tracking-widest mb-1">
                <span>Integrations</span>
                <span>/</span>
                <span>Domain Registrars</span>
            </div>
            <h1 class="font-['Hanken_Grotesk'] text-3xl font-extrabold text-slate-900">Registrar Integrations System</h1>
            <p class="text-xs text-slate-600 font-medium mt-1">Connect WHMCS/Blesta-grade domain registrar API accounts without editing source code.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.registrar-logs') }}" class="px-4 py-2.5 bg-slate-900 hover:bg-[#0059bb] text-white text-xs font-bold rounded-xl transition-all shadow flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">description</span>
                <span>View Registrar API Logs</span>
            </a>
        </div>
    </div>

    <!-- Registrars Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($registrars as $reg)
            <div class="bg-white rounded-3xl border-2 {{ $reg->default ? 'border-[#0059bb] shadow-xl ring-4 ring-blue-500/10' : 'border-slate-200/90 shadow-sm' }} hover:border-[#0059bb] transition-all duration-300 p-6 flex flex-col justify-between space-y-6">
                <div>
                    <!-- Top Status Row -->
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-blue-50 border border-blue-200/60 flex items-center justify-center text-[#0059bb] font-extrabold font-['Hanken_Grotesk'] shadow-sm text-sm">
                                {{ strtoupper(substr($reg->slug, 0, 2)) }}
                            </div>
                            <div>
                                <h3 class="font-['Hanken_Grotesk'] text-lg font-extrabold text-slate-900 leading-tight">{{ $reg->name }}</h3>
                                <span class="text-[11px] font-semibold text-slate-400 font-['JetBrains_Mono']">Slug: {{ $reg->slug }}</span>
                            </div>
                        </div>

                        @if($reg->default)
                            <span class="px-3 py-1 rounded-full text-[10px] font-extrabold bg-blue-100 text-[#0059bb] border border-blue-200 uppercase tracking-wider">
                                DEFAULT
                            </span>
                        @elseif($reg->enabled)
                            <span class="px-3 py-1 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-700 border border-emerald-200 uppercase tracking-wider">
                                ENABLED
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-[10px] font-extrabold bg-slate-100 text-slate-500 border border-slate-200 uppercase tracking-wider">
                                DISABLED
                            </span>
                        @endif
                    </div>

                    <p class="text-xs text-slate-600 leading-relaxed mb-4 min-h-[36px]">{{ $reg->description }}</p>

                    <!-- Features & Environment Badges -->
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center justify-between text-xs font-semibold">
                            <span class="text-slate-500">Environment:</span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $reg->sandbox ? 'bg-amber-100 text-amber-800 border border-amber-200' : 'bg-emerald-100 text-emerald-800 border border-emerald-200' }}">
                                {{ $reg->sandbox ? '🧪 SANDBOX' : '🚀 PRODUCTION' }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between text-xs font-semibold">
                            <span class="text-slate-500">Last API Connection:</span>
                            <span class="text-slate-800 font-['JetBrains_Mono'] text-[11px]">
                                {{ $reg->last_connection ? $reg->last_connection->diffForHumans() : 'Never' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Card Actions Row -->
                <div class="pt-4 border-t border-slate-100 space-y-2">
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" wire:click="testConnection({{ $reg->id }})" class="w-full py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold rounded-xl transition-colors flex items-center justify-center gap-1.5">
                            <span class="material-symbols-outlined text-sm">sensors</span>
                            <span>Test API</span>
                        </button>

                        <button type="button" wire:click="editConfig({{ $reg->id }})" class="w-full py-2 bg-[#0059bb] hover:bg-blue-600 text-white text-xs font-bold rounded-xl transition-colors flex items-center justify-center gap-1.5 shadow-sm">
                            <span class="material-symbols-outlined text-sm">settings</span>
                            <span>Configure</span>
                        </button>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" wire:click="toggleEnabled({{ $reg->id }})" class="w-full py-2 border text-xs font-bold rounded-xl transition-colors {{ $reg->enabled ? 'border-rose-200 text-rose-700 hover:bg-rose-50' : 'border-emerald-200 text-emerald-700 hover:bg-emerald-50' }}">
                            {{ $reg->enabled ? 'Disable' : 'Enable' }}
                        </button>

                        @if(!$reg->default && $reg->enabled)
                            <button type="button" wire:click="setDefault({{ $reg->id }})" class="w-full py-2 border border-blue-200 text-[#0059bb] hover:bg-blue-50 text-xs font-bold rounded-xl transition-colors">
                                Set Default
                            </button>
                        @else
                            <button type="button" wire:click="syncDomains({{ $reg->id }})" class="w-full py-2 bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-xl transition-colors flex items-center justify-center gap-1">
                                <span class="material-symbols-outlined text-xs">sync</span>
                                <span>Sync</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- CONFIGURATION EDIT MODAL -->
    @if($selectedRegistrarId)
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl max-w-2xl w-full p-8 shadow-2xl space-y-6 border border-slate-200 relative">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h3 class="font-['Hanken_Grotesk'] text-xl font-extrabold text-slate-900">Configure API Settings</h3>
                        <p class="text-xs text-slate-500 font-medium mt-0.5">API credentials are automatically encrypted in the database via Laravel Encrypted Casts.</p>
                    </div>
                    <button wire:click="$set('selectedRegistrarId', null)" class="text-slate-400 hover:text-slate-600">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <form wire:submit.prevent="saveConfig" class="space-y-4 text-xs font-bold text-slate-700">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-1 text-slate-800">API Key / Secret Key</label>
                            <input type="password" wire:model.defer="configApiKey" placeholder="Enter API Key..." class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl font-mono text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0059bb]">
                        </div>

                        <div>
                            <label class="block mb-1 text-slate-800">API Secret (If applicable)</label>
                            <input type="password" wire:model.defer="configApiSecret" placeholder="Enter API Secret..." class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl font-mono text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0059bb]">
                        </div>

                        <div>
                            <label class="block mb-1 text-slate-800">API Username / Email</label>
                            <input type="text" wire:model.defer="configUsername" placeholder="Enter Username..." class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl font-mono text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0059bb]">
                        </div>

                        <div>
                            <label class="block mb-1 text-slate-800">API Password</label>
                            <input type="password" wire:model.defer="configPassword" placeholder="Enter Password..." class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl font-mono text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0059bb]">
                        </div>

                        <div>
                            <label class="block mb-1 text-slate-800">Reseller ID / Customer ID</label>
                            <input type="text" wire:model.defer="configResellerId" placeholder="e.g. 984124..." class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl font-mono text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0059bb]">
                        </div>

                        <div>
                            <label class="block mb-1 text-slate-800">Custom Endpoint URL</label>
                            <input type="text" wire:model.defer="configEndpoint" placeholder="e.g. https://httpapi.com/api/..." class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl font-mono text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0059bb]">
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-200">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" id="configSandbox" wire:model.defer="configSandbox" class="w-4 h-4 text-[#0059bb] rounded focus:ring-0 cursor-pointer">
                            <label for="configSandbox" class="cursor-pointer font-bold text-slate-900">Enable Sandbox / Test Mode</label>
                        </div>

                        <div class="flex items-center gap-3">
                            <input type="checkbox" id="configDefault" wire:model.defer="configDefault" class="w-4 h-4 text-[#0059bb] rounded focus:ring-0 cursor-pointer">
                            <label for="configDefault" class="cursor-pointer font-bold text-[#0059bb]">Set as Default Registrar</label>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" wire:click="$set('selectedRegistrarId', null)" class="px-5 py-2.5 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 bg-[#0059bb] hover:bg-blue-600 text-white font-extrabold rounded-xl shadow-md">Save Credentials</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
