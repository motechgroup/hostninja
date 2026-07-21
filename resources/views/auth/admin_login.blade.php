<x-app-layout>
    <x-slot name="title">HostNinja | Admin Console Login</x-slot>

    <div class="min-h-[75vh] flex items-center justify-center py-12 px-6">
        <div class="w-full max-w-md bg-white p-8 rounded-3xl border border-slate-200 shadow-2xl space-y-6">
            <div class="text-center space-y-2">
                <div class="w-12 h-12 bg-slate-900 rounded-2xl mx-auto flex items-center justify-center text-amber-500 shadow-lg">
                    <span class="material-symbols-outlined text-2xl">admin_panel_settings</span>
                </div>
                <h1 class="font-['Hanken_Grotesk'] text-2xl font-extrabold text-slate-900">Admin Console Portal</h1>
                <p class="text-xs text-slate-500">Authorized personnel only. Authenticate with system root credentials.</p>
            </div>

            @if($errors->any())
                <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-700 text-xs font-bold space-y-1">
                    @foreach($errors->all() as $err)
                        <div>• {{ $err }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="text-xs font-semibold text-slate-700 block mb-1">Admin Email Address</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm">mail</span>
                        <input type="email" name="email" value="admin@hostninja.cloud" required placeholder="admin@hostninja.cloud" class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#0059bb]">
                    </div>
                </div>

                <div>
                    <label class="text-xs font-semibold text-slate-700 block mb-1">Security Password</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm">lock</span>
                        <input type="password" name="password" value="password" required placeholder="••••••••" class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#0059bb]">
                    </div>
                </div>

                <button type="submit" class="w-full py-3.5 bg-slate-900 hover:bg-[#0059bb] text-white font-bold text-xs rounded-xl shadow-lg transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-sm">vpn_key</span>
                    <span>Sign In to Admin Console</span>
                </button>
            </form>

            <div class="pt-4 border-t border-slate-100 text-center space-y-3">
                <p class="text-[11px] text-slate-400">Testing Sandbox Quick Access:</p>
                <a href="{{ route('auth.quick', 'admin') }}" class="inline-block px-4 py-2 bg-amber-500/10 text-amber-700 hover:bg-amber-500/20 font-bold text-xs rounded-xl border border-amber-500/20 transition-all">
                    ⚡ Instant Admin Sandbox Login
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
