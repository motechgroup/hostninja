<x-admin-layout>
    <x-slot name="title">HostNinja Admin | System Settings</x-slot>

    <div class="space-y-8" x-data="{ tab: 'seo', showMailPreview: false, previewSubject: '', previewBody: '' }">
        
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 text-xs font-bold flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Header -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1 class="font-['Hanken_Grotesk'] text-2xl font-extrabold text-slate-900">Platform Settings & Controls</h1>
                <p class="text-xs text-slate-500 mt-1">Manage SEO, Meta Keywords, M-Pesa Gateways, SMTP Servers, and Email Templates.</p>
            </div>
            <div class="flex gap-2 bg-slate-200 p-1 rounded-xl text-xs font-semibold overflow-x-auto">
                <button @click="tab = 'seo'" :class="tab === 'seo' ? 'bg-white text-slate-900 shadow' : 'text-slate-600'" class="px-4 py-2 rounded-lg transition-all whitespace-nowrap">SEO & Keywords</button>
                <button @click="tab = 'mail'" :class="tab === 'mail' ? 'bg-white text-slate-900 shadow' : 'text-slate-600'" class="px-4 py-2 rounded-lg transition-all whitespace-nowrap">SMTP & Mail Templates</button>
                <button @click="tab = 'gateways'" :class="tab === 'gateways' ? 'bg-white text-slate-900 shadow' : 'text-slate-600'" class="px-4 py-2 rounded-lg transition-all whitespace-nowrap">M-Pesa & Gateways</button>
                <button @click="tab = 'general'" :class="tab === 'general' ? 'bg-white text-slate-900 shadow' : 'text-slate-600'" class="px-4 py-2 rounded-lg transition-all whitespace-nowrap">General Branding</button>
            </div>
        </div>

        <!-- Settings Form -->
        <form method="POST" action="{{ route('admin.settings.update') }}" class="glass-card p-8 rounded-3xl border border-slate-200 space-y-6">
            @csrf

            <!-- TAB 1: SEO & KEYWORDS -->
            <div x-show="tab === 'seo'" class="space-y-6">
                <div class="flex justify-between items-center border-b border-slate-200 pb-3">
                    <h3 class="font-['Hanken_Grotesk'] text-lg font-bold text-slate-900">Search Engine Optimization (SEO) & Meta Keywords</h3>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">INDEXING ACTIVE</span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="text-xs font-semibold text-slate-700 block mb-1">Global Meta Title</label>
                        <input type="text" name="seo_title" value="{{ $settings['seo_title'] ?? 'HostNinja | Lightning Fast Cloud Hosting & Domains' }}" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900">
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-xs font-semibold text-slate-700 block mb-1">Meta Description</label>
                        <textarea name="seo_description" rows="3" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900">{{ $settings['seo_description'] ?? 'HostNinja provides enterprise-grade cloud hosting, NVMe SSD speed, domain registration, M-Pesa STK push integration, and 24/7 technical support.' }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-xs font-semibold text-slate-700 block mb-1">Meta Keywords (Comma-Separated)</label>
                        <textarea name="seo_keywords" rows="2" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-slate-900">{{ $settings['seo_keywords'] ?? 'cloud hosting, nvme hosting, domain registration, cpanel hosting, mpesa web hosting kenya, hostninja, web hosting nairobi' }}</textarea>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-slate-700 block mb-1">Google Analytics / Tag Manager Measurement ID</label>
                        <input type="text" name="google_analytics_id" value="{{ $settings['google_analytics_id'] ?? 'G-HN998218XX' }}" placeholder="G-XXXXXXXXXX" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900">
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-slate-700 block mb-1">OpenGraph Social Banner URL</label>
                        <input type="text" name="seo_og_image" value="{{ $settings['seo_og_image'] ?? 'https://hostninja.cloud/assets/og-banner.png' }}" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900">
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-xs font-semibold text-slate-700 block mb-1">Robots.txt Content</label>
                        <textarea name="robots_txt" rows="3" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-slate-900">{{ $settings['robots_txt'] ?? "User-agent: *\nAllow: /\nDisallow: /admin\nSitemap: https://hostninja.cloud/sitemap.xml" }}</textarea>
                    </div>
                </div>
            </div>

            <!-- TAB 2: SMTP & MAIL TEMPLATES -->
            <div x-show="tab === 'mail'" class="space-y-6" x-cloak>
                <div class="flex justify-between items-center border-b border-slate-200 pb-3">
                    <h3 class="font-['Hanken_Grotesk'] text-lg font-bold text-slate-900">SMTP Server & Notification Email Templates</h3>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700">SMTP ACTIVE</span>
                </div>

                <!-- SMTP Config Box -->
                <div class="p-6 bg-slate-50 rounded-2xl border border-slate-200 space-y-4">
                    <div class="flex items-center justify-between">
                        <h4 class="font-bold text-xs text-slate-900">SMTP Server Credentials</h4>
                        <button type="button" onclick="document.getElementById('test_smtp_modal').classList.remove('hidden')" class="px-3 py-1.5 bg-[#0059bb] hover:bg-blue-600 text-white font-bold text-xs rounded-xl shadow transition-all flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-sm">send</span>
                            <span>Test SMTP Connection</span>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="text-[11px] font-semibold text-slate-700 block mb-1">SMTP Host</label>
                            <input type="text" name="smtp_host" value="{{ $settings['smtp_host'] ?? 'smtp.hostninja.cloud' }}" placeholder="mail.yourdomain.com" class="w-full p-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-900">
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-slate-700 block mb-1">SMTP Port</label>
                            <input type="text" name="smtp_port" value="{{ $settings['smtp_port'] ?? '587' }}" placeholder="587 or 465" class="w-full p-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-900">
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-slate-700 block mb-1">Encryption Protocol</label>
                            <select name="smtp_encryption" class="w-full p-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-900">
                                <option value="tls" {{ ($settings['smtp_encryption'] ?? 'tls') === 'tls' ? 'selected' : '' }}>TLS (STARTTLS)</option>
                                <option value="ssl" {{ ($settings['smtp_encryption'] ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-slate-700 block mb-1">SMTP Username</label>
                            <input type="text" name="smtp_username" value="{{ $settings['smtp_username'] ?? 'notifications@hostninja.cloud' }}" placeholder="user@domain.com" class="w-full p-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-900">
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-slate-700 block mb-1">SMTP Password</label>
                            <input type="password" name="smtp_password" value="{{ $settings['smtp_password'] ?? '' }}" placeholder="••••••••••••" class="w-full p-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-900">
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-slate-700 block mb-1">Sender Email Address</label>
                            <input type="text" name="smtp_from_address" value="{{ $settings['smtp_from_address'] ?? 'no-reply@hostninja.cloud' }}" placeholder="no-reply@hostninja.cloud" class="w-full p-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-900">
                        </div>
                        <div class="md:col-span-3">
                            <label class="text-[11px] font-semibold text-slate-700 block mb-1">Sender Display Name</label>
                            <input type="text" name="smtp_from_name" value="{{ $settings['smtp_from_name'] ?? 'HostNinja Cloud Notifications' }}" placeholder="HostNinja Cloud System" class="w-full p-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-900">
                        </div>
                    </div>
                </div>

                <!-- Editable Mail Templates -->
                <div class="space-y-6">
                    <h4 class="font-bold text-sm text-slate-900">Automated Notification Templates</h4>

                    <!-- Template 1: Welcome Email -->
                    <div class="p-4 bg-white rounded-2xl border border-slate-200 space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-xs text-[#0059bb]">1. Welcome New Customer Email</span>
                            <button type="button" @click="previewSubject = 'Welcome to HostNinja Cloud!'; previewBody = $refs.tplWelcome.value; showMailPreview = true" class="text-xs text-[#0059bb] font-bold hover:underline">Preview HTML &rarr;</button>
                        </div>
                        <textarea x-ref="tplWelcome" name="template_welcome" rows="3" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-slate-800">{{ $settings['template_welcome'] ?? "Hello {name},\n\nWelcome to HostNinja Cloud! Your account is now active. Access your control panel at https://hostninja.cloud/dashboard." }}</textarea>
                    </div>

                    <!-- Template 2: Invoice Created -->
                    <div class="p-4 bg-white rounded-2xl border border-slate-200 space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-xs text-[#0059bb]">2. Invoice Generated & Payment Receipt Notification</span>
                            <button type="button" @click="previewSubject = 'Invoice Issued: {invoice_number}'; previewBody = $refs.tplInvoice.value; showMailPreview = true" class="text-xs text-[#0059bb] font-bold hover:underline">Preview HTML &rarr;</button>
                        </div>
                        <textarea x-ref="tplInvoice" name="template_invoice_created" rows="3" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-slate-800">{{ $settings['template_invoice_created'] ?? "Hi {name},\n\nA new invoice #{invoice_number} for KES {total} has been issued. Thank you for your payment!" }}</textarea>
                    </div>

                    <!-- Template 3: cPanel & Service Login Credentials -->
                    <div class="p-4 bg-white rounded-2xl border border-slate-200 space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-xs text-amber-600">3. cPanel & Web Hosting Credentials Notification</span>
                            <button type="button" @click="previewSubject = 'cPanel Credentials for {domain_name}'; previewBody = $refs.tplCpanel.value; showMailPreview = true" class="text-xs text-[#0059bb] font-bold hover:underline">Preview HTML &rarr;</button>
                        </div>
                        <textarea x-ref="tplCpanel" name="template_cpanel_credentials" rows="3" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-slate-800">{{ $settings['template_cpanel_credentials'] ?? "Hello {name},\n\nYour cPanel account for {domain_name} is active!\nUsername: {username}\nPassword: {password}\ncPanel URL: https://{domain_name}:2083" }}</textarea>
                    </div>

                    <!-- Template 4: Payment Due Reminder -->
                    <div class="p-4 bg-white rounded-2xl border border-slate-200 space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-xs text-rose-600">4. Payment Due Reminder</span>
                            <button type="button" @click="previewSubject = 'Payment Reminder: Invoice #{invoice_number}'; previewBody = $refs.tplReminder.value; showMailPreview = true" class="text-xs text-[#0059bb] font-bold hover:underline">Preview HTML &rarr;</button>
                        </div>
                        <textarea x-ref="tplReminder" name="template_payment_reminder" rows="3" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-slate-800">{{ $settings['template_payment_reminder'] ?? "Hello {name},\n\nThis is a reminder that invoice #{invoice_number} for KES {total} is due on {due_date}. Please log in to complete payment." }}</textarea>
                    </div>
                </div>
            </div>

            <!-- TAB 3: GATEWAYS -->
            <div x-show="tab === 'gateways'" class="space-y-6" x-cloak>
                <div class="flex justify-between items-center border-b border-slate-200 pb-3">
                    <h3 class="font-['Hanken_Grotesk'] text-lg font-bold text-slate-900">Payment Gateway Credentials</h3>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">ONLINE</span>
                </div>
                <div class="space-y-4">
                    <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-200">
                        <h4 class="font-bold text-xs text-emerald-800 mb-3">Safaricom M-Pesa Daraja STK Push API</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-[11px] font-semibold text-slate-700 block mb-1">Shortcode (Paybill / Till)</label>
                                <input type="text" name="mpesa_shortcode" value="{{ $settings['mpesa_shortcode'] ?? '174379' }}" class="w-full p-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-900">
                            </div>
                            <div>
                                <label class="text-[11px] font-semibold text-slate-700 block mb-1">Passkey</label>
                                <input type="password" name="mpesa_passkey" value="{{ $settings['mpesa_passkey'] ?? '' }}" class="w-full p-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-900">
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-blue-50 rounded-2xl border border-blue-200">
                        <h4 class="font-bold text-xs text-blue-800 mb-3">Stripe Credit Card Gateway</h4>
                        <div>
                            <label class="text-[11px] font-semibold text-slate-700 block mb-1">Stripe Publishable Key</label>
                            <input type="text" name="stripe_key" value="{{ $settings['stripe_key'] ?? '' }}" class="w-full p-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-900">
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 4: GENERAL -->
            <div x-show="tab === 'general'" class="space-y-6" x-cloak>
                <h3 class="font-['Hanken_Grotesk'] text-lg font-bold text-slate-900 border-b border-slate-200 pb-3">Company & Currency Settings</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-semibold text-slate-700 block mb-1">Company Name</label>
                        <input type="text" name="company_name" value="{{ $settings['company_name'] ?? 'HostNinja Cloud Infrastructure' }}" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-700 block mb-1">Company Email</label>
                        <input type="email" name="company_email" value="{{ $settings['company_email'] ?? 'billing@hostninja.cloud' }}" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-700 block mb-1">Default Currency Code</label>
                        <input type="text" name="currency" value="{{ $settings['currency'] ?? 'KES' }}" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-700 block mb-1">Value Added Tax Rate (%)</label>
                        <input type="text" name="tax_rate" value="{{ $settings['tax_rate'] ?? '16.00' }}" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900">
                    </div>
                </div>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="px-8 py-3 bg-[#0059bb] hover:bg-blue-600 text-white font-bold text-xs rounded-xl shadow-lg transition-all">
                    Save Changes
                </button>
            </div>
        </form>

        <!-- MODAL: EMAIL TEMPLATE PREVIEWER -->
        <div x-show="showMailPreview" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak>
            <div class="bg-white p-8 rounded-3xl border border-slate-200 max-w-lg w-full shadow-2xl" @click.away="showMailPreview = false">
                <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3">
                    <h3 class="font-['Hanken_Grotesk'] text-lg font-bold text-slate-900">Email Notification Template Preview</h3>
                    <button type="button" @click="showMailPreview = false" class="text-slate-400 hover:text-slate-600 font-bold text-sm">✕</button>
                </div>

                <div class="bg-slate-900 text-white rounded-2xl p-6 space-y-4 font-sans text-xs shadow-inner">
                    <div class="border-b border-slate-800 pb-3">
                        <div class="text-slate-400 font-['JetBrains_Mono'] text-[10px]">SUBJECT</div>
                        <div class="font-bold text-sm text-[#00F5FF]" x-text="previewSubject"></div>
                    </div>

                    <div class="text-slate-200 whitespace-pre-wrap leading-relaxed" x-text="previewBody"></div>

                    <div class="pt-4 border-t border-slate-800 text-[10px] text-slate-500 font-['JetBrains_Mono'] flex justify-between">
                        <span>HostNinja Cloud Automation Engine</span>
                        <span>SMTP Verified</span>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="button" @click="showMailPreview = false" class="px-6 py-2 bg-slate-900 text-white font-bold text-xs rounded-xl">Close Preview</button>
                </div>
            </div>
        </div>

        <!-- MODAL: TEST SMTP CONNECTION -->
        <div id="test_smtp_modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
            <div class="bg-white p-8 rounded-3xl border border-slate-200 max-w-md w-full shadow-2xl space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-['Hanken_Grotesk'] text-lg font-bold text-slate-900">Test SMTP Server Connection</h3>
                    <button type="button" onclick="document.getElementById('test_smtp_modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 font-bold text-sm">✕</button>
                </div>

                <form method="POST" action="{{ route('admin.smtp.test') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="text-xs font-semibold text-slate-700 block mb-1">Send Test Email To Address</label>
                        <input type="email" name="test_email" value="{{ auth()->user()->email }}" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900">
                        <p class="text-[10px] text-slate-400 mt-1">HostNinja will attempt to connect to your configured SMTP server and dispatch a test message.</p>
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" onclick="document.getElementById('test_smtp_modal').classList.add('hidden')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl">Cancel</button>
                        <button type="submit" class="px-5 py-2 bg-[#0059bb] hover:bg-blue-600 text-white font-bold text-xs rounded-xl shadow">Dispatch Test Mail</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-admin-layout>
