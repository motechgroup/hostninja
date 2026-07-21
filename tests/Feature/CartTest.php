<?php

namespace Tests\Feature;

use App\Models\HostingPlan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_page_renders_successfully()
    {
        $response = $this->get(route('cart.index'));
        $response->assertStatus(200);
        $response->assertSee('Your Shopping Cart');
    }

    public function test_cart_page_displays_added_domains_and_plans()
    {
        $plan = HostingPlan::create([
            'name' => 'Business NVMe Plan',
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

        session()->put('cart_plan_id', $plan->id);
        session()->put('cart_domains', ['ninjasite.co.ke' => 990.00]);

        $response = $this->get(route('cart.index'));

        $response->assertStatus(200);
        $response->assertSee('Business NVMe Plan');
        $response->assertSee('ninjasite.co.ke');
    }

    public function test_user_can_clear_cart()
    {
        session()->put('cart_domains', ['ninjasite.co.ke' => 990.00]);

        $response = $this->post(route('cart.clear'));

        $response->assertRedirect();
        $this->assertEmpty(session('cart_domains', []));
    }
}
