<x-admin-layout>
    <x-slot name="title">HostNinja Admin | Server Nodes</x-slot>

    <div class="space-y-8">
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 text-xs font-bold flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-['Hanken_Grotesk'] text-2xl font-extrabold text-slate-900">Server Nodes & Hosted cPanel Accounts</h1>
                <p class="text-xs text-slate-500 mt-1">Real-time node telemetry and account management controls.</p>
            </div>
            <span class="px-3.5 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-['JetBrains_Mono'] font-bold border border-emerald-200">
                Live Nodes: {{ count($servers) }}
            </span>
        </div>

        <!-- Infrastructure Nodes Table -->
        <section class="glass-card rounded-3xl overflow-hidden border border-slate-200 shadow-sm p-6 space-y-4">
            <h4 class="font-['Hanken_Grotesk'] text-lg font-bold text-slate-900">Cluster Nodes Health</h4>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead class="bg-slate-50 border-b border-slate-200 font-['JetBrains_Mono'] text-slate-500 uppercase">
                        <tr>
                            <th class="px-6 py-4">Node Name</th>
                            <th class="px-6 py-4">IP Address</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Active Accounts</th>
                            <th class="px-6 py-4">Disk Usage</th>
                            <th class="px-6 py-4">CPU Load</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($servers as $srv)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 font-bold text-slate-900">{{ $srv->name }}</td>
                                <td class="px-6 py-4 font-['JetBrains_Mono'] text-slate-600">{{ $srv->ip_address }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold badge-active uppercase">{{ $srv->status }}</span>
                                </td>
                                <td class="px-6 py-4 font-semibold text-slate-800">{{ $srv->active_accounts }} / {{ $srv->max_accounts }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-24 bg-slate-200 h-1.5 rounded-full overflow-hidden">
                                            <div class="bg-[#0059bb] h-1.5 rounded-full" style="width: {{ $srv->disk_usage_percent }}%"></div>
                                        </div>
                                        <span class="font-bold text-slate-700">{{ $srv->disk_usage_percent }}%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-bold text-emerald-600">{{ $srv->cpu_usage_percent }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Hosted Accounts Control Panel -->
        <section class="glass-card rounded-3xl overflow-hidden border border-slate-200 shadow-sm p-6 space-y-4">
            <h4 class="font-['Hanken_Grotesk'] text-lg font-bold text-slate-900">cPanel Instance Management (Suspend / Reactivate / Terminate)</h4>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-500 font-['JetBrains_Mono']">
                            <th class="py-3 px-4">Client</th>
                            <th class="py-3 px-4">Domain</th>
                            <th class="py-3 px-4">Package</th>
                            <th class="py-3 px-4">Server</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($services as $svc)
                            <tr>
                                <td class="py-3 px-4 font-bold text-slate-900">{{ $svc->user->name ?? 'User' }}</td>
                                <td class="py-3 px-4 font-mono text-[#0059bb] font-bold">{{ $svc->domain_name }}</td>
                                <td class="py-3 px-4 text-slate-700">{{ $svc->hostingPlan->name }}</td>
                                <td class="py-3 px-4 text-slate-500">{{ $svc->server->name ?? 'Nairobi Node' }}</td>
                                <td class="py-3 px-4">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold badge-active uppercase">{{ $svc->status }}</span>
                                </td>
                                <td class="py-3 px-4 text-right flex items-center justify-end gap-2">
                                    <form method="POST" action="{{ route('admin.services.cpanel-credentials', $svc->id) }}">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 bg-blue-50 text-[#0059bb] hover:bg-blue-100 font-bold text-[10px] rounded-lg transition-colors flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[12px]">key</span>
                                            <span>Send Credentials</span>
                                        </button>
                                    </form>

                                    @if($svc->status === 'active')
                                        <form method="POST" action="{{ route('admin.services.suspend', $svc->id) }}">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 bg-amber-500 text-white font-bold text-[10px] rounded-lg hover:bg-amber-600">
                                                Suspend
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.services.unsuspend', $svc->id) }}">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 bg-emerald-600 text-white font-bold text-[10px] rounded-lg hover:bg-emerald-500">
                                                Reactivate
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('admin.services.terminate', $svc->id) }}">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Terminate account {{ $svc->domain_name }}?')" class="px-3 py-1 bg-rose-600 text-white font-bold text-[10px] rounded-lg hover:bg-rose-500">
                                            Terminate
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-admin-layout>
