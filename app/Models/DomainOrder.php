<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DomainOrder extends Model
{
    protected $fillable = [
        'user_id',
        'domain_name',
        'action_type',
        'years',
        'amount',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
