<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResellerCommission extends Model
{
    protected $fillable = [
        'reseller_id',
        'client_id',
        'service_name',
        'sale_amount',
        'commission_amount',
        'status',
    ];

    protected $casts = [
        'sale_amount' => 'decimal:2',
        'commission_amount' => 'decimal:2',
    ];

    public function reseller()
    {
        return $this->belongsTo(User::class, 'reseller_id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}
