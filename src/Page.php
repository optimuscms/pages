<?php

namespace Optimus\Pages;

use Optix\Media\HasMedia;
use Plank\Metable\Metable;
use Illuminate\Http\Request;
use Optix\Draftable\Draftable;
use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use Draftable, HasMedia, Metable, NodeTrait;

    protected $casts = [
        'has_fixed_template' => 'bool',
        'has_fixed_slug' => 'bool',
        'is_deletable' => 'bool',
        'is_stand_alone' => 'bool'
    ];

    protected $dates = ['published_at'];

    protected $fillable = [
        'title', 'slug', 'template_id', 'parent_id', 'is_stand_alone', 'order'
    ];

    public function getUri()
    {
        return $this->ancestors()
            ->pluck('slug')
            ->merge([$this->slug])
            ->implode('/');
    }

    public function scopeFilter($query, Request $request)
    {
        if ($request->filled('parent')) {
            $parent = $request->input('parent');
            $query->where('parent_id', $parent === 'root' ? null : $parent);
        }
    }

    public function addContents(array $contents)
    {
        $records = [];

        foreach ($contents as $key => $value) {
            $records[] = [
                'key' => $key,
                'value' => $value,
            ];
        }

        $this->contents()->createMany($records);
    }

    public function hasContent($key)
    {
        return $this->contents->contains(function ($content) use ($key) {
            return $content->key === $key && ! empty($content->value);
        });
    }

    public function getContent($key)
    {
        if (! $this->hasContent($key)) {
            return null;
        }

        return $this->contents->firstWhere('key', $key)->value;
    }

    public function deleteContents()
    {
        $this->contents()->delete();
    }

    public function template()
    {
        return $this->belongsTo(PageTemplate::class);
    }

    public function contents()
    {
        return $this->hasMany(PageContent::class);
    }
}
