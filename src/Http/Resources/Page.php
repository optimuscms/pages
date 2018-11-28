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
            'uri' => $this->uri,
            'has_fixed_uri' => $this->has_fixed_uri,
            'parent_id' => $this->parent_id,
            'template_id' => $this->template_id,
            'has_fixed_template' => $this->has_fixed_template,
            'contents' => PageContent::collection($this->contents),
            'media' => Media::collection($this->media),
            'children_count' => $this->when(
                ! is_null($this->children_count), $this->children_count
            ),
            'is_stand_alone' => $this->is_stand_alone,
            'is_published' => $this->isPublished(),
            'is_deletable' => $this->is_deletable,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at
        ];
    }
}
