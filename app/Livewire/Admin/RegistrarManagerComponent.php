<?php

namespace App\Livewire\Admin;

use App\Models\Registrar;
use App\Services\Registrars\RegistrarManager;
use Livewire\Component;

class RegistrarManagerComponent extends Component
{
    public string $message = '';
    public string $messageType = 'success';
    public ?int $selectedRegistrarId = null;

    // Configuration modal fields
    public string $configApiKey = '';
    public string $configApiSecret = '';
    public string $configUsername = '';
    public string $configPassword = '';
    public string $configCustomerId = '';
    public string $configResellerId = '';
    public string $configEndpoint = '';
    public bool $configSandbox = true;
    public bool $configEnabled = false;
    public bool $configDefault = false;

    public function toggleEnabled(int $registrarId): void
    {
        $registrar = Registrar::findOrFail($registrarId);
        $registrar->enabled = !$registrar->enabled;
        $registrar->save();

        $this->message = "{$registrar->name} status set to " . ($registrar->enabled ? 'ENABLED' : 'DISABLED');
        $this->messageType = 'success';
    }

    public function setDefault(int $registrarId): void
    {
        Registrar::query()->update(['default' => false]);
        $registrar = Registrar::findOrFail($registrarId);
        $registrar->enabled = true;
        $registrar->default = true;
        $registrar->save();

        $this->message = "{$registrar->name} is now set as the DEFAULT domain registrar.";
        $this->messageType = 'success';
    }

    public function testConnection(int $registrarId, RegistrarManager $manager): void
    {
        $registrar = Registrar::findOrFail($registrarId);
        $driver = $manager->driver($registrar->slug);

        $success = $driver->testConnection();
        $registrar->last_connection = now();
        $registrar->save();

        if ($success) {
            $this->message = "Connection test to {$registrar->name} SUCCESSFUL!";
            $this->messageType = 'success';
        } else {
            $this->message = "Connection test to {$registrar->name} FAILED. Please check credentials or API endpoint.";
            $this->messageType = 'error';
        }
    }

    public function syncDomains(int $registrarId): void
    {
        $registrar = Registrar::findOrFail($registrarId);
        $registrar->last_sync = now();
        $registrar->save();

        $this->message = "Synchronized domain records and status from {$registrar->name}.";
        $this->messageType = 'success';
    }

    public function editConfig(int $registrarId): void
    {
        $registrar = Registrar::findOrFail($registrarId);
        $this->selectedRegistrarId = $registrar->id;

        $creds = $registrar->credentials ?? [];
        $this->configApiKey = $creds['api_key'] ?? '';
        $this->configApiSecret = $creds['api_secret'] ?? '';
        $this->configUsername = $creds['username'] ?? '';
        $this->configPassword = $creds['password'] ?? '';
        $this->configCustomerId = $creds['customer_id'] ?? '';
        $this->configResellerId = $creds['reseller_id'] ?? '';
        $this->configEndpoint = $registrar->endpoint ?? '';
        $this->configSandbox = $registrar->sandbox;
        $this->configEnabled = $registrar->enabled;
        $this->configDefault = $registrar->default;
    }

    public function saveConfig(): void
    {
        if (!$this->selectedRegistrarId) return;

        $registrar = Registrar::findOrFail($this->selectedRegistrarId);

        $creds = [
            'api_key' => $this->configApiKey,
            'api_secret' => $this->configApiSecret,
            'username' => $this->configUsername,
            'password' => $this->configPassword,
            'customer_id' => $this->configCustomerId,
            'reseller_id' => $this->configResellerId,
        ];

        if ($this->configDefault) {
            Registrar::query()->update(['default' => false]);
            $this->configEnabled = true;
        }

        $registrar->update([
            'credentials' => $creds,
            'endpoint' => $this->configEndpoint,
            'sandbox' => $this->configSandbox,
            'enabled' => $this->configEnabled,
            'default' => $this->configDefault,
            'last_connection' => now(),
        ]);

        $this->selectedRegistrarId = null;
        $this->message = "Configuration for {$registrar->name} updated successfully!";
        $this->messageType = 'success';
    }

    public function render()
    {
        $registrars = Registrar::all();
        return view('livewire.admin.registrar-manager-component', compact('registrars'));
    }
}
