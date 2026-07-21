<x-app-layout>
    <x-slot name="title">HostNinja | Authentication Portal</x-slot>

    <div class="min-h-screen flex items-center justify-center py-16 px-4 bg-[#f7f9fb]">
        <!-- Centered Login Card -->
        <div class="w-full max-w-[440px] bg-white rounded-3xl shadow-xl p-8 border border-slate-200/80 space-y-8">
            <!-- Brand Logo Header -->
            <div class="flex flex-col items-center gap-2">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-white shadow-lg" style="background-color: #0070ea !important;">
                    <span class="material-symbols-outlined text-2xl text-white">cloud_queue</span>
                </div>
                <span class="font-['Hanken_Grotesk'] text-2xl font-extrabold text-slate-900 tracking-tight">HostNinja</span>
            </div>

            <!-- Header Text -->
            <div class="space-y-1 text-center">
                <h2 class="font-['Hanken_Grotesk'] text-2xl font-extrabold text-slate-900">Welcome Back</h2>
                <p class="text-slate-500 text-xs font-medium">Enter your credentials to access your dashboard.</p>
            </div>

            <!-- Tab Switcher -->
            <div class="flex border-b border-slate-200">
                <a href="{{ route('login') }}" class="flex-1 py-3 text-center font-bold text-xs border-b-2 border-[#0059bb] text-slate-900 transition-colors">
                    Log In
                </a>
                <a href="{{ route('register') }}" class="flex-1 py-3 text-center font-semibold text-xs text-slate-400 hover:text-slate-900 transition-colors">
                    Sign Up
                </a>
            </div>

            <!-- Error Alert -->
            @if($errors->any())
                <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-bold flex items-center gap-2">
                    <span class="material-symbols-outlined text-rose-600 text-sm">error</span>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-5 text-left">
                @csrf
                <div class="space-y-4">
                    <!-- Email Input -->
                    <div>
                        <label class="font-['JetBrains_Mono'] text-[11px] font-bold text-slate-600 uppercase tracking-wider block mb-1.5">Email Address</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg">mail</span>
                            <input type="email" name="email" required value="{{ old('email', 'customer@hostninja.cloud') }}" placeholder="name@company.com" class="w-full pl-12 pr-4 py-3.5 border-transparent rounded-2xl text-xs font-bold text-slate-900 placeholder:text-slate-400 placeholder:font-normal focus:bg-white focus:border-[#0059bb] focus:ring-2 focus:ring-[#0059bb]/20 focus:outline-none transition-all" style="background-color: #f2f4f6 !important; color: #191c1e !important;">
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div>
                        <div class="flex justify-between items-center mb-1.5">
                            <label class="font-['JetBrains_Mono'] text-[11px] font-bold text-slate-600 uppercase tracking-wider">Password</label>
                            <a href="#" class="text-[#0059bb] font-['JetBrains_Mono'] text-[10px] font-bold hover:underline uppercase">Forgot?</a>
                        </div>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg">lock</span>
                            <input type="password" name="password" required value="password" placeholder="••••••••" class="w-full pl-12 pr-4 py-3.5 border-transparent rounded-2xl text-xs font-bold text-slate-900 placeholder:text-slate-400 placeholder:font-normal focus:bg-white focus:border-[#0059bb] focus:ring-2 focus:ring-[#0059bb]/20 focus:outline-none transition-all" style="background-color: #f2f4f6 !important; color: #191c1e !important;">
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="remember" name="remember" class="w-4 h-4 rounded text-[#0059bb] border-slate-300 focus:ring-0 cursor-pointer">
                        <label for="remember" class="text-xs font-medium text-slate-600 cursor-pointer">Remember me</label>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-4 rounded-2xl font-bold text-xs flex items-center justify-center gap-2 transition-all shadow-lg hover:opacity-90 group" style="background-color: #000000 !important; color: #ffffff !important;">
                    <span style="color: #ffffff !important;">Sign In to Portal</span>
                    <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform" style="color: #ffffff !important;">arrow_forward</span>
                </button>
            </form>

            <!-- 1-Click Quick Sandbox Login Switcher -->
            <div class="pt-5 border-t border-slate-100 space-y-3">
                <p class="text-[10px] font-['JetBrains_Mono'] font-bold text-slate-400 uppercase tracking-wider text-center">1-Click Quick Sandbox Login</p>
                <div class="grid grid-cols-2 gap-2 text-xs font-bold">
                    <a href="{{ route('auth.quick', 'customer') }}" class="p-3 rounded-2xl bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-900 text-center transition-colors">
                        👤 Customer
                    </a>
                    <a href="{{ route('auth.quick', 'admin') }}" class="p-3 rounded-2xl bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/30 text-amber-900 text-center transition-colors">
                        ⚡ Root Admin
                    </a>
                    <a href="{{ route('auth.quick', 'reseller') }}" class="p-3 rounded-2xl bg-blue-500/10 hover:bg-blue-500/20 border border-blue-500/30 text-[#0059bb] text-center transition-colors">
                        💼 Reseller
                    </a>
                    <a href="{{ route('auth.quick', 'agent') }}" class="p-3 rounded-2xl bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/30 text-emerald-900 text-center transition-colors">
                        🎧 Support Agent
                    </a>
                </div>
            </div>

            <!-- Footer Text -->
            <p class="text-center text-slate-500 text-xs pt-1">
                Facing issues? <a href="#" class="text-[#0059bb] font-bold hover:underline">Contact Support</a>
            </p>
        </div>
    </div>
</x-app-layout>
