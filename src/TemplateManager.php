<?php

namespace Optimus\Pages;

class TemplateManager
{
    /**
     * @var \Optimus\Pages\TemplateCollection
     */
    protected $templates;

    /**
     * Create a new TemplateManager instance.
     */
    public function __construct()
    {
        $this->templates = new TemplateCollection();
    }

    /**
     * Get all of the registered templates.
     *
     * @return \Optimus\Pages\TemplateCollection
     */
    public function all(): TemplateCollection
    {
        return $this->templates;
    }

    /**
     * Get all of the selectable templates.
     *
     * @return \Optimus\Pages\TemplateCollection
     */
    public function selectable(): TemplateCollection
    {
        return $this->templates->selectable();
    }

    /**
     * Get the template with the given name.
     *
     * @param  string  $name
     * @return \Optimus\Pages\Template
     */
    public function find(string $name): Template
    {
        return $this->templates->find($name);
    }

    /**
     * Register a template.
     *
     * @param  string  $class
     * @return void
     */
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

        $this->templates->add(new $class());
    }

    /**
     * Register multiple templates.
     *
     * @param  array  $classes
     * @return void
     */
    public function registerMany(array $classes)
    {
        foreach ($classes as $class) {
            $this->register($class);
        }
    }
}
