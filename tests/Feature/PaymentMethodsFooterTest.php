<?php

namespace Tests\Feature;

use App\Models\PaymentMethod;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentMethodsFooterTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_methods_can_be_seeded_and_fetched()
    {
        PaymentMethod::create([
            'name' => 'Visa',
            'code' => 'visa',
            'category' => 'cards',
            'icon_svg' => '<svg viewBox="0 0 36 24"></svg>',
            'is_enabled' => true,
            'sort_order' => 1,
        ]);

        PaymentMethod::create([
            'name' => 'Disabled Gateway',
            'code' => 'disabled_gw',
            'category' => 'cards',
            'icon_svg' => '<svg viewBox="0 0 36 24"></svg>',
            'is_enabled' => false,
            'sort_order' => 2,
        ]);

        $enabled = PaymentMethod::getEnabled();
        $this->assertCount(1, $enabled);
        $this->assertEquals('Visa', $enabled->first()->name);
    }

    public function test_admin_can_toggle_payment_method()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        $pm = PaymentMethod::create([
            'name' => 'M-Pesa Express',
            'code' => 'mpesa',
            'category' => 'mobile',
            'icon_svg' => '<svg viewBox="0 0 36 24"></svg>',
            'is_enabled' => true,
            'sort_order' => 1,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.payment-methods.toggle', $pm->id));
        $response->assertRedirect();
        $this->assertFalse($pm->fresh()->is_enabled);

        $response2 = $this->actingAs($admin)->post(route('admin.payment-methods.toggle', $pm->id));
        $response2->assertRedirect();
        $this->assertTrue($pm->fresh()->is_enabled);
    }

    public function test_admin_can_create_custom_payment_method()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($admin)->post(route('admin.payment-methods.create'), [
            'name' => 'Klarna',
            'code' => 'klarna',
            'category' => 'wallets',
            'sort_order' => 15,
            'icon_svg' => '<svg class="w-auto h-7" viewBox="0 0 36 24"><rect width="36" height="24" rx="4" fill="#FFB3C7"/></svg>',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('payment_methods', [
            'code' => 'klarna',
            'name' => 'Klarna',
        ]);
    }

    public function test_footer_displays_enabled_payment_methods()
    {
        Setting::setKey('show_footer_payment_methods', '1', 'payment');

        PaymentMethod::create([
            'name' => 'M-Pesa Express',
            'code' => 'mpesa',
            'category' => 'mobile',
            'icon_svg' => '<svg viewBox="0 0 36 24"><text>MPESA_LOGO</text></svg>',
            'is_enabled' => true,
            'sort_order' => 1,
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Accepted Payment Methods');
        $response->assertSee('Secure payments powered by trusted global and local payment providers.');
        $response->assertSee('M-Pesa Express');
    }
}
