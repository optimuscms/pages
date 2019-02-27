<?php

namespace Optimus\Pages\Facades;

use Optimus\Pages\TemplateManager;
use Illuminate\Support\Facades\Facade;

class Template extends Facade
{
    protected static function getFacadeAccessor()
    {
        return TemplateManager::class;
    }
}
