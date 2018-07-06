<?php

namespace Optimus\Pages;

use Illuminate\Database\Eloquent\Model;

class PageTemplate extends Model
{
    protected $fillable = [
        'name', 'component', 'is_selectable'
    ];

    public function getHandlerAttribute($className)
    {
        return app($className);
    }
}
