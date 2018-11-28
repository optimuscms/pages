<?php

namespace Optimus\Pages;

use Illuminate\Http\Request;
use Optimus\Pages\Models\Page;

class PageResolver
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function resolve()
    {
        $path = $this->request->path();

        $uri = $path === '/' ? null : $path;

        return Page::where('uri', $uri)->firstOrFail();
    }
}
