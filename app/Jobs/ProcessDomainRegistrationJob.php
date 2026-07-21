<?php

namespace App\Jobs;

use App\Models\Domain;
use App\Models\Registrar;
use App\Services\Registrars\RegistrarManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class ProcessDomainRegistrationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 30;

    public function __construct(
        public Domain $domain,
        public int $years = 1,
        public ?string $registrarSlug = null
    ) {}

    public function handle(RegistrarManager $manager): void
    {
        $registrarModel = $this->registrarSlug 
            ? Registrar::where('slug', $this->registrarSlug)->first()
            : Registrar::getDefault();

        $driver = $manager->driver($registrarModel?->slug);

        $params = [
            'domain' => $this->domain->domain_name,
            'years' => $this->years,
            'nameservers' => $this->domain->nameservers ?? ['ns1.hostninja.cloud', 'ns2.hostninja.cloud'],
            'user' => [
                'name' => $this->domain->user->name ?? 'Customer',
                'email' => $this->domain->user->email ?? 'customer@hostninja.cloud',
                'phone' => $this->domain->user->phone ?? '+254700000000',
            ],
        ];

        $result = $driver->registerDomain($params);

        if (!empty($result['success'])) {
            $this->domain->update([
                'registrar_id' => $registrarModel?->id,
                'registrar' => $registrarModel?->name ?? 'HostNinja Registrar',
                'registrar_domain_id' => $result['registrar_domain_id'] ?? null,
                'status' => 'active',
                'expiry_date' => $result['expiry_date'] ?? now()->addYears($this->years)->toDateString(),
                'last_synced_at' => now(),
            ]);
        } else {
            throw new \RuntimeException($result['error'] ?? 'Domain registration API failed');
        }
    }

    public function failed(Throwable $exception): void
    {
        $this->domain->update(['status' => 'registration_failed']);
    }
}
