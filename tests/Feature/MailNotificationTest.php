<?php

namespace Tests\Feature;

use App\Mail\InvoicePaidMail;
use App\Mail\PaymentReminderMail;
use App\Mail\ServiceProvisionedMail;
use App\Models\HostingPlan;
use App\Models\HostingService;
use App\Models\Invoice;
use App\Models\Setting;
use App\Models\User;
use App\Services\MailConfigService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class MailNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_dynamic_smtp_config_overrides_laravel_mailer()
    {
        Setting::setKey('smtp_host', 'smtp.mycompany.com');
        Setting::setKey('smtp_port', '465');
        Setting::setKey('smtp_encryption', 'ssl');
        Setting::setKey('smtp_username', 'admin@mycompany.com');
        Setting::setKey('smtp_password', 'secret123');
        Setting::setKey('smtp_from_address', 'support@mycompany.com');
        Setting::setKey('smtp_from_name', 'MyCompany Hosting');

        MailConfigService::applyDynamicSmtpConfig();

        $this->assertEquals('smtp', config('mail.default'));
        $this->assertEquals('smtp.mycompany.com', config('mail.mailers.smtp.host'));
        $this->assertEquals(465, config('mail.mailers.smtp.port'));
        $this->assertEquals('ssl', config('mail.mailers.smtp.encryption'));
        $this->assertEquals('support@mycompany.com', config('mail.from.address'));
        $this->assertEquals('MyCompany Hosting', config('mail.from.name'));
    }

    public function test_admin_can_send_smtp_test_email()
    {
        Mail::fake();

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($admin)->post(route('admin.smtp.test'), [
            'test_email' => 'testrecip@company.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    public function test_admin_can_resend_invoice_paid_email()
    {
        Mail::fake();

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        $customer = User::create([
            'name' => 'John Customer',
            'email' => 'john@customer.com',
            'role' => 'customer',
            'password' => bcrypt('password'),
        ]);

        $invoice = Invoice::create([
            'user_id' => $customer->id,
            'invoice_number' => 'INV-2026-9999',
            'description' => 'Business Hosting',
            'amount' => 1000.00,
            'tax' => 160.00,
            'total' => 1160.00,
            'status' => 'paid',
            'due_date' => now(),
        ]);

        $response = $this->actingAs($admin)->post(route('admin.invoices.resend', $invoice->id));

        $response->assertRedirect();
        Mail::assertSent(InvoicePaidMail::class, function ($mail) use ($customer) {
            return $mail->hasTo($customer->email);
        });
    }

    public function test_admin_can_send_cpanel_credentials_email()
    {
        Mail::fake();

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        $customer = User::create([
            'name' => 'John Customer',
            'email' => 'john@customer.com',
            'role' => 'customer',
            'password' => bcrypt('password'),
        ]);

        $plan = HostingPlan::create([
            'name' => 'Starter Plan',
            'slug' => 'starter',
            'price_monthly' => 299.00,
            'price_yearly' => 2990.00,
            'storage_gb' => 10,
            'bandwidth_gb' => 100,
            'email_accounts' => 10,
            'databases' => 5,
            'ssl_free' => true,
            'is_active' => true,
        ]);

        $service = HostingService::create([
            'user_id' => $customer->id,
            'hosting_plan_id' => $plan->id,
            'domain_name' => 'mybrand.co.ke',
            'username' => 'mybrand',
            'status' => 'active',
            'billing_cycle' => 'monthly',
            'amount' => 299.00,
            'next_due_date' => now()->addMonth(),
        ]);

        $response = $this->actingAs($admin)->post(route('admin.services.cpanel-credentials', $service->id));

        $response->assertRedirect();
        Mail::assertSent(ServiceProvisionedMail::class, function ($mail) use ($customer) {
            return $mail->hasTo($customer->email);
        });
    }
}
