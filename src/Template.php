<?php

namespace Optimus\Pages;

use Illuminate\Http\Request;
use Optimus\Pages\Models\Page;

abstract class Template
{
    abstract public function name(): string;

    public function label(): string
    {
        return title_case(snake_case($this->name(), ' '));
    }

    public function isSelectable(): bool
    {
        return true;
    }

    abstract public function validate(Request $request);

    abstract public function save(Page $page, Request $request);

    public function toArray()
    {
        return [
            'name' => $this->name(),
            'label' => $this->label(),
            'is_selectable' => $this->isSelectable()
        ];
    }
}
