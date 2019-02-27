<?php

namespace Optimus\Pages;

use Illuminate\Support\Collection;

class TemplateCollection extends Collection
{
    public function add(Template $template)
    {
        $this->items[] = $template;
    }

    public function selectable()
    {
        return $this->where(function (Template $template) {
            return $template->isSelectable();
        });
    }

    public function find(string $name)
    {
        $value = $this->first(function (Template $template) use ($name) {
            return $template->name() === $name;
        });

        if (! $value) {
            // throw new Exception();
        }

        return $value;
    }
}
