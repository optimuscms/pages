<?php

namespace Optimus\Pages\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class PageContent extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'key' => $this->key,
            'value' => $this->value,
            'template_id' => $this->template_id,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at
        ];
    }
}
