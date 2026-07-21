<?php

namespace Database\Seeders;

use App\Models\Domain;
use App\Models\HostingPlan;
use App\Models\HostingService;
use App\Models\Invoice;
use App\Models\Payment;
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
        ResellerCommission::create([
            'reseller_id' => $reseller->id,
            'client_id' => $customer->id,
            'service_name' => 'Business NVMe Hosting Plan',
            'sale_amount' => 599.00,
            'commission_amount' => 119.80,
            'status' => 'paid',
        ]);

        ResellerCommission::create([
            'reseller_id' => $reseller->id,
            'client_id' => $customer->id,
            'service_name' => 'Domain .co.ke Registration',
            'sale_amount' => 990.00,
            'commission_amount' => 148.50,
            'status' => 'paid',
        ]);

        // Hosting Plans
        $starterPlan = HostingPlan::create([
            'name' => 'Starter Plan',
            'slug' => 'starter',
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
        ]);

        $businessPlan = HostingPlan::create([
            'name' => 'Business Plan',
            'slug' => 'business',
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
        ]);

        $proPlan = HostingPlan::create([
            'name' => 'Professional Plan',
            'slug' => 'professional',
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
        ]);

        $enterprisePlan = HostingPlan::create([
            'name' => 'Enterprise Cloud',
            'slug' => 'enterprise',
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
        ]);

        // Servers
        $server1 = Server::create([
            'name' => 'Nairobi-Edge-01',
            'ip_address' => '197.248.0.12',
            'hostname' => 'ns1.hostninja.cloud',
            'type' => 'cPanel',
            'status' => 'online',
            'active_accounts' => 142,
            'max_accounts' => 500,
            'disk_usage_percent' => 32,
            'cpu_usage_percent' => 14,
        ]);

        $server2 = Server::create([
            'name' => 'Frankfurt-Cloud-02',
            'ip_address' => '185.12.90.44',
            'hostname' => 'ns2.hostninja.cloud',
            'type' => 'cPanel',
            'status' => 'online',
            'active_accounts' => 88,
            'max_accounts' => 500,
            'disk_usage_percent' => 19,
            'cpu_usage_percent' => 9,
        ]);

        // Customer Domains
        Domain::create([
            'user_id' => $customer->id,
            'registrar_id' => $resellerClub->id,
            'domain_name' => 'mybrand.co.ke',
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
        ]);

        Domain::create([
            'user_id' => $customer->id,
            'registrar_id' => $resellerClub->id,
            'domain_name' => 'hypergrowth.com',
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
        ]);

        Domain::create([
            'user_id' => $customer->id,
            'registrar_id' => $resellerClub->id,
            'domain_name' => 'ninjalabs.io',
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
        ]);

        // Hosting Services
        HostingService::create([
            'user_id' => $customer->id,
            'hosting_plan_id' => $businessPlan->id,
            'server_id' => $server1->id,
            'domain_name' => 'mybrand.co.ke',
            'username' => 'mybrandc',
            'status' => 'active',
            'billing_cycle' => 'monthly',
            'amount' => 599.00,
            'next_due_date' => '2026-08-12',
        ]);

        HostingService::create([
            'user_id' => $customer->id,
            'hosting_plan_id' => $starterPlan->id,
            'server_id' => $server2->id,
            'domain_name' => 'hypergrowth.com',
            'username' => 'hypergrw',
            'status' => 'active',
            'billing_cycle' => 'monthly',
            'amount' => 299.00,
            'next_due_date' => '2026-08-20',
        ]);

        // Invoices & Payments
        $inv1 = Invoice::create([
            'user_id' => $customer->id,
            'invoice_number' => 'INV-2026-001',
            'description' => 'Business Hosting Plan - mybrand.co.ke (1 Month)',
            'amount' => 599.00,
            'tax' => 95.84,
            'total' => 694.84,
            'status' => 'paid',
            'due_date' => '2026-07-12',
            'paid_at' => '2026-07-10 14:32:00',
        ]);

        Payment::create([
            'invoice_id' => $inv1->id,
            'user_id' => $customer->id,
            'payment_method' => 'mpesa',
            'transaction_reference' => 'QHK92819XX',
            'amount' => 694.84,
            'status' => 'completed',
        ]);

        $inv2 = Invoice::create([
            'user_id' => $customer->id,
            'invoice_number' => 'INV-2026-002',
            'description' => 'Domain Registration .co.ke - mybrand.co.ke (1 Year)',
            'amount' => 990.00,
            'tax' => 158.40,
            'total' => 1148.40,
            'status' => 'paid',
            'due_date' => '2026-05-12',
            'paid_at' => '2026-05-12 09:15:00',
        ]);

        Payment::create([
            'invoice_id' => $inv2->id,
            'user_id' => $customer->id,
            'payment_method' => 'mpesa',
            'transaction_reference' => 'QGF81273YY',
            'amount' => 1148.40,
            'status' => 'completed',
        ]);

        Invoice::create([
            'user_id' => $customer->id,
            'invoice_number' => 'INV-2026-003',
            'description' => 'Professional Hosting Upgrade - hypergrowth.com (Monthly)',
            'amount' => 1199.00,
            'tax' => 191.84,
            'total' => 1390.84,
            'status' => 'pending',
            'due_date' => '2026-07-28',
        ]);

        // Support Tickets
        $ticket1 = Ticket::create([
            'user_id' => $customer->id,
            'ticket_number' => 'TICK-8841',
            'subject' => 'DNS Propagation Delay for hypergrowth.com',
            'category' => 'domain',
            'priority' => 'high',
            'status' => 'answered',
            'assigned_to' => $agent->id,
        ]);

        TicketMessage::create([
            'ticket_id' => $ticket1->id,
            'user_id' => $customer->id,
            'message' => 'Hello Support, I updated my nameservers 4 hours ago to ns1.hostninja.cloud. How long will full DNS propagation take?',
        ]);

        TicketMessage::create([
            'ticket_id' => $ticket1->id,
            'user_id' => $agent->id,
            'message' => 'Hi David! Thanks for reaching out. Modern TLD updates usually propagate within 2 to 6 hours. I checked your domain records and ns1.hostninja.cloud is already responding correctly in Nairobi and Europe.',
        ]);

        $ticket2 = Ticket::create([
            'user_id' => $customer->id,
            'ticket_number' => 'TICK-8890',
            'subject' => 'M-Pesa STK Push Automatic Confirmation Inquiry',
            'category' => 'billing',
            'priority' => 'medium',
            'status' => 'open',
            'assigned_to' => null,
        ]);

        TicketMessage::create([
            'ticket_id' => $ticket2->id,
            'user_id' => $customer->id,
            'message' => 'Hi, does HostNinja support instant STK push auto-activation for pending invoices?',
        ]);
    }
}
