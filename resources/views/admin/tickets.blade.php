<x-admin-layout>
    <x-slot name="title">HostNinja Admin | Support Queue</x-slot>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-['Hanken_Grotesk'] text-2xl font-extrabold text-slate-900">Support Ticket Queue</h1>
                <p class="text-xs text-slate-500 mt-1">Review and answer customer support requests.</p>
            </div>
            <span class="px-3.5 py-1 bg-amber-100 text-amber-800 rounded-full text-xs font-['JetBrains_Mono'] font-bold border border-amber-200">
                Total Tickets: {{ count($tickets) }}
            </span>
        </div>

        <div class="space-y-6">
            @foreach($tickets as $t)
                <div class="glass-card p-6 rounded-3xl border border-slate-200 space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="font-mono text-xs font-bold text-[#0059bb] bg-[#0059bb]/10 px-2.5 py-1 rounded-lg border border-[#0059bb]/20">{{ $t->ticket_number }}</span>
                            <h3 class="font-['Hanken_Grotesk'] text-base font-bold text-slate-900">{{ $t->subject }}</h3>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold badge-active uppercase">{{ $t->status }}</span>
                    </div>

                    <div class="bg-white rounded-2xl p-4 border border-slate-200 space-y-3">
                        @foreach($t->messages as $msg)
                            <div class="p-3 rounded-xl text-xs {{ $msg->user_id === auth()->id() ? 'bg-blue-50 border border-blue-100 ml-4' : 'bg-slate-100 mr-4' }}">
                                <div class="flex justify-between mb-1">
                                    <span class="font-bold {{ $msg->user_id === auth()->id() ? 'text-[#0059bb]' : 'text-amber-700' }}">{{ $msg->user->name }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $msg->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-slate-800 leading-relaxed">{{ $msg->message }}</p>
                            </div>
                        @endforeach
                    </div>

                    <form method="POST" action="{{ route('dashboard.tickets.reply', $t->id) }}" class="flex gap-3">
                        @csrf
                        <input type="text" name="message" required placeholder="Type support staff reply..." class="flex-1 px-4 py-2 bg-white border border-slate-300 rounded-xl text-xs text-slate-900">
                        <button type="submit" class="px-5 py-2 bg-[#0059bb] text-white font-bold text-xs rounded-xl shadow">Send Reply</button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
</x-admin-layout>
