<?php

namespace App\Console\Commands;

use App\Models\Domain;
use App\Models\Registrar;
use App\Services\Registrars\RegistrarManager;
use Illuminate\Console\Command;
use Throwable;

class SyncRegistrarsCommand extends Command
{
    protected $signature = 'registrars:sync {--registrar= : Specific registrar slug to sync}';
    protected $description = 'Synchronize domain status, expiry dates, WHOIS and nameservers with connected registrar APIs';

    public function handle(RegistrarManager $manager): int
    {
        $this->info('Starting Domain Registrar Synchronization...');

        $slug = $this->option('registrar');
        $query = Domain::where('status', 'active');

        if ($slug) {
            $reg = Registrar::where('slug', $slug)->first();
            if ($reg) {
                $query->where('registrar_id', $reg->id);
            }
        }

        $domains = $query->get();
        $synced = 0;

        foreach ($domains as $domain) {
            try {
                $driver = $manager->driver($domain->registrarRecord?->slug);
                $syncData = $driver->syncDomainStatus($domain->domain_name);

                $domain->update([
                    'status' => strtolower($syncData['status'] ?? $domain->status),
                    'expiry_date' => $syncData['expiry_date'] ?? $domain->expiry_date,
                    'nameservers' => $syncData['nameservers'] ?? $domain->nameservers,
                    'whois_privacy_enabled' => $syncData['whois_privacy'] ?? $domain->whois_privacy_enabled,
                    'is_locked' => $syncData['is_locked'] ?? $domain->is_locked,
                    'last_synced_at' => now(),
                ]);

                $synced++;
                $this->line("<info>Synced:</info> {$domain->domain_name}");
            } catch (Throwable $e) {
                $this->error("Failed to sync {$domain->domain_name}: {$e->getMessage()}");
            }
        }

        $this->info("Completed synchronization of {$synced} domains.");

        Registrar::where('enabled', true)->update(['last_sync' => now()]);

        return 0;
    }
}
