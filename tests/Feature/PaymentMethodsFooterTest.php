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

    public function test_admin_can_access_payment_gateways_dashboard()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.payment-gateways'));
        $response->assertStatus(200);
        $response->assertSee('Payment Gateways');
    }

    public function test_admin_can_update_payment_method()
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

        $response = $this->actingAs($admin)->post(route('admin.payment-methods.update', $pm->id), [
            'name' => 'M-Pesa STK Direct',
            'category' => 'mobile',
            'sort_order' => 5,
            'icon_svg' => '<svg viewBox="0 0 36 24"><text>NEW_SVG</text></svg>',
        ]);

        $response->assertRedirect();
        $this->assertEquals('M-Pesa STK Direct', $pm->fresh()->name);
        $this->assertEquals(5, $pm->fresh()->sort_order);
    }

    public function test_admin_can_toggle_footer_visibility_independently()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        $pm = PaymentMethod::create([
            'name' => 'Visa Card',
            'code' => 'visa',
            'category' => 'cards',
            'icon_svg' => '<svg viewBox="0 0 36 24"></svg>',
            'is_enabled' => true,
            'show_in_footer' => true,
            'sort_order' => 1,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.payment-methods.toggle-footer', $pm->id));
        $response->assertRedirect();
        $this->assertFalse($pm->fresh()->show_in_footer);
        $this->assertTrue($pm->fresh()->is_enabled); // Payment method active, but hidden from footer

        $enabledForFooter = PaymentMethod::getEnabledForFooter();
        $this->assertCount(0, $enabledForFooter);
    }

    public function test_payment_method_logo_html_accessor_supports_images_and_svgs()
    {
        $svgMethod = PaymentMethod::create([
            'name' => 'SVG Method',
            'code' => 'svg_method',
            'category' => 'cards',
            'icon_svg' => '<svg viewBox="0 0 36 24"><text>TEST_SVG</text></svg>',
            'is_enabled' => true,
        ]);

        $imgMethod = PaymentMethod::create([
            'name' => 'Uploaded Image Method',
            'code' => 'img_method',
            'category' => 'cards',
            'icon_svg' => 'images/payment_logos/test_logo.png',
            'is_enabled' => true,
        ]);

        $this->assertStringContainsString('<svg', $svgMethod->logo_html);
        $this->assertStringContainsString('<img src=', $imgMethod->logo_html);
        $this->assertStringContainsString('images/payment_logos/test_logo.png', $imgMethod->logo_html);
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
            'show_in_footer' => true,
            'sort_order' => 1,
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Accepted Payment Methods');
        $response->assertSee('Secure payments powered by trusted global and local payment providers.');
        $response->assertSee('M-Pesa Express');
    }
}
