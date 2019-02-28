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
     * Get all the registered templates.
     *
     * @return \Optimus\Pages\Template[]
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
                return $template->selectable;
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
    public function find(string $name)
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
     * @param  \Optimus\Pages\Template  $template
     * @return void
     */
    public function register(Template $template)
    {
        $this->templates[] = $template;
    }

    /**
     * Register multiple template classes.
     *
     * @param  \Optimus\Pages\Template[]  $templates
     * @return void
     */
    public function registerMany(Template ...$templates)
    {
        foreach ($templates as $template) {
            $this->register($template);
        }
    }
}
