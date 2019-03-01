<?php

namespace Optimus\Pages;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Optimus\Pages\Models\Page;

abstract class Template
{
    /**
     * Get the template's name.
     *
     * @var string
     */
    public $name = 'default';

    /**
     * Determine if the template is selectable.
     *
     * @var bool
     */
    public $selectable = true;

    /**
     * Get the template's label.
     *
     * @return string
     */
    public function label()
    {
        return Str::title(
            Str::snake($this->name, ' ')
        );
    }

    /**
     * Validate the request data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    abstract public function validate(Request $request);

    /**
     * Save the request data to the page.
     *
     * @param  \Optimus\Pages\Models\Page  $page
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    abstract public function save(Page $page, Request $request);

    /**
     * Cast the template to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'name' => $this->name,
            'label' => $this->label()
        ];
    }
}
