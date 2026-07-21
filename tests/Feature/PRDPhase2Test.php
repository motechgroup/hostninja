<?php

namespace Tests\Feature;

use App\Livewire\DomainSearch;
use App\Models\Domain;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PRDPhase2Test extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\HostNinjaSeeder::class);
    }

    public function test_ai_domain_assistant_generates_suggestions(): void
    {
        Livewire::test(DomainSearch::class)
            ->set('aiPrompt', 'kenyan coffee tech')
            ->call('generateAiSuggestions')
            ->assertSet('hasAiGenerated', true)
            ->assertSee('AVAILABLE');
    }

    public function test_domain_search_renders_results(): void
    {
        Livewire::test(DomainSearch::class)
            ->set('query', 'mybrand')
            ->call('search')
            ->assertSet('hasSearched', true)
            ->assertSee('mybrand.co.ke');
    }

    public function test_public_header_does_not_contain_admin_console(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertDontSee('Admin Dashboard');
    }

    public function test_dedicated_admin_login_page_renders_and_authenticates(): void
    {
        $response = $this->get('/admin/login');
        $response->assertStatus(200);
        $response->assertSee('Admin Console Portal');

        $admin = User::where('role', 'admin')->first();

        $loginResponse = $this->post('/admin/login', [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $loginResponse->assertRedirect(route('admin.dashboard'));
    }

    public function test_non_admin_cannot_login_to_admin_portal(): void
    {
        $customer = User::where('role', 'customer')->first();

        $response = $this->post('/admin/login', [
            'email' => $customer->email,
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_admin_can_update_seo_keywords_and_smtp_settings(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->post('/admin/settings', [
            'seo_title' => 'Custom HostNinja Title',
            'seo_keywords' => 'cloud, hosting, kenya, nvme',
            'smtp_host' => 'smtp.customdomain.com',
            'smtp_port' => '587',
        ]);

        $response->assertStatus(302);
        $this->assertEquals('Custom HostNinja Title', Setting::getByKey('seo_title'));
        $this->assertEquals('cloud, hosting, kenya, nvme', Setting::getByKey('seo_keywords'));
    }

    public function test_customer_can_renew_domain(): void
    {
        $user = User::where('role', 'customer')->first();
        $domain = Domain::where('user_id', $user->id)->first();
        $oldExpiry = $domain->expiry_date;

        $response = $this->actingAs($user)
            ->post("/dashboard/domains/{$domain->id}/renew");

        $response->assertStatus(302);
        $this->assertTrue($domain->fresh()->expiry_date->gt($oldExpiry));
    }

    public function test_dedicated_admin_pages_accessible(): void
    {
        $admin = User::where('role', 'admin')->first();

        $this->actingAs($admin)->get('/admin')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/users')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/servers')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/plans')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/tickets')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/invoices')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/settings')->assertStatus(200);
    }

    public function test_dedicated_customer_pages_accessible(): void
    {
        $customer = User::where('role', 'customer')->first();

        $this->actingAs($customer)->get('/dashboard')->assertStatus(200);
        $this->actingAs($customer)->get('/dashboard/services')->assertStatus(200);
        $this->actingAs($customer)->get('/dashboard/domains')->assertStatus(200);
        $this->actingAs($customer)->get('/dashboard/invoices')->assertStatus(200);
        $this->actingAs($customer)->get('/dashboard/tickets')->assertStatus(200);
    }
}
