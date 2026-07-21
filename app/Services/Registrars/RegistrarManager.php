<?php

namespace App\Services\Registrars;

use App\Models\Registrar;
use App\Services\Registrars\Contracts\RegistrarInterface;
use App\Services\Registrars\Drivers\ENomDriver;
use App\Services\Registrars\Drivers\NameSiloDriver;
use App\Services\Registrars\Drivers\OpenProviderDriver;
use App\Services\Registrars\Drivers\OpenSRSDriver;
use App\Services\Registrars\Drivers\ResellerClubDriver;
use InvalidArgumentException;

class RegistrarManager
{
    protected array $drivers = [
        'resellerclub' => ResellerClubDriver::class,
        'openprovider' => OpenProviderDriver::class,
        'namesilo' => NameSiloDriver::class,
        'opensrs' => OpenSRSDriver::class,
        'enom' => ENomDriver::class,
    ];

    protected array $resolvedInstances = [];

    /**
     * Get instance of specified driver or the active default registrar.
     */
    public function driver(?string $slug = null): RegistrarInterface
    {
        if ($slug && isset($this->resolvedInstances[$slug])) {
            return $this->resolvedInstances[$slug];
        }

        $registrarModel = null;

        if ($slug) {
            $registrarModel = Registrar::where('slug', $slug)->first();
        } else {
            $registrarModel = Registrar::getDefault();
        }

        $driverSlug = $registrarModel?->slug ?? $slug ?? 'resellerclub';

        if (!isset($this->drivers[$driverSlug])) {
            $driverSlug = 'resellerclub';
        }

        $driverClass = $this->drivers[$driverSlug];
        $instance = new $driverClass($registrarModel);

        if ($slug) {
            $this->resolvedInstances[$slug] = $instance;
        }

        return $instance;
    }

    /**
     * Register a custom driver class dynamically.
     */
    public function extend(string $slug, string $driverClass): self
    {
        if (!is_subclass_of($driverClass, RegistrarInterface::class)) {
            throw new InvalidArgumentException("Driver [{$driverClass}] must implement RegistrarInterface.");
        }

        $this->drivers[$slug] = $driverClass;
        return $this;
    }

    /**
     * Get list of supported driver slugs.
     */
    public function getSupportedDrivers(): array
    {
        return array_keys($this->drivers);
    }
}
