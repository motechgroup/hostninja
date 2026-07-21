<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HostingControlPanel extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'logo',
        'description',
        'official_url',
        'featured',
        'enabled',
        'display_order',
    ];

    protected $casts = [
        'featured' => 'boolean',
        'enabled' => 'boolean',
        'display_order' => 'integer',
    ];

    public static function getEnabledOrdered()
    {
        return static::where('enabled', true)
            ->orderBy('featured', 'desc')
            ->orderBy('display_order', 'asc')
            ->orderBy('name', 'asc')
            ->get();
    }

    public function getLogoHtmlAttribute()
    {
        $content = trim($this->logo ?? '');
        if (empty($content)) {
            return '<span class="material-symbols-outlined text-slate-400 text-2xl">dns</span>';
        }

        if (str_starts_with($content, '<svg') || str_starts_with($content, '<')) {
            return $content;
        }

        $url = (str_starts_with($content, 'http://') || str_starts_with($content, 'https://')) ? $content : asset($content);
        return '<img src="' . e($url) . '" alt="' . e($this->name) . ' logo" loading="lazy" class="h-10 w-auto max-h-10 object-contain inline-block" />';
    }
}
