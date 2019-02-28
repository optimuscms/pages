<?php

namespace Optimus\Pages\Facades;

use Illuminate\Support\Facades\Facade;

class Template extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \TemplateRepository::class;
    }
}
