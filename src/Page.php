<?php

namespace Optimus\Pages;

use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Page extends Model
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

    public function templateContents()
    {
        return $this->contents()
            ->select('page_contents.*')
            ->join('pages', function ($join) {
                $join->on('page_contents.page_id', 'pages.id')
                     ->whereRaw('pages.template_id = page_contents.template_id');
            });
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

    public function deleteContents(int $templateId = null)
    {
        $this->contents()->when(
            $templateId, function ($query) use ($templateId) {
                $query->where('template_id', $templateId);
            }
        )->delete();
    }
}
