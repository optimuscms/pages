<?php

namespace Optimus\Pages\Http\Resources;

use Optimus\Media\Http\Resources\Media;
use Illuminate\Http\Resources\Json\Resource;

class Page extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'has_fixed_slug' => $this->has_fixed_slug,
            'uri' => $this->uri,
            'parent_id' => $this->parent_id,
            'template' => new PageTemplate($this->template),
            'has_fixed_template' => $this->has_fixed_template,
            'contents' => PageContent::collection($this->contents),
            'media' => Media::collection($this->media),
            'children_count' => $this->children_count,
            'meta' => [
                'title' => $this->getMeta('title'),
                'description' => $this->getMeta('description')
            ],
            'is_stand_alone' => $this->is_stand_alone,
            'is_published' => ! is_null($this->published_at),
            'is_deletable' => $this->is_deletable,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at
        ];
    }
}
