<?php

namespace Optimus\Pages;

use Illuminate\Http\Request;
use Optimus\Pages\Models\Page;

interface Template
{
    public function validate(Request $request);

    public function save(Page $page, Request $request);
}
