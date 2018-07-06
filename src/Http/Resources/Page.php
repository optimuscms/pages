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
            'has_fixed_slug' => (bool) $this->has_fixed_slug,
            'uri' => $this->uri,
            'parent_id' => $this->parent_id,
            'template' => new PageTemplate($this->template),
            'has_fixed_template' => (bool) $this->has_fixed_template,
            'contents' => PageContent::collection($this->contents),
            'media' => Media::collection($this->media),
            'children_count' => $this->when(
                ! is_null($this->children_count), $this->children_count
            ),
            'is_stand_alone' => (bool) $this->is_stand_alone,
            'is_published' => (bool) ! is_null($this->published_at),
            'is_deletable' => (bool) $this->is_deletable,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at
        ];
    }
}
