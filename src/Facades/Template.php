<?php

namespace Optimus\Pages\Facades;

use Optimus\Pages\TemplateRegistry;
use Illuminate\Support\Facades\Facade;

class Template extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return TemplateRegistry::class;
    }
}
