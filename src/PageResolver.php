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
        $uri = $this->parseUriFromRequest();

        return Page::where('uri', $uri)->firstOrFail();
    }

    protected function parseUriFromRequest()
    {
        $path = $this->request->path();

        return $path === '/' ? null : $path;
    }
}
