<?php

namespace Optimus\Pages\Templates;

use Optimus\Pages\Page;
use Illuminate\Support\Collection;

interface Template
{
    public function validate(Collection $contents);

    public function save(Page $page, Collection $contents);

    public function render($data = [], $mergeData = []);
}
