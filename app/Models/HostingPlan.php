<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HostingPlan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'tagline',
        'price_monthly',
        'price_yearly',
        'storage_gb',
        'bandwidth_gb',
        'email_accounts',
        'databases',
        'ssl_free',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'ssl_free' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'price_monthly' => 'decimal:2',
        'price_yearly' => 'decimal:2',
    ];

    public function services()
    {
        return $this->hasMany(HostingService::class);
    }
}
