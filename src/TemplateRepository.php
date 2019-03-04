<?php

namespace Optimus\Pages;

use InvalidArgumentException;

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
        $templates = array_where($this->all(), function ($template) {
            return $template->selectable();
        });

        return array_values($templates);
    }

    /**
     * Get the template with the given name.
     *
     * @throws \InvalidArgumentException
     *
     * @param  string  $name
     * @return \Optimus\Pages\Template
     */
    public function find(string $name)
    {
        $template = array_first($this->all(), function ($template) use ($name) {
            return $name === $template->name();
        });

        if (! $template) {
            throw new InvalidArgumentException(
                "A template with the name `{$name}` has not been registered."
            );
        }

        return $template;
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
     * @param  array  $templates
     * @return void
     */
    public function registerMany(array $templates)
    {
        foreach ($templates as $template) {
            $this->register($template);
        }
    }
}
