<?php

namespace Optimus\Pages\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Template extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'is_selectable' => (bool) $this->is_selectable
        ];
    }
}
