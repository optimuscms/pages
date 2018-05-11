<?php

namespace Optimus\Pages;

use Illuminate\Database\Eloquent\Model;

class PageContent extends Model
{
    protected $fillable = [
        'template_id', 'key', 'value'
    ];

    public function template()
    {
        return $this->belongsTo(PageTemplate::class);
    }
}
