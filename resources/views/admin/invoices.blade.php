<x-admin-layout>
    <x-slot name="title">HostNinja Admin | Billing & Invoices</x-slot>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-['Hanken_Grotesk'] text-2xl font-extrabold text-slate-900">Billing & Invoices Audit</h1>
                <p class="text-xs text-slate-500 mt-1">Track financial transaction receipts, M-Pesa payments, and due dates.</p>
            </div>
            <span class="px-3.5 py-1 bg-slate-900 text-white rounded-xl text-xs font-['JetBrains_Mono'] font-bold">Total Invoices: {{ count($invoices) }}</span>
        </div>

        <div class="glass-card p-6 rounded-3xl border border-slate-200">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-500 font-['JetBrains_Mono']">
                            <th class="py-3 px-4">Invoice #</th>
                            <th class="py-3 px-4">Client</th>
                            <th class="py-3 px-4">Description</th>
                            <th class="py-3 px-4">Total Amount</th>
                            <th class="py-3 px-4">Due Date</th>
                            <th class="py-3 px-4 text-center">Status</th>
                            <th class="py-3 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($invoices as $inv)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-4 px-4 font-mono font-bold text-[#0059bb]">{{ $inv->invoice_number }}</td>
                                <td class="py-4 px-4 font-bold text-slate-900">{{ $inv->user->name ?? 'Client' }}</td>
                                <td class="py-4 px-4 text-slate-600 font-semibold">{{ $inv->description }}</td>
                                <td class="py-4 px-4 font-extrabold text-slate-900">KES {{ number_format($inv->total, 2) }}</td>
                                <td class="py-4 px-4 text-slate-500">{{ $inv->due_date->format('Y-m-d') }}</td>
                                <td class="py-4 px-4 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $inv->status === 'paid' ? 'badge-active' : 'badge-pending' }} uppercase">{{ $inv->status }}</span>
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <form method="POST" action="{{ route('admin.invoices.resend', $inv->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-[#0059bb] font-bold text-[11px] rounded-xl transition-colors inline-flex items-center gap-1">
                                            <span class="material-symbols-outlined text-xs">mail</span>
                                            <span>Resend Email</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
