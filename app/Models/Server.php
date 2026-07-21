<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    protected $fillable = [
        'name',
        'ip_address',
        'hostname',
        'type',
        'status',
        'active_accounts',
        'max_accounts',
        'disk_usage_percent',
        'cpu_usage_percent',
    ];

    public function hostingServices()
    {
        return $this->hasMany(HostingService::class);
    }
}
