<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Domain extends Model
{
    protected $fillable = [
        'user_id',
        'registrar_id',
        'registrar_domain_id',
        'domain_name',
        'extension',
        'registration_date',
        'expiry_date',
        'status',
        'registrar',
        'price',
        'auto_renew',
        'is_locked',
        'whois_privacy_enabled',
        'dnssec_enabled',
        'nameservers',
        'dns_records',
        'glue_records',
        'whois_info',
        'last_synced_at',
    ];

    protected $casts = [
        'registration_date' => 'date',
        'expiry_date' => 'date',
        'auto_renew' => 'boolean',
        'is_locked' => 'boolean',
        'whois_privacy_enabled' => 'boolean',
        'dnssec_enabled' => 'boolean',
        'nameservers' => 'array',
        'dns_records' => 'array',
        'glue_records' => 'array',
        'whois_info' => 'array',
        'price' => 'decimal:2',
        'last_synced_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function registrarRecord(): BelongsTo
    {
        return $this->belongsTo(Registrar::class, 'registrar_id');
    }
}
