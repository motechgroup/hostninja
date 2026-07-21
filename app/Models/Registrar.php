<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Registrar extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'description',
        'enabled',
        'default',
        'sandbox',
        'credentials',
        'endpoint',
        'webhook_secret',
        'supported_features',
        'last_connection',
        'last_sync',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'default' => 'boolean',
        'sandbox' => 'boolean',
        'credentials' => 'encrypted:array',
        'supported_features' => 'array',
        'last_connection' => 'datetime',
        'last_sync' => 'datetime',
    ];

    public function logs(): HasMany
    {
        return $this->hasMany(RegistrarApiLog::class);
    }

    public function domains(): HasMany
    {
        return $this->hasMany(Domain::class);
    }

    public static function getDefault(): ?self
    {
        return static::where('enabled', true)->where('default', true)->first() 
            ?? static::where('enabled', true)->first();
    }
}
