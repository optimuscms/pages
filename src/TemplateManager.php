<?php

namespace Optimus\Pages;

class TemplateManager
{
    protected $templates;

    public function __construct()
    {
        $this->templates = new TemplateCollection();
    }

    public function registered()
    {
        return $this->templates;
    }

    public function register(string $class)
    {
        if (! class_exists($class)) {
            // throw new Exception();
            return;
        }

        if (! is_subclass_of($class, Template::class)) {
            // throw new Exception();
            return;
        }

        $this->templates[] = new $class();
    }

    public function registerMany(array $classes)
    {
        foreach ($classes as $class) {
            $this->register($class);
        }
    }
}
