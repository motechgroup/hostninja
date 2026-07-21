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
        'credentials',
        'sort_order',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'show_in_footer' => 'boolean',
        'credentials' => 'array',
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

    public function getCredential(string $key, $default = null)
    {
        $creds = $this->credentials ?? [];
        return $creds[$key] ?? $default;
    }

    public function getLogoHtmlAttribute()
    {
        $content = trim($this->icon_svg ?? '');
        if (empty($content)) {
            return '<span class="material-symbols-outlined text-slate-400 text-xl">payments</span>';
        }

        if (str_starts_with($content, '<svg') || str_starts_with($content, '<')) {
            return $content;
        }

        $url = (str_starts_with($content, 'http://') || str_starts_with($content, 'https://')) ? $content : asset($content);
        return '<img src="' . e($url) . '" alt="' . e($this->name) . '" class="w-auto h-7 max-h-7 object-contain inline-block" />';
    }
}
