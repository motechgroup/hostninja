<?php

namespace Tests\Feature;

use App\Models\HostingControlPanel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ControlPanelsTest extends TestCase
{
    use RefreshDatabase;

    public function test_control_panels_seeder_creates_default_panels()
    {
        $this->seed();

        $this->assertDatabaseHas('hosting_control_panels', ['slug' => 'cpanel-whm', 'featured' => true]);
        $this->assertDatabaseHas('hosting_control_panels', ['slug' => 'plesk', 'featured' => true]);
        $this->assertDatabaseHas('hosting_control_panels', ['slug' => 'directadmin']);
        $this->assertDatabaseHas('hosting_control_panels', ['slug' => 'cyberpanel']);
        $this->assertDatabaseHas('hosting_control_panels', ['slug' => 'cloudpanel']);

        $panels = HostingControlPanel::all();
        $this->assertGreaterThanOrEqual(10, $panels->count());
    }

    public function test_homepage_displays_enabled_control_panels_in_order()
    {
        HostingControlPanel::create([
            'name' => 'Proxmox VE',
            'slug' => 'proxmox-ve',
            'description' => 'Enterprise Open-Source Server Virtualization.',
            'official_url' => 'https://proxmox.com',
            'featured' => true,
            'enabled' => true,
            'display_order' => 1,
            'logo' => '<svg viewBox="0 0 120 32"><text>PROXMOX</text></svg>',
        ]);

        HostingControlPanel::create([
            'name' => 'Disabled Panel',
            'slug' => 'disabled-panel',
            'description' => 'Should not appear',
            'featured' => false,
            'enabled' => false,
            'display_order' => 2,
            'logo' => '<svg viewBox="0 0 120 32"><text>DISABLED</text></svg>',
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Proxmox VE');
        $response->assertSee('Enterprise Open-Source Server Virtualization.');
        $response->assertSee('https://proxmox.com');
        $response->assertDontSee('Disabled Panel');
    }

    public function test_admin_can_access_control_panels_console()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin_cp@test.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.control-panels'));
        $response->assertStatus(200);
        $response->assertSee('Supported Hosting Control Panels');
    }

    public function test_admin_can_add_new_control_panel()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin_cp2@test.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($admin)->post(route('admin.control-panels.create'), [
            'name' => 'WHMCS Billing Platform',
            'slug' => 'whmcs',
            'description' => 'All-in-one client management, billing and support solution for web hosts.',
            'official_url' => 'https://whmcs.com',
            'display_order' => 5,
            'featured' => '1',
            'logo' => '<svg viewBox="0 0 120 32"><text>WHMCS</text></svg>',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('hosting_control_panels', [
            'slug' => 'whmcs',
            'name' => 'WHMCS Billing Platform',
            'featured' => true,
            'enabled' => true,
        ]);
    }

    public function test_admin_can_toggle_and_edit_control_panel()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin_cp3@test.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        $panel = HostingControlPanel::create([
            'name' => 'Plesk Control Panel',
            'slug' => 'plesk-test',
            'description' => 'Initial description',
            'official_url' => 'https://plesk.com',
            'featured' => false,
            'enabled' => true,
            'display_order' => 1,
        ]);

        // Toggle Enabled
        $this->actingAs($admin)->post(route('admin.control-panels.toggle', $panel->id));
        $this->assertFalse($panel->fresh()->enabled);

        // Toggle Featured
        $this->actingAs($admin)->post(route('admin.control-panels.toggle-featured', $panel->id));
        $this->assertTrue($panel->fresh()->featured);

        // Update Details
        $this->actingAs($admin)->post(route('admin.control-panels.update', $panel->id), [
            'name' => 'Plesk WebOps Edition',
            'description' => 'Updated description for WebOps',
            'official_url' => 'https://plesk.com/webops',
            'display_order' => 10,
            'featured' => '1',
        ]);

        $this->assertEquals('Plesk WebOps Edition', $panel->fresh()->name);
        $this->assertEquals('https://plesk.com/webops', $panel->fresh()->official_url);
    }

    public function test_admin_can_delete_control_panel()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin_cp4@test.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        $panel = HostingControlPanel::create([
            'name' => 'Temp Panel',
            'slug' => 'temp-panel',
            'display_order' => 99,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.control-panels.delete', $panel->id));
        $response->assertRedirect();
        $this->assertDatabaseMissing('hosting_control_panels', ['id' => $panel->id]);
    }
}
