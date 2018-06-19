<?php

namespace Optimus\Pages;

use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Page extends Model implements HasMedia
{
    use NodeTrait, HasMediaTrait;

    protected $fillable = [
        'title', 'slug', 'template_id', 'parent_id', 'is_stand_alone', 'is_published'
    ];

    public function getUri()
    {
        return $this->ancestors()
            ->pluck('slug')
            ->merge([$this->slug])
            ->implode('/');
    }

    public function template()
    {
        return $this->belongsTo(PageTemplate::class);
    }

    public function contents()
    {
        return $this->hasMany(PageContent::class);
    }

    public function createContents(array $contents)
    {
        $records = [];

        foreach ($contents as $key => $value) {
            $records[] = [
                'key' => $key,
                'value' => $value,
                'template_id' => $this->template_id
            ];
        }

        $this->contents()->createMany($records);
    }

    public function hasContent($key)
    {
        return $this->contents->contains('key', $key);
    }

    public function getContent($key)
    {
        if (! $this->hasContent($key)) {
            return null;
        }

        return $this->contents->firstWhere('key', $key)->value;
    }

    public function deleteContents(int $templateId = null)
    {
        $this->contents()->when(
            $templateId, function ($query) use ($templateId) {
                $query->where('template_id', $templateId);
            }
        )->delete();
    }
}
