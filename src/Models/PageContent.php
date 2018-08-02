<?php

namespace Optimus\Pages;

use Illuminate\Database\Eloquent\Model;

class PageContent extends Model
{
    protected $fillable = ['key', 'value'];
}
