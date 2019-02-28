<?php

namespace Optimus\Pages;

use Exception;
use Illuminate\Support\Arr;

class TemplateRepository
{
    /**
     * @var \Optimus\Pages\Template[]
     */
    protected $templates = [];

    /**
     * Create a new TemplateRepository instance.
     *
     * @throws Exception
     *
     * @param  array $classes
     */
    public function __construct(array $classes = [])
    {
        $this->registerMany($classes);
    }

    /**
     * Get all the registered templates.
     *
     * @return array
     */
    public function all()
    {
        return $this->templates;
    }

    /**
     * Get all the registered templates.
     *
     * @return array
     */
    public function selectable()
    {
        return Arr::where(
            $this->templates, function (Template $template) {
                return $template->isSelectable;
            }
        );
    }

    /**
     * Get the template with the given name.
     *
     * @throws Exception
     *
     * @param  string  $name
     * @return \Optimus\Pages\Template
     */
    public function get(string $name)
    {
        $value = Arr::first(
            $this->templates, function (Template $template) use ($name) {
                return $template->name === $name;
            }
        );

        if (! $value) {
            throw new Exception();
        }

        return $value;
    }

    /**
     * Register a template class.
     *
     * @throws Exception
     *
     * @param  string  $class
     * @return void
     */
    public function register(string $class)
    {
        if (! class_exists($class)) {
            throw new Exception();
        }

        if (! is_subclass_of($class, Template::class)) {
            throw new Exception();
        }

        $this->templates[] = new $class();
    }

    /**
     * Register multiple template classes.
     *
     * @throws Exception
     *
     * @param  array  $items
     * @return void
     */
    public function registerMany(array $items)
    {
        foreach ($items as $item) {
            $this->register($item);
        }
    }
}
