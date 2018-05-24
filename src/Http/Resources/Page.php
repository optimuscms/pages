<?php

namespace Optimus\Pages\Http\Resources;

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
            'parent' => $this->when($this->parent_id, function () {
                return [
                    'id' => $this->parent->id,
                    'title' => $this->parent->title,
                    'slug' => $this->parent->slug
                ];
            }),
            'template' => new PageTemplate($this->template),
            'has_fixed_template' => (bool) $this->has_fixed_template,
            'contents' => $this->templateContents->mapWithKeys(function ($content) {
                return [$content->key => $content->value];
            }),
            'children_count' => $this->when(
                ! is_null($this->children_count), $this->children_count
            ),
            'is_stand_alone' => (bool) $this->is_stand_alone,
            'is_published' => (bool) $this->is_published,
            'is_deletable' => (bool) $this->is_deletable,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at
        ];
    }
}
