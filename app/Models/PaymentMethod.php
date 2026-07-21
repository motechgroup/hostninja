<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'name',
        'code',
        'category',
        'icon_svg',
        'is_enabled',
        'show_in_footer',
        'sort_order',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'show_in_footer' => 'boolean',
        'sort_order' => 'integer',
    ];

    public static function getEnabled()
    {
        return static::where('is_enabled', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('name', 'asc')
            ->get();
    }

    public static function getEnabledForFooter()
    {
        return static::where('is_enabled', true)
            ->where('show_in_footer', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('name', 'asc')
            ->get();
    }
}
