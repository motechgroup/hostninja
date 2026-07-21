<x-admin-layout>
    <x-slot name="title">HostNinja Admin | Payment Gateways & Logo Manager</x-slot>

    <div class="space-y-8" x-data="paymentGatewayManager()">
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 text-xs font-bold flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Header -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1 class="font-['Hanken_Grotesk'] text-2xl font-extrabold text-slate-900">Payment Gateways & Logo Manager</h1>
                <p class="text-xs text-slate-500 mt-1">Manage global payment providers, upload custom logos/SVGs, and configure footer payment badges.</p>
            </div>
            <div class="flex gap-3">
                <button type="button" @click="showAddModal = true" class="px-4 py-2.5 bg-[#0059bb] hover:bg-blue-600 text-white font-bold text-xs rounded-xl shadow transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">add</span>
                    <span>Add New Gateway</span>
                </button>
            </div>
        </div>

        <!-- Metrics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="glass-card p-6 rounded-2xl border border-slate-200">
                <div class="flex items-center justify-between">
                    <span class="text-slate-500 text-xs font-semibold">Total Configured</span>
                    <div class="p-2 bg-blue-50 text-[#0059bb] rounded-lg">
                        <span class="material-symbols-outlined text-lg">payments</span>
                    </div>
                </div>
                <div class="font-['Hanken_Grotesk'] text-2xl font-extrabold text-slate-900 mt-2">{{ count($paymentMethods) }}</div>
                <p class="text-[10px] text-slate-400 mt-1">Global & local methods</p>
            </div>

            <div class="glass-card p-6 rounded-2xl border border-slate-200">
                <div class="flex items-center justify-between">
                    <span class="text-slate-500 text-xs font-semibold">Active Gateways</span>
                    <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg">
                        <span class="material-symbols-outlined text-lg">check_circle</span>
                    </div>
                </div>
                <div class="font-['Hanken_Grotesk'] text-2xl font-extrabold text-emerald-600 mt-2">{{ $paymentMethods->where('is_enabled', true)->count() }}</div>
                <p class="text-[10px] text-slate-400 mt-1">Enabled for customer checkout</p>
            </div>

            <div class="glass-card p-6 rounded-2xl border border-slate-200">
                <div class="flex items-center justify-between">
                    <span class="text-slate-500 text-xs font-semibold">Footer Visible Logos</span>
                    <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg">
                        <span class="material-symbols-outlined text-lg">visibility</span>
                    </div>
                </div>
                <div class="font-['Hanken_Grotesk'] text-2xl font-extrabold text-indigo-600 mt-2">{{ $paymentMethods->where('show_in_footer', true)->count() }}</div>
                <p class="text-[10px] text-slate-400 mt-1">Logos shown on public footer</p>
            </div>

            <div class="glass-card p-6 rounded-2xl border border-slate-200">
                <div class="flex items-center justify-between">
                    <span class="text-slate-500 text-xs font-semibold">Footer Section Status</span>
                    <div class="p-2 bg-amber-50 text-amber-600 rounded-lg">
                        <span class="material-symbols-outlined text-lg">toggle_on</span>
                    </div>
                </div>
                <div class="font-['Hanken_Grotesk'] text-xl font-extrabold mt-2 {{ ($settings['show_footer_payment_methods'] ?? '1') === '1' ? 'text-emerald-600' : 'text-slate-400' }}">
                    {{ ($settings['show_footer_payment_methods'] ?? '1') === '1' ? 'ENABLED' : 'DISABLED' }}
                </div>
                <p class="text-[10px] text-slate-400 mt-1">Global website setting</p>
            </div>
        </div>

        <!-- Global Settings & API Configuration Form -->
        <form method="POST" action="{{ route('admin.settings.update') }}" class="glass-card p-6 rounded-3xl border border-slate-200 space-y-6">
            @csrf
            
            <div class="flex flex-col md:flex-row md:items-center justify-between border-b border-slate-100 pb-4 gap-4">
                <div>
                    <h3 class="font-['Hanken_Grotesk'] text-lg font-bold text-slate-900">API Credentials & Footer Visibility</h3>
                    <p class="text-xs text-slate-500">Configure core M-Pesa STK Push parameters and Stripe publishable keys.</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-slate-700">Footer Logos Section:</span>
                    <input type="hidden" name="show_footer_payment_methods" value="0">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="show_footer_payment_methods" value="1" {{ ($settings['show_footer_payment_methods'] ?? '1') === '1' ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                    </label>
                    <button type="submit" class="px-5 py-2 bg-slate-900 hover:bg-[#0059bb] text-white font-bold text-xs rounded-xl shadow transition-all">
                        Save Credentials
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- M-Pesa -->
                <div class="p-5 bg-emerald-50/60 rounded-2xl border border-emerald-200/80 space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                            <h4 class="font-bold text-xs text-emerald-900">Safaricom M-Pesa STK Push API</h4>
                        </div>
                        <span class="text-[10px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded">Daraja v2</span>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <label class="text-[11px] font-semibold text-slate-700 block mb-1">Paybill / Till Shortcode</label>
                            <input type="text" name="mpesa_shortcode" value="{{ $settings['mpesa_shortcode'] ?? '174379' }}" class="w-full p-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-900">
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-slate-700 block mb-1">STK Passkey</label>
                            <input type="password" name="mpesa_passkey" value="{{ $settings['mpesa_passkey'] ?? '' }}" placeholder="••••••••••••" class="w-full p-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-900">
                        </div>
                    </div>
                </div>

                <!-- Stripe -->
                <div class="p-5 bg-blue-50/60 rounded-2xl border border-blue-200/80 space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                            <h4 class="font-bold text-xs text-blue-900">Stripe Card Gateway</h4>
                        </div>
                        <span class="text-[10px] font-bold text-blue-700 bg-blue-100 px-2 py-0.5 rounded">v3 Elements</span>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <label class="text-[11px] font-semibold text-slate-700 block mb-1">Stripe Publishable Key</label>
                            <input type="text" name="stripe_key" value="{{ $settings['stripe_key'] ?? '' }}" placeholder="pk_test_..." class="w-full p-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-900">
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-slate-700 block mb-1">Billing Currency</label>
                            <input type="text" name="currency" value="{{ $settings['currency'] ?? 'KES' }}" class="w-full p-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-900">
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Payment Methods Manager Grid -->
        <div class="glass-card p-6 rounded-3xl border border-slate-200 space-y-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
                <div>
                    <h3 class="font-['Hanken_Grotesk'] text-lg font-bold text-slate-900">Configured Payment Logos & Methods ({{ count($paymentMethods) }})</h3>
                    <p class="text-xs text-slate-500">Upload custom logo files, select preset icons, toggle footer visibility, or adjust ordering.</p>
                </div>

                <!-- Category Filters -->
                <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl text-xs font-semibold overflow-x-auto">
                    <button type="button" @click="categoryFilter = 'all'" :class="categoryFilter === 'all' ? 'bg-white text-slate-900 shadow' : 'text-slate-600'" class="px-3 py-1.5 rounded-lg transition-all">All</button>
                    <button type="button" @click="categoryFilter = 'cards'" :class="categoryFilter === 'cards' ? 'bg-white text-slate-900 shadow' : 'text-slate-600'" class="px-3 py-1.5 rounded-lg transition-all">Cards</button>
                    <button type="button" @click="categoryFilter = 'wallets'" :class="categoryFilter === 'wallets' ? 'bg-white text-slate-900 shadow' : 'text-slate-600'" class="px-3 py-1.5 rounded-lg transition-all">Wallets</button>
                    <button type="button" @click="categoryFilter = 'mobile'" :class="categoryFilter === 'mobile' ? 'bg-white text-slate-900 shadow' : 'text-slate-600'" class="px-3 py-1.5 rounded-lg transition-all">Mobile Money</button>
                    <button type="button" @click="categoryFilter = 'crypto'" :class="categoryFilter === 'crypto' ? 'bg-white text-slate-900 shadow' : 'text-slate-600'" class="px-3 py-1.5 rounded-lg transition-all">Crypto</button>
                    <button type="button" @click="categoryFilter = 'banking'" :class="categoryFilter === 'banking' ? 'bg-white text-slate-900 shadow' : 'text-slate-600'" class="px-3 py-1.5 rounded-lg transition-all">Banking</button>
                </div>
            </div>

            <!-- Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($paymentMethods as $pm)
                    <div x-show="categoryFilter === 'all' || categoryFilter === '{{ $pm->category }}'" class="p-4 bg-white rounded-2xl border border-slate-200 shadow-sm hover:border-[#0059bb]/50 transition-all space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="p-1.5 bg-slate-900 rounded-xl border border-slate-800 shadow-inner flex items-center justify-center">
                                {!! $pm->logo_html !!}
                            </div>
                            <span class="text-[10px] font-['JetBrains_Mono'] font-bold text-slate-400 bg-slate-100 px-2 py-0.5 rounded">Order #{{ $pm->sort_order }}</span>
                        </div>

                        <div>
                            <div class="font-bold text-xs text-slate-900">{{ $pm->name }}</div>
                            <div class="text-[10px] text-slate-400 capitalize font-mono">{{ $pm->code }} &bull; {{ $pm->category }}</div>
                        </div>

                        <div class="space-y-2 pt-2 border-t border-slate-100">
                            <div class="flex items-center justify-between gap-1">
                                <!-- Footer Visibility Toggle -->
                                <form method="POST" action="{{ route('admin.payment-methods.toggle-footer', $pm->id) }}">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1 rounded-lg text-[10px] font-bold transition-colors flex items-center gap-1 {{ $pm->show_in_footer ? 'bg-blue-50 text-[#0059bb] hover:bg-blue-100' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}" title="Toggle Footer Visibility">
                                        <span class="material-symbols-outlined text-[12px]">{{ $pm->show_in_footer ? 'visibility' : 'visibility_off' }}</span>
                                        <span>{{ $pm->show_in_footer ? 'Footer: Visible' : 'Footer: Hidden' }}</span>
                                    </button>
                                </form>

                                <!-- Active Status Toggle -->
                                <form method="POST" action="{{ route('admin.payment-methods.toggle', $pm->id) }}">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1 rounded-lg text-[10px] font-bold transition-colors {{ $pm->is_enabled ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-amber-100 text-amber-700 hover:bg-amber-200' }}" title="Toggle System Active Status">
                                        {{ $pm->is_enabled ? 'Active' : 'Disabled' }}
                                    </button>
                                </form>
                            </div>

                            <div class="flex items-center justify-end gap-2 pt-1 border-t border-slate-50">
                                <!-- Edit Button -->
                                <button type="button" @click="editMethod = { id: '{{ $pm->id }}', name: '{{ addslashes($pm->name) }}', category: '{{ $pm->category }}', sort_order: {{ $pm->sort_order }}, show_in_footer: {{ $pm->show_in_footer ? 'true' : 'false' }}, icon_svg: '{{ addslashes($pm->icon_svg) }}' }; showEditModal = true" class="text-[11px] text-slate-500 hover:text-[#0059bb] font-semibold flex items-center gap-0.5">
                                    <span class="material-symbols-outlined text-xs">edit</span>
                                    <span>Edit / Change Logo</span>
                                </button>

                                <span class="text-slate-300">&bull;</span>

                                <!-- Delete Button -->
                                <form method="POST" action="{{ route('admin.payment-methods.delete', $pm->id) }}" onsubmit="return confirm('Delete payment method {{ $pm->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-[11px] text-slate-400 hover:text-rose-600 font-semibold flex items-center gap-0.5" title="Delete Gateway">
                                        <span class="material-symbols-outlined text-xs">delete</span>
                                        <span>Delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- MODAL 1: ADD NEW PAYMENT METHOD -->
        <div x-show="showAddModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak>
            <div class="bg-white p-8 rounded-3xl border border-slate-200 max-w-xl w-full shadow-2xl space-y-6" @click.away="showAddModal = false">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-['Hanken_Grotesk'] text-lg font-bold text-slate-900">Add New Supported Payment Gateway</h3>
                    <button type="button" @click="showAddModal = false" class="text-slate-400 hover:text-slate-600 font-bold text-sm">✕</button>
                </div>

                <form method="POST" action="{{ route('admin.payment-methods.create') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-semibold text-slate-700 block mb-1">Gateway Name</label>
                            <input type="text" name="name" placeholder="e.g. Klarna, Google Pay" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900">
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-slate-700 block mb-1">Unique Code Slug</label>
                            <input type="text" name="code" placeholder="e.g. klarna" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-slate-900">
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-slate-700 block mb-1">Category</label>
                            <select name="category" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900">
                                <option value="cards">Credit & Debit Cards</option>
                                <option value="wallets">E-Wallets & Buy-Now-Pay-Later</option>
                                <option value="mobile">Mobile Money</option>
                                <option value="crypto">Cryptocurrency</option>
                                <option value="banking">Bank Transfer</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-slate-700 block mb-1">Sort Order Position</label>
                            <input type="number" name="sort_order" value="15" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900">
                        </div>
                    </div>

                    <!-- Logo Choice Options -->
                    <div class="space-y-3 pt-2 border-t border-slate-100">
                        <label class="text-xs font-bold text-slate-800 block">Select Logo / Icon Source:</label>
                        <div class="flex items-center gap-2 border-b border-slate-100 pb-2">
                            <button type="button" @click="addLogoMode = 'upload'" :class="addLogoMode === 'upload' ? 'bg-[#0059bb] text-white font-bold' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-lg text-xs transition-all">
                                📁 Upload Logo File
                            </button>
                            <button type="button" @click="addLogoMode = 'presets'" :class="addLogoMode === 'presets' ? 'bg-[#0059bb] text-white font-bold' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-lg text-xs transition-all">
                                🎨 Vector Presets
                            </button>
                            <button type="button" @click="addLogoMode = 'custom'" :class="addLogoMode === 'custom' ? 'bg-[#0059bb] text-white font-bold' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-lg text-xs transition-all">
                                💻 Custom SVG / URL
                            </button>
                        </div>

                        <!-- Mode 1: File Upload -->
                        <div x-show="addLogoMode === 'upload'" class="space-y-2">
                            <label class="text-[11px] text-slate-500 block">Upload Logo Image (SVG, PNG, JPG, WebP - max 2MB):</label>
                            <input type="file" name="logo_file" accept="image/*" class="w-full text-xs text-slate-600 p-2 bg-slate-50 border border-slate-200 rounded-xl file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#0059bb] file:text-white">
                        </div>

                        <!-- Mode 2: Presets -->
                        <div x-show="addLogoMode === 'presets'" class="space-y-2" x-cloak>
                            <label class="text-[11px] text-slate-500 block">Click a preset icon to use:</label>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="(svg, key) in presets" :key="key">
                                    <button type="button" @click="$refs.addIconInput.value = svg" class="p-2 bg-slate-900 rounded-xl hover:ring-2 hover:ring-[#0059bb] transition-all flex items-center justify-center">
                                        <div x-html="svg"></div>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- Mode 3: Custom SVG / URL -->
                        <div x-show="addLogoMode === 'custom' || addLogoMode === 'presets'" class="space-y-2" x-cloak>
                            <label class="text-[11px] text-slate-500 block">SVG Code or Image URL:</label>
                            <textarea x-ref="addIconInput" name="icon_svg" rows="3" placeholder='<svg class="w-auto h-7" viewBox="0 0 36 24">...</svg>' class="w-full p-3 bg-slate-900 text-white rounded-xl text-xs font-mono"></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                        <button type="button" @click="showAddModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold text-xs rounded-xl">Cancel</button>
                        <button type="submit" class="px-5 py-2 bg-[#0059bb] text-white font-bold text-xs rounded-xl shadow">Save & Add Gateway</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL 2: EDIT PAYMENT METHOD -->
        <div x-show="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak>
            <div class="bg-white p-8 rounded-3xl border border-slate-200 max-w-xl w-full shadow-2xl space-y-6" @click.away="showEditModal = false">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-['Hanken_Grotesk'] text-lg font-bold text-slate-900">Edit Payment Gateway & Logo</h3>
                    <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 font-bold text-sm">✕</button>
                </div>

                <form method="POST" :action="'/admin/payment-methods/' + editMethod.id + '/update'" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-semibold text-slate-700 block mb-1">Gateway Name</label>
                            <input type="text" name="name" x-model="editMethod.name" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900">
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-slate-700 block mb-1">Category</label>
                            <select name="category" x-model="editMethod.category" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900">
                                <option value="cards">Credit & Debit Cards</option>
                                <option value="wallets">E-Wallets & Buy-Now-Pay-Later</option>
                                <option value="mobile">Mobile Money</option>
                                <option value="crypto">Cryptocurrency</option>
                                <option value="banking">Bank Transfer</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-slate-700 block mb-1">Sort Order Position</label>
                            <input type="number" name="sort_order" x-model="editMethod.sort_order" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900">
                        </div>

                        <div class="flex items-center pt-5">
                            <label class="relative inline-flex items-center cursor-pointer gap-2">
                                <input type="checkbox" name="show_in_footer" value="1" :checked="editMethod.show_in_footer" class="sr-only peer">
                                <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#0059bb]"></div>
                                <span class="text-xs font-bold text-slate-700">Show in Footer</span>
                            </label>
                        </div>
                    </div>

                    <!-- Logo Choice Options -->
                    <div class="space-y-3 pt-2 border-t border-slate-100">
                        <label class="text-xs font-bold text-slate-800 block">Change Logo / Icon:</label>
                        <div class="flex items-center gap-2 border-b border-slate-100 pb-2">
                            <button type="button" @click="editLogoMode = 'upload'" :class="editLogoMode === 'upload' ? 'bg-[#0059bb] text-white font-bold' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-lg text-xs transition-all">
                                📁 Upload New Logo File
                            </button>
                            <button type="button" @click="editLogoMode = 'presets'" :class="editLogoMode === 'presets' ? 'bg-[#0059bb] text-white font-bold' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-lg text-xs transition-all">
                                🎨 Presets
                            </button>
                            <button type="button" @click="editLogoMode = 'custom'" :class="editLogoMode === 'custom' ? 'bg-[#0059bb] text-white font-bold' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-lg text-xs transition-all">
                                💻 Custom SVG Code / URL
                            </button>
                        </div>

                        <!-- Mode 1: File Upload -->
                        <div x-show="editLogoMode === 'upload'" class="space-y-2">
                            <label class="text-[11px] text-slate-500 block">Upload Logo Image File (SVG, PNG, JPG, WebP):</label>
                            <input type="file" name="logo_file" accept="image/*" class="w-full text-xs text-slate-600 p-2 bg-slate-50 border border-slate-200 rounded-xl file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#0059bb] file:text-white">
                        </div>

                        <!-- Mode 2: Presets -->
                        <div x-show="editLogoMode === 'presets'" class="space-y-2" x-cloak>
                            <label class="text-[11px] text-slate-500 block">Click a preset vector icon to apply:</label>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="(svg, key) in presets" :key="key">
                                    <button type="button" @click="editMethod.icon_svg = svg" class="p-2 bg-slate-900 rounded-xl hover:ring-2 hover:ring-[#0059bb] transition-all flex items-center justify-center">
                                        <div x-html="svg"></div>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- Mode 3: Custom SVG / URL -->
                        <div x-show="editLogoMode === 'custom' || editLogoMode === 'presets'" class="space-y-2" x-cloak>
                            <label class="text-[11px] text-slate-500 block">SVG Code or Image URL Path:</label>
                            <textarea name="icon_svg" x-model="editMethod.icon_svg" rows="3" class="w-full p-3 bg-slate-900 text-white rounded-xl text-xs font-mono"></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                        <button type="button" @click="showEditModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold text-xs rounded-xl">Cancel</button>
                        <button type="submit" class="px-5 py-2 bg-[#0059bb] text-white font-bold text-xs rounded-xl shadow">Update Gateway</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
        function paymentGatewayManager() {
            return {
                categoryFilter: 'all',
                showAddModal: false,
                showEditModal: false,
                addLogoMode: 'upload',
                editLogoMode: 'upload',
                editMethod: { id: '', name: '', category: 'cards', sort_order: 1, show_in_footer: true, icon_svg: '' },
                presets: {
                    card: '<svg class="w-auto h-7" viewBox="0 0 36 24" fill="none"><rect width="36" height="24" rx="4" fill="#1E293B"/><rect y="6" width="36" height="4" fill="#475569"/><rect x="4" y="14" width="8" height="4" rx="1" fill="#38BDF8"/></svg>',
                    mobile: '<svg class="w-auto h-7" viewBox="0 0 36 24" fill="none"><rect width="36" height="24" rx="4" fill="#059669"/><path d="M12 7h12v10H12z" fill="#ffffff"/><circle cx="18" cy="12" r="3" fill="#059669"/></svg>',
                    crypto: '<svg class="w-auto h-7" viewBox="0 0 36 24" fill="none"><rect width="36" height="24" rx="4" fill="#F59E0B"/><path d="M18 6l6 6-6 6-6-6 6-6z" fill="#ffffff"/></svg>',
                    bank: '<svg class="w-auto h-7" viewBox="0 0 36 24" fill="none"><rect width="36" height="24" rx="4" fill="#1E293B"/><path d="M18 6L8 11v2h20v-2L18 6zm-8 8v5h2v-5h-2zm5 0v5h2v-5h-2zm5 0v5h2v-5h-2zm-12 6v2h20v-2H8z" fill="#94A3B8"/></svg>',
                    wallet: '<svg class="w-auto h-7" viewBox="0 0 36 24" fill="none"><rect width="36" height="24" rx="4" fill="#2563EB"/><rect x="6" y="8" width="24" height="12" rx="2" fill="#ffffff"/><circle cx="24" cy="14" r="2" fill="#2563EB"/></svg>'
                }
            };
        }
    </script>
</x-admin-layout>
