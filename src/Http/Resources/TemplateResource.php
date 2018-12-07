<?php

namespace Optimus\Pages\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class TemplateResource extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'label' => $this->label,
            'name' => $this->name,
            'is_selectable' => $this->is_selectable
        ];
    }
}
