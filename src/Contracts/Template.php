<?php

namespace Optimus\Pages\Contracts;

use Optimus\Pages\Page;
use Illuminate\Support\Collection;

interface Template
{
    public function validationRules(Page $page = null);

    public function saveContents(Page $page, Collection $contents);
}
