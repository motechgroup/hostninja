<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-200 pb-6">
        <div>
            <div class="flex items-center gap-2 text-xs text-[#0059bb] font-bold uppercase tracking-widest mb-1">
                <a href="{{ route('admin.registrars') }}" class="hover:underline">Integrations</a>
                <span>/</span>
                <span>API Audit Logs</span>
            </div>
            <h1 class="font-['Hanken_Grotesk'] text-3xl font-extrabold text-slate-900">Registrar API Logs</h1>
            <p class="text-xs text-slate-600 font-medium mt-1">Real-time audit log of all domain registrar API requests, responses, HTTP status codes, and latency.</p>
        </div>

        <div class="flex items-center gap-3">
            <button wire:click="clearLogs" onclick="confirm('Clear all registrar logs?') || event.stopImmediatePropagation()" class="px-4 py-2.5 bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold rounded-xl transition-all shadow flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">delete_sweep</span>
                <span>Clear All Logs</span>
            </button>
        </div>
    </div>

    <!-- Filters & Search Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4 text-xs font-bold">
        <div class="relative w-full md:w-96">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by action, endpoint, or error message..." class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0059bb]">
        </div>

        <div class="flex items-center gap-3 w-full md:w-auto">
            <select wire:model.live="selectedRegistrar" class="bg-slate-50 border border-slate-300 rounded-xl px-3 py-2.5 text-slate-800 focus:outline-none">
                <option value="">All Registrars</option>
                @foreach($registrars as $r)
                    <option value="{{ $r->id }}">{{ $r->name }}</option>
                @endforeach
            </select>

            <select wire:model.live="selectedStatus" class="bg-slate-50 border border-slate-300 rounded-xl px-3 py-2.5 text-slate-800 focus:outline-none">
                <option value="">All HTTP Statuses</option>
                <option value="200">200 OK (Success)</option>
                <option value="error">Errors / Failures</option>
            </select>
        </div>
    </div>

    <!-- Logs Data Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-700 font-extrabold uppercase tracking-wider font-['Hanken_Grotesk']">
                    <tr>
                        <th class="p-4">Timestamp</th>
                        <th class="p-4">Registrar / Driver</th>
                        <th class="p-4">Action</th>
                        <th class="p-4">HTTP Status</th>
                        <th class="p-4">Latency</th>
                        <th class="p-4">Payload Details</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-800">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="p-4 font-['JetBrains_Mono'] text-[11px] text-slate-500 whitespace-nowrap">
                                {{ $log->created_at->format('Y-m-d H:i:s') }}
                            </td>
                            <td class="p-4">
                                <span class="font-bold text-slate-900 block">{{ $log->registrar?->name ?? 'Custom Driver' }}</span>
                                <span class="text-[10px] text-slate-400 font-['JetBrains_Mono']">{{ class_basename($log->driver) }}</span>
                            </td>
                            <td class="p-4 font-bold text-[#0059bb]">
                                {{ $log->action }}
                            </td>
                            <td class="p-4">
                                @if($log->http_status === 200)
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                        200 OK
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-700 border border-rose-200">
                                        {{ $log->http_status }} FAIL
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 font-['JetBrains_Mono'] text-[11px] text-slate-600">
                                {{ $log->execution_time_ms }} ms
                            </td>
                            <td class="p-4 max-w-xs truncate font-['JetBrains_Mono'] text-[11px] text-slate-500">
                                {{ json_encode($log->request_payload) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-12 text-center text-slate-400 font-medium">
                                No registrar API logs recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
