<?php

namespace Optimus\Pages;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Optimus\Pages\Models\Page;

abstract class Template
{
    public $name;

    public $isSelectable = false;

    public function label()
    {
        return Str::title(Str::snake($this->name, ' '));
    }

    abstract public function validate(Request $request);

    abstract public function save(Page $page, Request $request);

    public function toArray()
    {
        return [
            'name' => $this->name,
            'label' => $this->label(),
            'is_selectable' => $this->isSelectable
        ];
    }
}
