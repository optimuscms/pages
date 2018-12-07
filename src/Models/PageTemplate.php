<?php

namespace Optimus\Pages\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PageTemplate extends Model
{
    protected $casts = [
        'is_selectable' => 'bool'
    ];

    protected $fillable = [
        'label', 'name', 'is_selectable'
    ];

    public function scopeFilter(Builder $query, Request $request)
    {
        // Selectable
        if ($request->filled('is_selectable')) {
            $query->where('is_selectable', $request->input('is_selectable'));
        }
    }

    public function getHandlerAttribute($className)
    {
        return app($className);
    }
}
