<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistrarApiLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'registrar_id',
        'driver',
        'action',
        'endpoint',
        'request_payload',
        'response_payload',
        'http_status',
        'execution_time_ms',
        'error',
        'retries',
    ];

    protected $casts = [
        'request_payload' => 'array',
        'response_payload' => 'array',
        'http_status' => 'integer',
        'execution_time_ms' => 'integer',
        'retries' => 'integer',
    ];

    public function registrar(): BelongsTo
    {
        return $this->belongsTo(Registrar::class);
    }
}
