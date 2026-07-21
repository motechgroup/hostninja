<?php

namespace Tests\Feature;

use App\Livewire\Admin\RegistrarLogsComponent;
use App\Livewire\Admin\RegistrarManagerComponent;
use App\Models\Domain;
use App\Models\Registrar;
use App\Models\User;
use App\Services\Registrars\Drivers\ResellerClubDriver;
use App\Services\Registrars\RegistrarManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Livewire\Livewire;
use Tests\TestCase;

class RegistrarSystemTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_registrar_manager_resolves_strategy_pattern_driver(): void
    {
        $manager = app(RegistrarManager::class);
        $driver = $manager->driver('resellerclub');

        $this->assertInstanceOf(ResellerClubDriver::class, $driver);
    }

    public function test_credentials_are_encrypted_in_database(): void
    {
        $registrar = Registrar::where('slug', 'resellerclub')->first();
        $creds = $registrar->credentials;

        $this->assertIsArray($creds);
        $this->assertEquals('984124', $creds['reseller_id']);
    }

    public function test_admin_can_access_registrars_page_and_test_connection(): void
    {
        $admin = User::where('role', 'admin')->first();
        $registrar = Registrar::where('slug', 'resellerclub')->first();

        $response = $this->actingAs($admin)->get('/admin/integrations/registrars');
        $response->assertStatus(200);

        Livewire::test(RegistrarManagerComponent::class)
            ->call('testConnection', $registrar->id)
            ->assertSet('messageType', 'success');
    }

    public function test_admin_can_update_registrar_credentials_via_livewire(): void
    {
        $registrar = Registrar::where('slug', 'namesilo')->first();

        Livewire::test(RegistrarManagerComponent::class)
            ->call('editConfig', $registrar->id)
            ->set('configApiKey', 'new_secret_key_999')
            ->call('saveConfig')
            ->assertSet('messageType', 'success');

        $updated = Registrar::find($registrar->id);
        $this->assertEquals('new_secret_key_999', $updated->credentials['api_key']);
    }

    public function test_admin_can_view_and_filter_api_audit_logs(): void
    {
        $admin = User::where('role', 'admin')->first();
        $response = $this->actingAs($admin)->get('/admin/integrations/registrar-logs');
        $response->assertStatus(200);

        Livewire::test(RegistrarLogsComponent::class)
            ->set('search', 'checkAvailability')
            ->assertStatus(200);
    }

    public function test_customer_can_toggle_whois_privacy(): void
    {
        $customer = User::where('role', 'customer')->first();
        $domain = Domain::where('user_id', $customer->id)->first();
        $initialStatus = $domain->whois_privacy_enabled;

        $response = $this->actingAs($customer)
            ->post("/dashboard/domains/{$domain->id}/whois-privacy");

        $response->assertStatus(302);
        $this->assertEquals(!$initialStatus, $domain->fresh()->whois_privacy_enabled);
    }

    public function test_sync_registrars_console_command(): void
    {
        $exitCode = Artisan::call('registrars:sync');
        $this->assertEquals(0, $exitCode);
    }
}
