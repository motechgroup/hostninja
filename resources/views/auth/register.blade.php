<x-app-layout>
    <x-slot name="title">HostNinja | Account Registration</x-slot>

    <div class="min-h-screen flex items-center justify-center py-16 px-4 bg-[#f7f9fb]">
        <!-- Centered Register Card -->
        <div class="w-full max-w-[440px] bg-white rounded-3xl shadow-xl p-8 border border-slate-200/80 space-y-8">
            <!-- Brand Logo Header -->
            <div class="flex flex-col items-center gap-2">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-500/20" style="background-color: #0070ea !important;">
                    <span class="material-symbols-outlined text-2xl text-white">cloud_queue</span>
                </div>
                <span class="font-['Hanken_Grotesk'] text-2xl font-extrabold text-slate-900 tracking-tight">HostNinja</span>
            </div>

            <!-- Header Text -->
            <div class="space-y-1 text-center">
                <h2 class="font-['Hanken_Grotesk'] text-2xl font-extrabold text-slate-900">Start Your Journey</h2>
                <p class="text-slate-500 text-xs font-medium">Create your free account to start deploying in under 60 seconds.</p>
            </div>

            <!-- Tab Switcher -->
            <div class="flex border-b border-slate-200">
                <a href="{{ route('login') }}" class="flex-1 py-3 text-center font-semibold text-xs text-slate-400 hover:text-slate-900 transition-colors">
                    Log In
                </a>
                <a href="{{ route('register') }}" class="flex-1 py-3 text-center font-bold text-xs border-b-2 border-[#0059bb] text-slate-900 transition-colors">
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

            <!-- Signup Form -->
            <form method="POST" action="{{ route('register') }}" class="space-y-4 text-left">
                @csrf
                <div class="space-y-3.5">
                    <div>
                        <label class="font-['JetBrains_Mono'] text-[11px] font-bold text-slate-600 uppercase tracking-wider block mb-1">Full Name</label>
                        <input type="text" name="name" required placeholder="Jane Doe" class="w-full px-4 py-3 border-transparent rounded-2xl text-xs font-bold text-slate-900 placeholder:text-slate-400 placeholder:font-normal focus:bg-white focus:border-[#0059bb] focus:ring-2 focus:ring-[#0059bb]/20 focus:outline-none transition-all" style="background-color: #f2f4f6 !important; color: #191c1e !important;">
                    </div>

                    <div>
                        <label class="font-['JetBrains_Mono'] text-[11px] font-bold text-slate-600 uppercase tracking-wider block mb-1">Email Address</label>
                        <input type="email" name="email" required placeholder="jane@company.com" class="w-full px-4 py-3 border-transparent rounded-2xl text-xs font-bold text-slate-900 placeholder:text-slate-400 placeholder:font-normal focus:bg-white focus:border-[#0059bb] focus:ring-2 focus:ring-[#0059bb]/20 focus:outline-none transition-all" style="background-color: #f2f4f6 !important; color: #191c1e !important;">
                    </div>

                    <div>
                        <label class="font-['JetBrains_Mono'] text-[11px] font-bold text-slate-600 uppercase tracking-wider block mb-1">Account Type</label>
                        <select name="role" class="w-full px-4 py-3 border-transparent rounded-2xl text-xs font-bold text-slate-900 focus:bg-white focus:border-[#0059bb] focus:ring-2 focus:ring-[#0059bb]/20 focus:outline-none transition-all" style="background-color: #f2f4f6 !important; color: #191c1e !important;">
                            <option value="customer">Customer Account (Web Hosting & Domains)</option>
                            <option value="reseller">Reseller Hosting Partner Account</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="font-['JetBrains_Mono'] text-[11px] font-bold text-slate-600 uppercase tracking-wider block mb-1">Password</label>
                            <input type="password" name="password" required placeholder="Min. 8 chars" class="w-full px-4 py-3 border-transparent rounded-2xl text-xs font-bold text-slate-900 focus:bg-white focus:border-[#0059bb] focus:ring-2 focus:ring-[#0059bb]/20 focus:outline-none transition-all" style="background-color: #f2f4f6 !important; color: #191c1e !important;">
                        </div>
                        <div>
                            <label class="font-['JetBrains_Mono'] text-[11px] font-bold text-slate-600 uppercase tracking-wider block mb-1">Confirm</label>
                            <input type="password" name="password_confirmation" required placeholder="Confirm" class="w-full px-4 py-3 border-transparent rounded-2xl text-xs font-bold text-slate-900 focus:bg-white focus:border-[#0059bb] focus:ring-2 focus:ring-[#0059bb]/20 focus:outline-none transition-all" style="background-color: #f2f4f6 !important; color: #191c1e !important;">
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full py-4 rounded-2xl font-bold text-xs flex items-center justify-center gap-2 transition-all shadow-lg hover:opacity-90 group" style="background-color: #000000 !important; color: #ffffff !important;">
                    <span style="color: #ffffff !important;">Create Free Account</span>
                    <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform" style="color: #ffffff !important;">arrow_forward</span>
                </button>
            </form>

            <p class="text-center text-slate-500 text-xs pt-1">
                Already have an account? <a href="{{ route('login') }}" class="text-[#0059bb] font-bold hover:underline">Log In</a>
            </p>
        </div>
    </div>
</x-app-layout>
