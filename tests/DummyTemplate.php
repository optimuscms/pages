<?php

namespace Optimus\Pages\Tests;

use Illuminate\Http\Request;
use Optimus\Pages\Models\Page;
use Optimus\Pages\Contracts\Template;

class DummyTemplate implements Template
{
    public function validate(Request $request)
    {
        $request->validate([
            'content' => 'required'
        ]);
    }

    public function save(Page $page, Request $request)
    {
        $page->addContents(
            $request->only('content')
        );
    }
}
