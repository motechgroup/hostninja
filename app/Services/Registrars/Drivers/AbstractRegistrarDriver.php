<?php

namespace App\Services\Registrars\Drivers;

use App\Models\Registrar;
use App\Models\RegistrarApiLog;
use App\Services\Registrars\Contracts\RegistrarInterface;
use Illuminate\Support\Facades\Http;
use Throwable;

abstract class AbstractRegistrarDriver implements RegistrarInterface
{
    protected ?Registrar $model = null;
    protected array $config = [];
    protected bool $sandbox = true;
    protected string $endpoint = '';

    public function __construct(?Registrar $model = null, array $config = [])
    {
        $this->model = $model;
        $this->config = $config ?: ($model?->credentials ?? []);
        $this->sandbox = $model ? $model->sandbox : ($config['sandbox'] ?? true);
        $this->endpoint = $model?->endpoint ?: $this->getDefaultEndpoint();
    }

    abstract protected function getDefaultEndpoint(): string;

    protected function logApiCall(
        string $action,
        ?string $endpoint,
        array $requestPayload,
        array $responsePayload,
        int $httpStatus = 200,
        int $executionTimeMs = 0,
        ?string $error = null,
        int $retries = 0
    ): void {
        try {
            RegistrarApiLog::create([
                'registrar_id' => $this->model?->id,
                'driver' => static::class,
                'action' => $action,
                'endpoint' => $endpoint ?: $this->endpoint,
                'request_payload' => $requestPayload,
                'response_payload' => $responsePayload,
                'http_status' => $httpStatus,
                'execution_time_ms' => $executionTimeMs,
                'error' => $error,
                'retries' => $retries,
            ]);
        } catch (Throwable $e) {
            // Silence logging errors to prevent breaking user flow
        }
    }

    public function testConnection(): bool
    {
        try {
            $info = $this->checkAvailability('connectiontest-' . time() . '.com');
            return isset($info['available']);
        } catch (Throwable $e) {
            return false;
        }
    }

    public function getTransferStatus(string $domain): array
    {
        return [
            'domain' => $domain,
            'status' => 'completed',
            'message' => 'Transfer is active or completed.',
        ];
    }

    public function getPremiumPrice(string $domain): ?float
    {
        return null;
    }

    public function syncDomainStatus(string $domain): array
    {
        $info = $this->getDomainInfo($domain);
        return [
            'status' => $info['status'] ?? 'active',
            'expiry_date' => $info['expiry_date'] ?? now()->addYear()->toDateString(),
            'nameservers' => $info['nameservers'] ?? ['ns1.hostninja.com', 'ns2.hostninja.com'],
            'whois_privacy' => $info['whois_privacy'] ?? false,
            'is_locked' => $info['is_locked'] ?? true,
        ];
    }

    public function getDnssec(string $domain): array
    {
        return [];
    }

    public function updateDnssec(string $domain, array $dsRecords): bool
    {
        return true;
    }

    public function getGlueRecords(string $domain): array
    {
        return [];
    }

    public function updateGlueRecords(string $domain, array $glueRecords): bool
    {
        return true;
    }

    public function setAutoRenew(string $domain, bool $autoRenew): bool
    {
        return true;
    }
}
