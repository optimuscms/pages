<?php

namespace Optimus\Pages\Models;

use Optix\Media\HasMedia;
use Illuminate\Http\Request;
use Spatie\Sluggable\HasSlug;
use Optix\Draftable\Draftable;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Page extends Model
{
    use Draftable,
        HasMedia,
        HasSlug;

    protected $casts = [
        'has_fixed_template' => 'bool',
        'has_fixed_uri' => 'bool',
        'is_deletable' => 'bool',
        'is_stand_alone' => 'bool'
    ];

    protected $dates = ['published_at'];

    protected $fillable = [
        'title',
        'slug',
        'template_id',
        'parent_id',
        'is_stand_alone',
        'order'
    ];

    public function scopeFilter(Builder $query, Request $request)
    {
        // Parent
        if ($request->filled('parent')) {
            $parent = $request->input('parent');
            $query->where('parent_id', $parent === 'root' ? null : $parent);
        }
    }

    public function getSlugOptions(): SlugOptions
    {
        $options = SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');

        if ($this->has_fixed_uri) {
            $options->doNotGenerateSlugsOnUpdate();
        }

        return $options;
    }

    protected function otherRecordExistsWithSlug(string $slug): bool
    {
        return static::where($this->slugOptions->slugField, $slug)
            ->where($this->getKeyName(), '!=', $this->getKey() ?? '0')
            ->where('parent_id', $this->parent_id)
            ->withoutGlobalScopes()
            ->exists();
    }

    public static function findByUriOrFail($uri)
    {
        $uri = (! $uri || $uri === '/') ? null : $uri;

        return static::query()->where('uri', $uri)->firstOrFail();
    }

    public function generateUri()
    {
        $prefix = '';

        $parent = $this->parent;

        if ($parent && $prefix = $parent->uri) {
            $prefix .= '/';
        }

        return $prefix . $this->slug;
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

    public function getContent($key, $default = null)
    {
        if (! $this->hasContent($key)) {
            return $default;
        }

        $content = $this->contents->firstWhere('key', $key);

        return $content->value;
    }

    public function deleteContents()
    {
        $this->contents()->delete();
    }

    public function parent()
    {
        return $this->belongsTo(Page::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Page::class, 'parent_id');
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
