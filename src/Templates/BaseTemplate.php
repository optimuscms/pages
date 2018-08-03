<?php

use Illuminate\Support\Collection;
use Optimus\Pages\Templates\Template;

abstract class BaseTemplate implements Template
{
    protected $view;

    public function validate(Collection $contents)
    {
        $validator = validator(
            $contents->toArray(), $this->rules($contents)
        )->validate();
    }

    abstract protected function rules(Collection $contents);

    public function render($data = [], $mergeData = [])
    {
        return view($this->view, $data, $mergeData);
    }
}
