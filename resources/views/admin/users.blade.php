<x-admin-layout>
    <x-slot name="title">HostNinja Admin | Users & Accounts</x-slot>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-['Hanken_Grotesk'] text-2xl font-extrabold text-slate-900">User Accounts & Roles</h1>
                <p class="text-xs text-slate-500 mt-1">Manage registered client accounts, admin users, resellers, and support staff.</p>
            </div>
            <span class="px-3.5 py-1 bg-slate-900 text-white rounded-xl text-xs font-['JetBrains_Mono'] font-bold">Total Users: {{ count($users) }}</span>
        </div>

        <div class="glass-card p-6 rounded-3xl border border-slate-200">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-500 font-['JetBrains_Mono']">
                            <th class="py-3 px-4">User</th>
                            <th class="py-3 px-4">Role</th>
                            <th class="py-3 px-4">Company</th>
                            <th class="py-3 px-4">Phone</th>
                            <th class="py-3 px-4">Balance</th>
                            <th class="py-3 px-4 text-right">Registered</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($users as $u)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-4 px-4">
                                    <div class="font-bold text-slate-900 text-sm">{{ $u->name }}</div>
                                    <div class="text-xs text-slate-500 font-mono">{{ $u->email }}</div>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-[#0059bb]/10 text-[#0059bb] uppercase">{{ $u->role }}</span>
                                </td>
                                <td class="py-4 px-4 text-slate-700 font-semibold">{{ $u->company ?? 'N/A' }}</td>
                                <td class="py-4 px-4 text-slate-600 font-mono">{{ $u->phone ?? 'N/A' }}</td>
                                <td class="py-4 px-4 font-extrabold text-emerald-600">KES {{ number_format($u->balance, 2) }}</td>
                                <td class="py-4 px-4 text-right text-slate-500">{{ $u->created_at->format('M d, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
