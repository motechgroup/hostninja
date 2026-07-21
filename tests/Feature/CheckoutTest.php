<?php

namespace Tests\Feature;

use App\Models\HostingPlan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_page_renders_successfully()
    {
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

        $response = $this->get(route('checkout.index', ['plan' => 'starter']));

        $response->assertStatus(200);
        $response->assertSee('Starter Plan');
        $response->assertSee('Checkout');
    }

    public function test_customer_can_apply_coupon_code()
    {
        $plan = HostingPlan::create([
            'name' => 'Business Plan',
            'slug' => 'business',
            'price_monthly' => 599.00,
            'price_yearly' => 5990.00,
            'storage_gb' => 50,
            'bandwidth_gb' => 500,
            'email_accounts' => 50,
            'databases' => 25,
            'ssl_free' => true,
            'is_active' => true,
        ]);

        $response = $this->post(route('checkout.coupon'), [
            'coupon_code' => 'SAVE20',
        ]);

        $response->assertRedirect();
        $this->assertEquals('SAVE20', session('cart_coupon'));
    }

    public function test_customer_can_complete_checkout_order_and_provision_services()
    {
        $user = User::create([
            'name' => 'David Kamau',
            'email' => 'david@testsite.co.ke',
            'phone' => '+254712345678',
            'role' => 'customer',
            'password' => bcrypt('password123'),
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

        session()->put('cart_plan_id', $plan->id);
        session()->put('cart_domains', ['mybrand.co.ke' => 990.00]);

        $response = $this->actingAs($user)->post(route('checkout.process'), [
            'payment_method' => 'mpesa',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('invoices', [
            'user_id' => $user->id,
            'status' => 'paid',
        ]);

        $this->assertDatabaseHas('hosting_services', [
            'user_id' => $user->id,
            'domain_name' => 'mybrand.co.ke',
            'status' => 'active',
        ]);

        $this->assertDatabaseHas('domains', [
            'user_id' => $user->id,
            'domain_name' => 'mybrand.co.ke',
            'status' => 'active',
        ]);
    }
}
