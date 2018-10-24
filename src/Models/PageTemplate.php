<?php

namespace Optimus\Pages\Models;

use Illuminate\Database\Eloquent\Model;

class PageTemplate extends Model
{
    protected $fillable = [
        'name', 'component_name', 'is_selectable'
    ];

    public function getHandlerAttribute($className)
    {
        return new $className;
    }
}
