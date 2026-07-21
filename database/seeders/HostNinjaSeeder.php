<?php

namespace Database\Seeders;

use App\Models\Domain;
use App\Models\HostingPlan;
use App\Models\HostingService;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Registrar;
use App\Models\ResellerCommission;
use App\Models\Server;
use App\Models\Setting;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class HostNinjaSeeder extends Seeder
{
    public function run(): void
    {
        // Settings
        Setting::setKey('company_name', 'HostNinja Cloud Infrastructure', 'general');
        Setting::setKey('company_email', 'billing@hostninja.cloud', 'general');
        Setting::setKey('currency', 'KES', 'billing');
        Setting::setKey('tax_rate', '16.00', 'billing');
        Setting::setKey('mpesa_shortcode', '174379', 'payment');
        Setting::setKey('mpesa_passkey', 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919', 'payment');
        Setting::setKey('stripe_key', 'pk_test_51H...hostninja', 'payment');
        Setting::setKey('smtp_host', 'smtp.hostninja.cloud', 'mail');
        Setting::setKey('smtp_port', '587', 'mail');
        Setting::setKey('show_footer_payment_methods', '1', 'payment');

        // SEO & Keywords Settings
        Setting::setKey('seo_title', 'HostNinja | Lightning Fast Cloud Hosting & Domains', 'seo');
        Setting::setKey('seo_description', 'HostNinja provides enterprise-grade cloud hosting, NVMe SSD speed, domain registration, M-Pesa STK push integration, and 24/7 technical support.', 'seo');
        Setting::setKey('seo_keywords', 'cloud hosting, nvme hosting, domain registration, cpanel hosting, mpesa web hosting kenya, hostninja, web hosting nairobi', 'seo');
        Setting::setKey('google_analytics_id', 'G-HN998218XX', 'seo');
        Setting::setKey('robots_txt', "User-agent: *\nAllow: /\nDisallow: /admin\nSitemap: https://hostninja.cloud/sitemap.xml", 'seo');

        // Mail Notification Templates
        Setting::setKey('template_welcome', "Hello {name},\n\nWelcome to HostNinja Cloud! Your account is now active. Access your control panel at https://hostninja.cloud/dashboard.", 'mail');
        Setting::setKey('template_invoice_created', "Hi {name},\n\nA new invoice #{invoice_number} for KES {total} has been issued. Please process payment before {due_date}.", 'mail');
        Setting::setKey('template_payment_received', "Hello {name},\n\nWe received your payment of KES {amount} (Ref: {reference}). Thank you for choosing HostNinja Cloud!", 'mail');

        // Seed 14 Supported Payment Methods
        $defaultMethods = [
            [
                'code' => 'visa',
                'name' => 'Visa',
                'category' => 'cards',
                'sort_order' => 1,
                'icon_svg' => '<svg class="w-auto h-7 text-white fill-current" viewBox="0 0 36 24" xmlns="http://www.w3.org/2000/svg"><rect width="36" height="24" rx="4" fill="#1434CB"/><path d="M13.8 17.5l2-11.5h3.2l-2 11.5h-3.2zm10.7-11.2c-.6-.2-1.6-.4-2.8-.4-3.1 0-5.3 1.6-5.3 3.9 0 1.7 1.6 2.6 2.8 3.2 1.2.6 1.6 1 1.6 1.5 0 .8-1 1.2-1.9 1.2-1.3 0-2-.2-3.1-.7l-.4-.2-.5 3c.9.4 2.5.7 4.1.7 3.3 0 5.4-1.6 5.4-4 0-1.3-.8-2.3-2.6-3.2-1.1-.5-1.8-.9-1.8-1.5 0-.5.6-1 1.9-1 1 0 1.8.2 2.4.5l.3.1.4-2.9zm6.6-.3h-2.5c-.8 0-1.4.2-1.7 1l-4.9 11h3.4s.5-1.5.7-1.9h4.2c.1.4.4 1.9.4 1.9h3l-2.6-12zm-3.6 7.4l1.7-4.6.9 4.6h-2.6zM10 6L6.8 13.9 6.5 12.3c-.6-2.1-2.4-4.4-4.5-5.5L4.7 17.5h3.4l5.1-11.5H10z" fill="#FFFFFF"/></svg>',
            ],
            [
                'code' => 'mastercard',
                'name' => 'Mastercard',
                'category' => 'cards',
                'sort_order' => 2,
                'icon_svg' => '<svg class="w-auto h-7" viewBox="0 0 36 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="36" height="24" rx="4" fill="#0A0E1A"/><circle cx="14" cy="12" r="7" fill="#EB001B"/><circle cx="22" cy="12" r="7" fill="#F79E1B"/><path d="M18 6.7A6.97 6.97 0 0015.5 12c0 2.1.9 4 2.5 5.3a6.97 6.97 0 002.5-5.3c0-2.1-.9-4-2.5-5.3z" fill="#FF5F00"/></svg>',
            ],
            [
                'code' => 'amex',
                'name' => 'American Express',
                'category' => 'cards',
                'sort_order' => 3,
                'icon_svg' => '<svg class="w-auto h-7" viewBox="0 0 36 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="36" height="24" rx="4" fill="#006FCF"/><path d="M7 11.5l1.5-3.5h2l.7 1.8.7-1.8h2l1.5 3.5h-1.5l-.3-.8h-1.6l-.3.8H7.3zm2.5-1.8l.5 1.3h-1l.5-1.3zm6.5 1.8V8h4.5v1.3H17.5v.8h2.7V11.4h-2.7v.8h3v1.3H16zm6 0V8h2.2l1.3 2.2L26.8 8H29v5.5h-1.5v-3.5l-1.3 2.2h-1l-1.3-2.2v3.5H22z" fill="#FFFFFF"/></svg>',
            ],
            [
                'code' => 'paypal',
                'name' => 'PayPal',
                'category' => 'wallets',
                'sort_order' => 4,
                'icon_svg' => '<svg class="w-auto h-7" viewBox="0 0 36 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="36" height="24" rx="4" fill="#003087"/><path d="M12.5 6h4c2.5 0 4 1.2 3.6 3.5-.5 2.8-2.6 4.5-5.1 4.5h-1.5l-1 6h-2.5l2.5-14z" fill="#0079C1"/><path d="M14.5 9h4c2.5 0 4 1.2 3.6 3.5-.5 2.8-2.6 4.5-5.1 4.5h-1.5l-1 6h-2.5l2.5-14z" fill="#00457C" opacity="0.3"/><path d="M13.5 8h4c2.5 0 4 1.2 3.6 3.5-.5 2.8-2.6 4.5-5.1 4.5h-1.5l-1 6h-2.5l2.5-14z" fill="#0079C1"/></svg>',
            ],
            [
                'code' => 'stripe',
                'name' => 'Stripe',
                'category' => 'cards',
                'sort_order' => 5,
                'icon_svg' => '<svg class="w-auto h-7" viewBox="0 0 36 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="36" height="24" rx="4" fill="#635BFF"/><path d="M16.7 10.3c0-.6.5-.9 1.4-.9 1.2 0 2.8.4 4 1.1V7.2c-1.3-.5-2.7-.7-4-.7-3.4 0-5.7 1.8-5.7 4.8 0 4.7 6.4 3.9 6.4 6 0 .8-.7 1.1-1.7 1.1-1.5 0-3.3-.6-4.7-1.4v3.4c1.5.7 3.2 1 4.7 1 3.6 0 6-1.8 6-4.9-.1-5-6.4-4.1-6.4-6.2z" fill="#FFFFFF"/></svg>',
            ],
            [
                'code' => 'applepay',
                'name' => 'Apple Pay',
                'category' => 'wallets',
                'sort_order' => 6,
                'icon_svg' => '<svg class="w-auto h-7" viewBox="0 0 36 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="36" height="24" rx="4" fill="#000000"/><path d="M12.5 12.3c0-1.6 1.3-2.4 1.3-2.5-.7-1.1-1.9-1.2-2.3-1.2-1-.1-2 .6-2.5.6-.5 0-1.3-.6-2.1-.6-1.1 0-2.1.6-2.6 1.6-1.2 2-0.3 5 0.8 6.7.6.8 1.2 1.7 2.1 1.7.9 0 1.2-.5 2.2-.5 1 0 1.3.5 2.2.5.9 0 1.5-.8 2.1-1.6.6-.9.9-1.9.9-2-.1 0-1.9-.7-2.1-2.2zM11.6 7.6c.5-.6.8-1.4.7-2.2-.7 0-1.5.4-2 .1-.4.5-.8 1.3-.7 2.1.8.1 1.5-.4 2-.9z" fill="#FFFFFF"/><path d="M18.5 7h2v10h-2V7zm5 0h3c1.5 0 2.5.8 2.5 2.2 0 1.4-1 2.2-2.5 2.2h-1V17h-2V7zm2 3h1c.5 0 1-.2 1-.7s-.5-.7-1-.7h-1V10z" fill="#FFFFFF"/></svg>',
            ],
            [
                'code' => 'googlepay',
                'name' => 'Google Pay',
                'category' => 'wallets',
                'sort_order' => 7,
                'icon_svg' => '<svg class="w-auto h-7" viewBox="0 0 36 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="36" height="24" rx="4" fill="#FFFFFF" stroke="#E2E8F0"/><path d="M13.2 12.2v2.7h-4.3V7h4.3v2.7h-1.6v-1.1H10.5v1.3h2.3v1.5h-2.3v1.5h2.7v-0.7z" fill="#5F6368"/><path d="M17.1 11.2c0 2.3-1.6 3.9-3.9 3.9s-3.9-1.6-3.9-3.9 1.6-3.9 3.9-3.9 3.9 1.6 3.9 3.9zm-1.6 0c0-1.5-.9-2.5-2.3-2.5s-2.3 1-2.3 2.5.9 2.5 2.3 2.5 2.3-1 2.3-2.5z" fill="#4285F4"/><path d="M21.5 7.3h-2.6v9.3h2.6V7.3z" fill="#34A853"/><path d="M26.2 11.2c0 2.3-1.6 3.9-3.9 3.9s-3.9-1.6-3.9-3.9 1.6-3.9 3.9-3.9 3.9 1.6 3.9 3.9zm-1.6 0c0-1.5-.9-2.5-2.3-2.5s-2.3 1-2.3 2.5.9 2.5 2.3 2.5 2.3-1 2.3-2.5z" fill="#EA4335"/></svg>',
            ],
            [
                'code' => 'mpesa',
                'name' => 'M-Pesa Express',
                'category' => 'mobile',
                'sort_order' => 8,
                'icon_svg' => '<svg class="w-auto h-7" viewBox="0 0 36 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="36" height="24" rx="4" fill="#00A651"/><path d="M8 6h3l2.5 6L16 6h3v12h-2.5v-7.5L14 16.5h-1L10.5 10.5V18H8V6zm13 8h4v4h-4v-4zm0-8h4v6h-4V6z" fill="#FFFFFF"/><path d="M26 6h2v12h-2V6z" fill="#E21A22"/></svg>',
            ],
            [
                'code' => 'airtelmoney',
                'name' => 'Airtel Money',
                'category' => 'mobile',
                'sort_order' => 9,
                'icon_svg' => '<svg class="w-auto h-7" viewBox="0 0 36 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="36" height="24" rx="4" fill="#FF0000"/><path d="M10 6h4c2.5 0 4 1.5 4 3.5 0 1.5-.8 2.5-2 3l2.5 5.5h-3l-2.2-5H12.5V18H10V6zm2.5 2.3v3h1.5c1 0 1.7-.5 1.7-1.5s-.7-1.5-1.7-1.5h-1.5z" fill="#FFFFFF"/><path d="M21 12c0-2.2 1.8-4 4-4s4 1.8 4 4-1.8 4-4 4-4-1.8-4-4zm6 0c0-1.1-.9-2-2-2s-2 .9-2 2 .9 2 2 2 2-.9 2-2z" fill="#FFFFFF"/></svg>',
            ],
            [
                'code' => 'binancepay',
                'name' => 'Binance Pay',
                'category' => 'crypto',
                'sort_order' => 10,
                'icon_svg' => '<svg class="w-auto h-7" viewBox="0 0 36 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="36" height="24" rx="4" fill="#181A20"/><path d="M18 6l3 3-3 3-3-3 3-3zm-6 6l3 3-3 3-3-3 3-3zm12 0l3 3-3 3-3-3 3-3zm-6 6l3 3-3 3-3-3 3-3zm0-6l2.1 2.1-2.1 2.1-2.1-2.1 2.1-2.1z" fill="#F0B90B"/></svg>',
            ],
            [
                'code' => 'skrill',
                'name' => 'Skrill',
                'category' => 'wallets',
                'sort_order' => 11,
                'icon_svg' => '<svg class="w-auto h-7" viewBox="0 0 36 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="36" height="24" rx="4" fill="#811E44"/><path d="M11 10.5c0-.8.7-1.3 1.8-1.3 1.5 0 3.2.6 4.3 1.3V7.8c-1.3-.5-2.8-.8-4.3-.8-3.5 0-5.8 1.8-5.8 4.7 0 4.5 6.2 3.8 6.2 5.8 0 .8-.8 1.3-2 1.3-1.8 0-3.8-.8-5.2-1.7v2.8c1.5.8 3.5 1.2 5.2 1.2 3.8 0 6.2-1.8 6.2-4.9 0-4.8-6.4-3.9-6.4-6.8zM24 6h-3v12h3V6zm3 0h-3v12h3V6z" fill="#FFFFFF"/></svg>',
            ],
            [
                'code' => 'payoneer',
                'name' => 'Payoneer',
                'category' => 'wallets',
                'sort_order' => 12,
                'icon_svg' => '<svg class="w-auto h-7" viewBox="0 0 36 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="36" height="24" rx="4" fill="#FF4800"/><circle cx="18" cy="12" r="6" fill="#FFFFFF"/><circle cx="18" cy="12" r="3" fill="#FF4800"/></svg>',
            ],
            [
                'code' => 'wise',
                'name' => 'Wise',
                'category' => 'banking',
                'sort_order' => 13,
                'icon_svg' => '<svg class="w-auto h-7" viewBox="0 0 36 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="36" height="24" rx="4" fill="#2E0696"/><path d="M12 7l-4 10h2.8l.8-2h4.8l.8 2H20L16 7h-4zm.4 6l1.6-4.5 1.6 4.5h-3.2z" fill="#00D4B6"/><path d="M21 7l4 6-1.5 4h3L30 7h-9z" fill="#00D4B6"/></svg>',
            ],
            [
                'code' => 'banktransfer',
                'name' => 'Bank Transfer',
                'category' => 'banking',
                'sort_order' => 14,
                'icon_svg' => '<svg class="w-auto h-7" viewBox="0 0 36 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="36" height="24" rx="4" fill="#1E293B"/><path d="M18 6L8 11v2h20v-2L18 6zm-8 8v5h2v-5h-2zm5 0v5h2v-5h-2zm5 0v5h2v-5h-2zm-12 6v2h20v-2H8z" fill="#94A3B8"/></svg>',
            ],
        ];

        foreach ($defaultMethods as $pm) {
            PaymentMethod::firstOrCreate(
                ['code' => $pm['code']],
                [
                    'name' => $pm['name'],
                    'category' => $pm['category'],
                    'sort_order' => $pm['sort_order'],
                    'icon_svg' => $pm['icon_svg'],
                    'is_enabled' => true,
                    'show_in_footer' => true,
                ]
            );
        }

        // Seed Domain Registrars
        $resellerClub = Registrar::firstOrCreate(
            ['slug' => 'resellerclub'],
            [
                'name' => 'ResellerClub (LogicBoxes)',
                'logo' => 'resellerclub.png',
                'description' => 'Global domain registrar supporting over 500 TLDs with instant registration and WHOIS privacy.',
                'enabled' => true,
                'default' => true,
                'sandbox' => true,
                'credentials' => [
                    'reseller_id' => '984124',
                    'api_key' => 'rc_live_key_998124',
                    'username' => 'api@hostninja.cloud',
                ],
                'endpoint' => 'https://test.httpapi.com/api/',
                'supported_features' => ['check', 'register', 'renew', 'transfer', 'whois_privacy', 'dns', 'nameservers', 'lock'],
                'last_connection' => now(),
            ]
        );

        Registrar::firstOrCreate(
            ['slug' => 'openprovider'],
            [
                'name' => 'Openprovider',
                'logo' => 'openprovider.png',
                'description' => 'Automated domain registrar platform with competitive European and ccTLD pricing.',
                'enabled' => true,
                'default' => false,
                'sandbox' => true,
                'credentials' => [
                    'username' => 'openprovider_user',
                    'password' => 'op_secret_password',
                ],
                'endpoint' => 'https://api.pte.openprovider.eu/v1beta/',
                'supported_features' => ['check', 'register', 'renew', 'transfer', 'whois_privacy', 'dns'],
                'last_connection' => now(),
            ]
        );

        Registrar::firstOrCreate(
            ['slug' => 'namesilo'],
            [
                'name' => 'NameSilo',
                'logo' => 'namesilo.png',
                'description' => 'Low-cost API domain registrar providing free WHOIS privacy and DNS management.',
                'enabled' => true,
                'default' => false,
                'sandbox' => true,
                'credentials' => [
                    'api_key' => 'ns_api_key_884129',
                ],
                'endpoint' => 'https://sandbox.namesilo.com/api/',
                'supported_features' => ['check', 'register', 'renew', 'whois_privacy', 'dns'],
                'last_connection' => now(),
            ]
        );

        Registrar::firstOrCreate(
            ['slug' => 'opensrs'],
            [
                'name' => 'OpenSRS (Tucows)',
                'logo' => 'opensrs.png',
                'description' => 'Tucows OpenSRS domain management API with enterprise DNSSEC support.',
                'enabled' => false,
                'default' => false,
                'sandbox' => true,
                'credentials' => [
                    'username' => 'opensrs_reseller',
                    'api_key' => 'srs_key_009123',
                ],
                'endpoint' => 'https://horizon.opensrs.net:55443',
                'supported_features' => ['check', 'register', 'renew', 'dnssec'],
                'last_connection' => null,
            ]
        );

        Registrar::firstOrCreate(
            ['slug' => 'enom'],
            [
                'name' => 'eNom',
                'logo' => 'enom.png',
                'description' => 'Established wholesale domain registrar API with extensive ccTLD support.',
                'enabled' => false,
                'default' => false,
                'sandbox' => true,
                'credentials' => [
                    'username' => 'enom_admin',
                    'password' => 'enom_pass_998',
                ],
                'endpoint' => 'https://resellertest.enom.com/interface.asp',
                'supported_features' => ['check', 'register', 'renew', 'lock', 'nameservers'],
                'last_connection' => null,
            ]
        );

        // Users
        $admin = User::firstOrCreate(
            ['email' => 'admin@hostninja.cloud'],
            [
                'name' => 'Alex Vance (Admin)',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '+254 712 345678',
                'company' => 'HostNinja Infrastructure Ltd',
                'two_factor_enabled' => true,
                'balance' => 50000.00,
            ]
        );

        $customer = User::firstOrCreate(
            ['email' => 'customer@hostninja.cloud'],
            [
                'name' => 'David Kamau',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '+254 799 123456',
                'company' => 'Nairobi Tech Ventures',
                'two_factor_enabled' => false,
                'balance' => 3450.00,
            ]
        );

        $reseller = User::firstOrCreate(
            ['email' => 'reseller@hostninja.cloud'],
            [
                'name' => 'Sarah Jenkins',
                'password' => Hash::make('password'),
                'role' => 'reseller',
                'phone' => '+254 700 888999',
                'company' => 'Apex Cloud Solutions',
                'two_factor_enabled' => true,
                'balance' => 12800.00,
            ]
        );

        $agent = User::firstOrCreate(
            ['email' => 'agent@hostninja.cloud'],
            [
                'name' => 'Brian Ochieng (Support)',
                'password' => Hash::make('password'),
                'role' => 'support_agent',
                'phone' => '+254 722 000111',
                'company' => 'HostNinja Support Team',
                'two_factor_enabled' => true,
                'balance' => 0.00,
            ]
        );

        // Reseller Commissions
        ResellerCommission::firstOrCreate(
            ['reseller_id' => $reseller->id, 'service_name' => 'Business NVMe Hosting Plan'],
            [
                'client_id' => $customer->id,
                'sale_amount' => 599.00,
                'commission_amount' => 119.80,
                'status' => 'paid',
            ]
        );

        ResellerCommission::firstOrCreate(
            ['reseller_id' => $reseller->id, 'service_name' => 'Domain .co.ke Registration'],
            [
                'client_id' => $customer->id,
                'sale_amount' => 990.00,
                'commission_amount' => 148.50,
                'status' => 'paid',
            ]
        );

        // Hosting Plans
        $starterPlan = HostingPlan::firstOrCreate(
            ['slug' => 'starter'],
            [
                'name' => 'Starter Plan',
                'tagline' => 'Ideal for personal blogs and landing pages',
                'price_monthly' => 299.00,
                'price_yearly' => 2990.00,
                'storage_gb' => 10,
                'bandwidth_gb' => 100,
                'email_accounts' => 10,
                'databases' => 5,
                'ssl_free' => true,
                'is_featured' => false,
                'is_active' => true,
            ]
        );

        $businessPlan = HostingPlan::firstOrCreate(
            ['slug' => 'business'],
            [
                'name' => 'Business Plan',
                'tagline' => 'High-performance NVMe storage for growing businesses',
                'price_monthly' => 599.00,
                'price_yearly' => 5990.00,
                'storage_gb' => 50,
                'bandwidth_gb' => 500,
                'email_accounts' => 50,
                'databases' => 25,
                'ssl_free' => true,
                'is_featured' => true,
                'is_active' => true,
            ]
        );

        $proPlan = HostingPlan::firstOrCreate(
            ['slug' => 'professional'],
            [
                'name' => 'Professional Plan',
                'tagline' => 'Unmatched speed & resources for high traffic sites',
                'price_monthly' => 1199.00,
                'price_yearly' => 11990.00,
                'storage_gb' => 150,
                'bandwidth_gb' => 1000,
                'email_accounts' => 100,
                'databases' => 50,
                'ssl_free' => true,
                'is_featured' => false,
                'is_active' => true,
            ]
        );

        $enterprisePlan = HostingPlan::firstOrCreate(
            ['slug' => 'enterprise'],
            [
                'name' => 'Enterprise Cloud',
                'tagline' => 'Dedicated resources & priority VIP 24/7 support',
                'price_monthly' => 2499.00,
                'price_yearly' => 24990.00,
                'storage_gb' => 500,
                'bandwidth_gb' => 5000,
                'email_accounts' => 500,
                'databases' => 200,
                'ssl_free' => true,
                'is_featured' => false,
                'is_active' => true,
            ]
        );

        // Servers
        $server1 = Server::firstOrCreate(
            ['hostname' => 'ns1.hostninja.cloud'],
            [
                'name' => 'Nairobi-Edge-01',
                'ip_address' => '197.248.0.12',
                'type' => 'cPanel',
                'status' => 'online',
                'active_accounts' => 142,
                'max_accounts' => 500,
                'disk_usage_percent' => 32,
                'cpu_usage_percent' => 14,
            ]
        );

        $server2 = Server::firstOrCreate(
            ['hostname' => 'ns2.hostninja.cloud'],
            [
                'name' => 'Frankfurt-Cloud-02',
                'ip_address' => '185.12.90.44',
                'type' => 'cPanel',
                'status' => 'online',
                'active_accounts' => 88,
                'max_accounts' => 500,
                'disk_usage_percent' => 19,
                'cpu_usage_percent' => 9,
            ]
        );

        // Customer Domains
        Domain::firstOrCreate(
            ['domain_name' => 'mybrand.co.ke'],
            [
                'user_id' => $customer->id,
                'registrar_id' => $resellerClub->id,
                'extension' => '.co.ke',
                'registration_date' => '2025-05-12',
                'expiry_date' => '2027-05-12',
                'status' => 'active',
                'registrar' => 'ResellerClub (LogicBoxes)',
                'price' => 990.00,
                'auto_renew' => true,
                'is_locked' => true,
                'whois_privacy_enabled' => true,
                'nameservers' => ['ns1.hostninja.cloud', 'ns2.hostninja.cloud'],
                'dns_records' => [
                    ['type' => 'A', 'name' => '@', 'value' => '197.248.0.12', 'ttl' => 3600],
                    ['type' => 'CNAME', 'name' => 'www', 'value' => 'mybrand.co.ke', 'ttl' => 3600],
                    ['type' => 'MX', 'name' => '@', 'value' => 'mail.mybrand.co.ke', 'ttl' => 3600],
                ],
            ]
        );

        Domain::firstOrCreate(
            ['domain_name' => 'hypergrowth.com'],
            [
                'user_id' => $customer->id,
                'registrar_id' => $resellerClub->id,
                'extension' => '.com',
                'registration_date' => '2025-11-20',
                'expiry_date' => '2027-11-20',
                'status' => 'active',
                'registrar' => 'ResellerClub (LogicBoxes)',
                'price' => 1200.00,
                'auto_renew' => true,
                'is_locked' => true,
                'whois_privacy_enabled' => false,
                'nameservers' => ['ns1.hostninja.cloud', 'ns2.hostninja.cloud'],
                'dns_records' => [
                    ['type' => 'A', 'name' => '@', 'value' => '185.12.90.44', 'ttl' => 3600],
                    ['type' => 'CNAME', 'name' => 'www', 'value' => 'hypergrowth.com', 'ttl' => 3600],
                ],
            ]
        );

        Domain::firstOrCreate(
            ['domain_name' => 'ninjalabs.io'],
            [
                'user_id' => $customer->id,
                'registrar_id' => $resellerClub->id,
                'extension' => '.io',
                'registration_date' => '2025-12-01',
                'expiry_date' => '2026-12-01',
                'status' => 'active',
                'registrar' => 'ResellerClub (LogicBoxes)',
                'price' => 4500.00,
                'auto_renew' => false,
                'is_locked' => true,
                'whois_privacy_enabled' => true,
                'nameservers' => ['ns1.hostninja.cloud', 'ns2.hostninja.cloud'],
            ]
        );

        // Hosting Services
        HostingService::firstOrCreate(
            ['domain_name' => 'mybrand.co.ke'],
            [
                'user_id' => $customer->id,
                'hosting_plan_id' => $businessPlan->id,
                'server_id' => $server1->id,
                'username' => 'mybrandc',
                'status' => 'active',
                'billing_cycle' => 'monthly',
                'amount' => 599.00,
                'next_due_date' => '2026-08-12',
            ]
        );

        HostingService::firstOrCreate(
            ['domain_name' => 'hypergrowth.com'],
            [
                'user_id' => $customer->id,
                'hosting_plan_id' => $starterPlan->id,
                'server_id' => $server2->id,
                'username' => 'hypergrw',
                'status' => 'active',
                'billing_cycle' => 'monthly',
                'amount' => 299.00,
                'next_due_date' => '2026-08-20',
            ]
        );

        // Invoices & Payments
        $inv1 = Invoice::firstOrCreate(
            ['invoice_number' => 'INV-2026-001'],
            [
                'user_id' => $customer->id,
                'description' => 'Business Hosting Plan - mybrand.co.ke (1 Month)',
                'amount' => 599.00,
                'tax' => 95.84,
                'total' => 694.84,
                'status' => 'paid',
                'due_date' => '2026-07-12',
                'paid_at' => '2026-07-10 14:32:00',
            ]
        );

        Payment::firstOrCreate(
            ['transaction_reference' => 'QHK92819XX'],
            [
                'invoice_id' => $inv1->id,
                'user_id' => $customer->id,
                'payment_method' => 'mpesa',
                'amount' => 694.84,
                'status' => 'completed',
            ]
        );

        $inv2 = Invoice::firstOrCreate(
            ['invoice_number' => 'INV-2026-002'],
            [
                'user_id' => $customer->id,
                'description' => 'Domain Registration .co.ke - mybrand.co.ke (1 Year)',
                'amount' => 990.00,
                'tax' => 158.40,
                'total' => 1148.40,
                'status' => 'paid',
                'due_date' => '2026-05-12',
                'paid_at' => '2026-05-12 09:15:00',
            ]
        );

        Payment::firstOrCreate(
            ['transaction_reference' => 'QGF81273YY'],
            [
                'invoice_id' => $inv2->id,
                'user_id' => $customer->id,
                'payment_method' => 'mpesa',
                'amount' => 1148.40,
                'status' => 'completed',
            ]
        );

        Invoice::firstOrCreate(
            ['invoice_number' => 'INV-2026-003'],
            [
                'user_id' => $customer->id,
                'description' => 'Professional Hosting Upgrade - hypergrowth.com (Monthly)',
                'amount' => 1199.00,
                'tax' => 191.84,
                'total' => 1390.84,
                'status' => 'pending',
                'due_date' => '2026-07-28',
            ]
        );

        // Support Tickets
        $ticket1 = Ticket::firstOrCreate(
            ['ticket_number' => 'TICK-8841'],
            [
                'user_id' => $customer->id,
                'subject' => 'DNS Propagation Delay for hypergrowth.com',
                'category' => 'domain',
                'priority' => 'high',
                'status' => 'answered',
                'assigned_to' => $agent->id,
            ]
        );

        TicketMessage::firstOrCreate(
            ['ticket_id' => $ticket1->id, 'user_id' => $customer->id, 'message' => 'Hello Support, I updated my nameservers 4 hours ago to ns1.hostninja.cloud. How long will full DNS propagation take?']
        );

        TicketMessage::firstOrCreate(
            ['ticket_id' => $ticket1->id, 'user_id' => $agent->id, 'message' => 'Hi David! Thanks for reaching out. Modern TLD updates usually propagate within 2 to 6 hours. I checked your domain records and ns1.hostninja.cloud is already responding correctly in Nairobi and Europe.']
        );

        $ticket2 = Ticket::firstOrCreate(
            ['ticket_number' => 'TICK-8890'],
            [
                'user_id' => $customer->id,
                'subject' => 'M-Pesa STK Push Automatic Confirmation Inquiry',
                'category' => 'billing',
                'priority' => 'medium',
                'status' => 'open',
                'assigned_to' => null,
            ]
        );

        TicketMessage::firstOrCreate(
            ['ticket_id' => $ticket2->id, 'user_id' => $customer->id, 'message' => 'Hi, does HostNinja support instant STK push auto-activation for pending invoices?']
        );

        TicketMessage::firstOrCreate(
            ['ticket_id' => $ticket2->id, 'user_id' => $customer->id, 'message' => 'Hi, does HostNinja support instant STK push auto-activation for pending invoices?']
        );

        // Seed Supported Control Panels
        $defaultPanels = [
            [
                'name' => 'cPanel & WHM',
                'slug' => 'cpanel-whm',
                'description' => 'Industry-standard Linux web hosting control panel for easy account management, DNS, and automation.',
                'official_url' => 'https://cpanel.net',
                'featured' => true,
                'display_order' => 1,
                'logo' => '<svg class="h-10 w-auto" viewBox="0 0 120 32" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="120" height="32" rx="6" fill="#FF6C2C"/><text x="12" y="21" fill="#FFFFFF" font-weight="900" font-family="sans-serif" font-size="16">cPanel</text><text x="72" y="21" fill="#1E293B" font-weight="900" font-family="sans-serif" font-size="14">& WHM</text></svg>',
            ],
            [
                'name' => 'Plesk',
                'slug' => 'plesk',
                'description' => 'Leading WebOps hosting platform to run, automate, and grow applications, websites, and cloud servers.',
                'official_url' => 'https://plesk.com',
                'featured' => true,
                'display_order' => 2,
                'logo' => '<svg class="h-10 w-auto" viewBox="0 0 120 32" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="120" height="32" rx="6" fill="#52B0E7"/><path d="M16 8l10 8-10 8V8z" fill="#FFFFFF"/><text x="36" y="21" fill="#FFFFFF" font-weight="900" font-family="sans-serif" font-size="16">Plesk</text></svg>',
            ],
            [
                'name' => 'DirectAdmin',
                'slug' => 'directadmin',
                'description' => 'Lightweight, high-performance web hosting control panel designed for speed and reliability.',
                'official_url' => 'https://directadmin.com',
                'featured' => true,
                'display_order' => 3,
                'logo' => '<svg class="h-10 w-auto" viewBox="0 0 120 32" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="120" height="32" rx="6" fill="#2B3990"/><path d="M12 8h12v4H12zM12 14h16v4H12zM12 20h8v4H12z" fill="#00AEEF"/><text x="34" y="21" fill="#FFFFFF" font-weight="800" font-family="sans-serif" font-size="13">DirectAdmin</text></svg>',
            ],
            [
                'name' => 'CyberPanel',
                'slug' => 'cyberpanel',
                'description' => 'Next-gen web hosting control panel powered by OpenLiteSpeed with built-in caching and SSL.',
                'official_url' => 'https://cyberpanel.net',
                'featured' => true,
                'display_order' => 4,
                'logo' => '<svg class="h-10 w-auto" viewBox="0 0 120 32" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="120" height="32" rx="6" fill="#0B132B"/><circle cx="20" cy="16" r="8" stroke="#00F5FF" stroke-width="3"/><text x="36" y="21" fill="#FFFFFF" font-weight="800" font-family="sans-serif" font-size="13">CyberPanel</text></svg>',
            ],
            [
                'name' => 'Webuzo',
                'slug' => 'webuzo',
                'description' => 'Single and multi-user control panel automating server administration and 1-click app deployments.',
                'official_url' => 'https://webuzo.com',
                'featured' => false,
                'display_order' => 5,
                'logo' => '<svg class="h-10 w-auto" viewBox="0 0 120 32" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="120" height="32" rx="6" fill="#10B981"/><path d="M12 16l4-8 4 8h-8z" fill="#FFFFFF"/><text x="34" y="21" fill="#FFFFFF" font-weight="900" font-family="sans-serif" font-size="15">Webuzo</text></svg>',
            ],
            [
                'name' => 'ISPConfig',
                'slug' => 'ispconfig',
                'description' => 'Open-source enterprise hosting control panel capable of managing multiple servers from one master panel.',
                'official_url' => 'https://ispconfig.org',
                'featured' => false,
                'display_order' => 6,
                'logo' => '<svg class="h-10 w-auto" viewBox="0 0 120 32" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="120" height="32" rx="6" fill="#D97706"/><rect x="12" y="9" width="14" height="14" rx="2" fill="#FFFFFF"/><text x="34" y="21" fill="#FFFFFF" font-weight="900" font-family="sans-serif" font-size="14">ISPConfig</text></svg>',
            ],
            [
                'name' => 'aaPanel',
                'slug' => 'aapanel',
                'description' => 'Simple yet powerful modular open-source web hosting control panel with one-click LEMP stack installation.',
                'official_url' => 'https://aapanel.com',
                'featured' => false,
                'display_order' => 7,
                'logo' => '<svg class="h-10 w-auto" viewBox="0 0 120 32" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="120" height="32" rx="6" fill="#2563EB"/><text x="12" y="22" fill="#38BDF8" font-weight="900" font-family="sans-serif" font-size="18">aa</text><text x="36" y="21" fill="#FFFFFF" font-weight="800" font-family="sans-serif" font-size="15">Panel</text></svg>',
            ],
            [
                'name' => 'Virtualmin',
                'slug' => 'virtualmin',
                'description' => 'Powerful and flexible Webmin-based domain management and virtual hosting control panel.',
                'official_url' => 'https://virtualmin.com',
                'featured' => false,
                'display_order' => 8,
                'logo' => '<svg class="h-10 w-auto" viewBox="0 0 120 32" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="120" height="32" rx="6" fill="#475569"/><path d="M12 20L20 8l4 12" stroke="#38BDF8" stroke-width="3"/><text x="32" y="21" fill="#FFFFFF" font-weight="800" font-family="sans-serif" font-size="13">Virtualmin</text></svg>',
            ],
            [
                'name' => 'Hestia Control Panel',
                'slug' => 'hestia-cp',
                'description' => 'Clean, fast, and modern open-source web hosting control panel with NGINX/Apache stack.',
                'official_url' => 'https://hestiacp.com',
                'featured' => false,
                'display_order' => 9,
                'logo' => '<svg class="h-10 w-auto" viewBox="0 0 120 32" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="120" height="32" rx="6" fill="#7C3AED"/><circle cx="18" cy="16" r="6" fill="#F43F5E"/><text x="32" y="21" fill="#FFFFFF" font-weight="800" font-family="sans-serif" font-size="13">HestiaCP</text></svg>',
            ],
            [
                'name' => 'CloudPanel',
                'slug' => 'cloudpanel',
                'description' => 'Modern PHP & Node.js server management control panel built for high performance and cloud environments.',
                'official_url' => 'https://cloudpanel.io',
                'featured' => true,
                'display_order' => 10,
                'logo' => '<svg class="h-10 w-auto" viewBox="0 0 120 32" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="120" height="32" rx="6" fill="#0EA5E9"/><path d="M12 18a4 4 0 018 0h6a3 3 0 00-3-3 4 4 0 00-7-2 4 4 0 00-4 5z" fill="#FFFFFF"/><text x="34" y="21" fill="#FFFFFF" font-weight="800" font-family="sans-serif" font-size="13">CloudPanel</text></svg>',
            ],
        ];

        foreach ($defaultPanels as $panel) {
            \App\Models\HostingControlPanel::firstOrCreate(
                ['slug' => $panel['slug']],
                [
                    'name' => $panel['name'],
                    'description' => $panel['description'],
                    'official_url' => $panel['official_url'],
                    'featured' => $panel['featured'],
                    'display_order' => $panel['display_order'],
                    'logo' => $panel['logo'],
                    'enabled' => true,
                ]
            );
        }
    }
}
