<?php

namespace Optimus\Pages\Contracts;

use Optimus\Pages\Page;
use Illuminate\Http\Request;

interface Template
{
    public function validationRules(Page $page = null);

    public function saveContents(Request $request, Page $page);
}
