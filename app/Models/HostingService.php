<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HostingService extends Model
{
    protected $fillable = [
        'user_id',
        'hosting_plan_id',
        'server_id',
        'domain_name',
        'username',
        'status',
        'billing_cycle',
        'amount',
        'next_due_date',
    ];

    protected $casts = [
        'next_due_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hostingPlan()
    {
        return $this->belongsTo(HostingPlan::class);
    }

    public function server()
    {
        return $this->belongsTo(Server::class);
    }
}
