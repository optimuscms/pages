<?php

namespace Optimus\Pages;

use Illuminate\Database\Eloquent\Model;

class PageTemplate extends Model
{
    protected $fillable = [
        'name', 'slug', 'is_selectable'
    ];

    public function getHandlerAttribute($className)
    {
        return app($className);
    }
}
