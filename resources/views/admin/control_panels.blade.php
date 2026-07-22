<x-admin-layout>
    <x-slot name="title">HostNinja Admin | Supported Control Panels & Logo Manager</x-slot>

    <div class="space-y-8" x-data="controlPanelManager()">
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 text-xs font-bold flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Header -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1 class="font-['Hanken_Grotesk'] text-2xl font-extrabold text-slate-900">Supported Control Panels & Logo Manager</h1>
                <p class="text-xs text-slate-500 mt-1">Manage control panel logos, official website links, descriptions, and homepage showcase visibility.</p>
            </div>
            <div class="flex gap-3">
                <button type="button" @click="showAddModal = true" class="px-4 py-2.5 bg-[#0059bb] hover:bg-blue-600 text-white font-bold text-xs rounded-xl shadow transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">add</span>
                    <span>Add New Control Panel</span>
                </button>
            </div>
        </div>

        <!-- Metrics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="glass-card p-6 rounded-2xl border border-slate-200">
                <div class="flex items-center justify-between">
                    <span class="text-slate-500 text-xs font-semibold">Total Configured</span>
                    <div class="p-2 bg-blue-50 text-[#0059bb] rounded-lg">
                        <span class="material-symbols-outlined text-lg">grid_view</span>
                    </div>
                </div>
                <div class="font-['Hanken_Grotesk'] text-2xl font-extrabold text-slate-900 mt-2">{{ count($controlPanels) }}</div>
                <p class="text-[10px] text-slate-400 mt-1">Hosting control panels & tools</p>
            </div>

            <div class="glass-card p-6 rounded-2xl border border-slate-200">
                <div class="flex items-center justify-between">
                    <span class="text-slate-500 text-xs font-semibold">Active & Enabled</span>
                    <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg">
                        <span class="material-symbols-outlined text-lg">check_circle</span>
                    </div>
                </div>
                <div class="font-['Hanken_Grotesk'] text-2xl font-extrabold text-emerald-600 mt-2">{{ $controlPanels->where('enabled', true)->count() }}</div>
                <p class="text-[10px] text-slate-400 mt-1">Visible on homepage section</p>
            </div>

            <div class="glass-card p-6 rounded-2xl border border-slate-200">
                <div class="flex items-center justify-between">
                    <span class="text-slate-500 text-xs font-semibold">Featured Panels</span>
                    <div class="p-2 bg-amber-50 text-amber-600 rounded-lg">
                        <span class="material-symbols-outlined text-lg">star</span>
                    </div>
                </div>
                <div class="font-['Hanken_Grotesk'] text-2xl font-extrabold text-amber-600 mt-2">{{ $controlPanels->where('featured', true)->count() }}</div>
                <p class="text-[10px] text-slate-400 mt-1">Highlighted at top of grid</p>
            </div>

            <div class="glass-card p-6 rounded-2xl border border-slate-200">
                <div class="flex items-center justify-between">
                    <span class="text-slate-500 text-xs font-semibold">Showcase Interactive Grid</span>
                    <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg">
                        <span class="material-symbols-outlined text-lg">touch_app</span>
                    </div>
                </div>
                <div class="font-['Hanken_Grotesk'] text-xl font-extrabold text-emerald-600 mt-2">
                    LOGO GRID
                </div>
                <p class="text-[10px] text-slate-400 mt-1">Click logo to view modal</p>
            </div>
        </div>

        <!-- Control Panels Manager Grid -->
        <div class="glass-card p-6 rounded-3xl border border-slate-200 space-y-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
                <div>
                    <h3 class="font-['Hanken_Grotesk'] text-lg font-bold text-slate-900">Hosting Control Panels & Logos ({{ count($controlPanels) }})</h3>
                    <p class="text-xs text-slate-500">Upload custom logos, edit provider URLs, manage descriptions, and toggle homepage visibility.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($controlPanels as $cp)
                    <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-sm hover:border-[#0059bb]/50 transition-all space-y-3 flex flex-col justify-between">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="p-2 bg-slate-950 rounded-xl border border-slate-800 shadow-inner flex items-center justify-center min-h-[48px]">
                                    {!! $cp->logo_html !!}
                                </div>
                                
                                <div class="flex items-center gap-1">
                                    @if($cp->featured)
                                        <span class="text-[9px] font-bold text-amber-700 bg-amber-100 px-2 py-0.5 rounded">FEATURED</span>
                                    @endif
                                    <span class="text-[10px] font-['JetBrains_Mono'] font-bold text-slate-400 bg-slate-100 px-2 py-0.5 rounded">#{{ $cp->display_order }}</span>
                                </div>
                            </div>

                            <div>
                                <h4 class="font-bold text-xs text-slate-900 flex items-center justify-between">
                                    <span>{{ $cp->name }}</span>
                                    @if($cp->official_url)
                                        <a href="{{ $cp->official_url }}" target="_blank" class="text-[10px] text-[#0059bb] hover:underline font-bold" title="Visit official site">↗ Site</a>
                                    @endif
                                </h4>
                                <p class="text-[11px] text-slate-500 line-clamp-2 mt-1">{{ $cp->description }}</p>
                            </div>
                        </div>

                        <div class="space-y-2 pt-2 border-t border-slate-100">
                            <div class="flex items-center justify-between gap-1">
                                <!-- Featured Toggle -->
                                <form method="POST" action="{{ route('admin.control-panels.toggle-featured', $cp->id) }}">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1 rounded-lg text-[10px] font-bold transition-colors flex items-center gap-1 {{ $cp->featured ? 'bg-amber-100 text-amber-800 hover:bg-amber-200' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}" title="Toggle Featured State">
                                        <span class="material-symbols-outlined text-[12px]">{{ $cp->featured ? 'star' : 'star_outline' }}</span>
                                        <span>{{ $cp->featured ? 'Featured' : 'Standard' }}</span>
                                    </button>
                                </form>

                                <!-- Enabled Toggle -->
                                <form method="POST" action="{{ route('admin.control-panels.toggle', $cp->id) }}">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1 rounded-lg text-[10px] font-bold transition-colors {{ $cp->enabled ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}" title="Toggle Visibility">
                                        {{ $cp->enabled ? 'Active' : 'Disabled' }}
                                    </button>
                                </form>
                            </div>

                            <div class="flex items-center justify-between pt-1 border-t border-slate-50">
                                <button type="button" @click="editPanel = { id: '{{ $cp->id }}', name: '{{ addslashes($cp->name) }}', description: '{{ addslashes($cp->description ?? '') }}', official_url: '{{ addslashes($cp->official_url ?? '') }}', display_order: {{ $cp->display_order }}, featured: {{ $cp->featured ? 'true' : 'false' }}, logo: '{{ addslashes($cp->logo ?? '') }}' }; editLogoMode = 'upload'; showEditModal = true" class="text-[11px] text-[#0059bb] hover:underline font-bold flex items-center gap-0.5">
                                    <span class="material-symbols-outlined text-xs">edit</span>
                                    <span>Manage Logo & Details</span>
                                </button>
                                
                                <form method="POST" action="{{ route('admin.control-panels.delete', $cp->id) }}" onsubmit="return confirm('Delete control panel {{ $cp->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-[11px] text-slate-400 hover:text-rose-600 font-semibold flex items-center gap-0.5">
                                        <span class="material-symbols-outlined text-xs">delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- MODAL 1: ADD NEW CONTROL PANEL -->
        <div x-show="showAddModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak>
            <div class="bg-white p-8 rounded-3xl border border-slate-200 max-w-xl w-full shadow-2xl space-y-6" @click.away="showAddModal = false">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-['Hanken_Grotesk'] text-lg font-bold text-slate-900">Add New Supported Control Panel</h3>
                    <button type="button" @click="showAddModal = false" class="text-slate-400 hover:text-slate-600 font-bold text-sm">✕</button>
                </div>

                <form method="POST" action="{{ route('admin.control-panels.create') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-semibold text-slate-700 block mb-1">Control Panel Name</label>
                            <input type="text" name="name" placeholder="e.g. WHMCS, Proxmox VE" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900">
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-slate-700 block mb-1">Unique Slug</label>
                            <input type="text" name="slug" placeholder="e.g. proxmox-ve" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-slate-900">
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-xs font-semibold text-slate-700 block mb-1">Short Description (1-2 lines)</label>
                            <textarea name="description" rows="2" placeholder="Brief description of features and integration." class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900"></textarea>
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-slate-700 block mb-1">Official Provider URL</label>
                            <input type="url" name="official_url" placeholder="https://..." class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900">
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-slate-700 block mb-1">Display Order Position</label>
                            <input type="number" name="display_order" value="11" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900">
                        </div>

                        <div class="md:col-span-2 pt-2">
                            <label class="relative inline-flex items-center cursor-pointer gap-2">
                                <input type="checkbox" name="featured" value="1" class="sr-only peer">
                                <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#0059bb]"></div>
                                <span class="text-xs font-bold text-slate-700">Mark as Featured Control Panel</span>
                            </label>
                        </div>
                    </div>

                    <!-- Logo Manager Options -->
                    <div class="space-y-3 pt-2 border-t border-slate-100">
                        <label class="text-xs font-bold text-slate-800 block">Manage Logo / Icon:</label>
                        <div class="flex items-center gap-2 border-b border-slate-100 pb-2">
                            <button type="button" @click="addLogoMode = 'upload'" :class="addLogoMode === 'upload' ? 'bg-[#0059bb] text-white font-bold' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-lg text-xs transition-all">
                                📁 Upload Logo File
                            </button>
                            <button type="button" @click="addLogoMode = 'presets'" :class="addLogoMode === 'presets' ? 'bg-[#0059bb] text-white font-bold' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-lg text-xs transition-all">
                                🎨 Presets
                            </button>
                            <button type="button" @click="addLogoMode = 'custom'" :class="addLogoMode === 'custom' ? 'bg-[#0059bb] text-white font-bold' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-lg text-xs transition-all">
                                💻 Custom SVG / URL
                            </button>
                        </div>

                        <!-- Mode 1: File Upload -->
                        <div x-show="addLogoMode === 'upload'" class="space-y-2">
                            <label class="text-[11px] text-slate-500 block">Upload Image File (SVG, PNG, JPG, WebP - max 2MB):</label>
                            <input type="file" name="logo_file" accept="image/*" class="w-full text-xs text-slate-600 p-2 bg-slate-50 border border-slate-200 rounded-xl file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#0059bb] file:text-white">
                        </div>

                        <!-- Mode 2: Presets -->
                        <div x-show="addLogoMode === 'presets'" class="space-y-2" x-cloak>
                            <label class="text-[11px] text-slate-500 block">Click a preset vector icon:</label>
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
                            <label class="text-[11px] text-slate-500 block">SVG Code or Image URL Path:</label>
                            <textarea x-ref="addIconInput" name="logo" rows="3" placeholder='<svg class="h-10 w-auto" viewBox="0 0 120 32">...</svg>' class="w-full p-3 bg-slate-900 text-white rounded-xl text-xs font-mono"></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                        <button type="button" @click="showAddModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold text-xs rounded-xl">Cancel</button>
                        <button type="submit" class="px-5 py-2 bg-[#0059bb] text-white font-bold text-xs rounded-xl shadow">Save & Add Panel</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL 2: EDIT CONTROL PANEL & LOGO -->
        <div x-show="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak>
            <div class="bg-white p-8 rounded-3xl border border-slate-200 max-w-xl w-full shadow-2xl space-y-6" @click.away="showEditModal = false">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-['Hanken_Grotesk'] text-lg font-bold text-slate-900">Manage Control Panel & Logo</h3>
                    <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 font-bold text-sm">✕</button>
                </div>

                <form method="POST" :action="'/admin/control-panels/' + editPanel.id + '/update'" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-semibold text-slate-700 block mb-1">Control Panel Name</label>
                            <input type="text" name="name" x-model="editPanel.name" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900">
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-slate-700 block mb-1">Official Provider URL</label>
                            <input type="url" name="official_url" x-model="editPanel.official_url" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900">
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-xs font-semibold text-slate-700 block mb-1">Short Description</label>
                            <textarea name="description" x-model="editPanel.description" rows="2" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900"></textarea>
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-slate-700 block mb-1">Display Order Position</label>
                            <input type="number" name="display_order" x-model="editPanel.display_order" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900">
                        </div>

                        <div class="flex items-center pt-5">
                            <label class="relative inline-flex items-center cursor-pointer gap-2">
                                <input type="checkbox" name="featured" value="1" :checked="editPanel.featured" class="sr-only peer">
                                <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#0059bb]"></div>
                                <span class="text-xs font-bold text-slate-700">Mark as Featured</span>
                            </label>
                        </div>
                    </div>

                    <!-- Logo Choice Options -->
                    <div class="space-y-3 pt-2 border-t border-slate-100">
                        <label class="text-xs font-bold text-slate-800 block">Change / Update Logo:</label>
                        <div class="flex items-center gap-2 border-b border-slate-100 pb-2">
                            <button type="button" @click="editLogoMode = 'upload'" :class="editLogoMode === 'upload' ? 'bg-[#0059bb] text-white font-bold' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-lg text-xs transition-all">
                                📁 Upload New Image File
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
                                    <button type="button" @click="editPanel.logo = svg" class="p-2 bg-slate-900 rounded-xl hover:ring-2 hover:ring-[#0059bb] transition-all flex items-center justify-center">
                                        <div x-html="svg"></div>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- Mode 3: Custom SVG / URL -->
                        <div x-show="editLogoMode === 'custom' || editLogoMode === 'presets'" class="space-y-2" x-cloak>
                            <label class="text-[11px] text-slate-500 block">SVG Code or Image URL Path:</label>
                            <textarea name="logo" x-model="editPanel.logo" rows="3" class="w-full p-3 bg-slate-900 text-white rounded-xl text-xs font-mono"></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                        <button type="button" @click="showEditModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold text-xs rounded-xl">Cancel</button>
                        <button type="submit" class="px-5 py-2 bg-[#0059bb] text-white font-bold text-xs rounded-xl shadow">Update Panel</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
        function controlPanelManager() {
            return {
                showAddModal: false,
                showEditModal: false,
                addLogoMode: 'upload',
                editLogoMode: 'upload',
                editPanel: { id: '', name: '', description: '', official_url: '', display_order: 1, featured: false, logo: '' },
                presets: {
                    cpanel: '<svg class="h-10 w-auto" viewBox="0 0 120 32" fill="none"><rect width="120" height="32" rx="6" fill="#FF6C2C"/><text x="12" y="21" fill="#FFFFFF" font-weight="900" font-family="sans-serif" font-size="16">cPanel</text><text x="72" y="21" fill="#1E293B" font-weight="900" font-family="sans-serif" font-size="14">& WHM</text></svg>',
                    plesk: '<svg class="h-10 w-auto" viewBox="0 0 120 32" fill="none"><rect width="120" height="32" rx="6" fill="#52B0E7"/><path d="M16 8l10 8-10 8V8z" fill="#FFFFFF"/><text x="36" y="21" fill="#FFFFFF" font-weight="900" font-family="sans-serif" font-size="16">Plesk</text></svg>',
                    directadmin: '<svg class="h-10 w-auto" viewBox="0 0 120 32" fill="none"><rect width="120" height="32" rx="6" fill="#2B3990"/><path d="M12 8h12v4H12zM12 14h16v4H12zM12 20h8v4H12z" fill="#00AEEF"/><text x="34" y="21" fill="#FFFFFF" font-weight="800" font-family="sans-serif" font-size="13">DirectAdmin</text></svg>',
                    cloudpanel: '<svg class="h-10 w-auto" viewBox="0 0 120 32" fill="none"><rect width="120" height="32" rx="6" fill="#0EA5E9"/><path d="M12 18a4 4 0 018 0h6a3 3 0 00-3-3 4 4 0 00-7-2 4 4 0 00-4 5z" fill="#FFFFFF"/><text x="34" y="21" fill="#FFFFFF" font-weight="800" font-family="sans-serif" font-size="13">CloudPanel</text></svg>',
                    whmcs: '<svg class="h-10 w-auto" viewBox="0 0 120 32" fill="none"><rect width="120" height="32" rx="6" fill="#1E293B"/><text x="12" y="21" fill="#38BDF8" font-weight="900" font-family="sans-serif" font-size="16">WHMCS</text></svg>'
                }
            };
        }
    </script>
</x-admin-layout>
