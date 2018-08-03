<?php

namespace Optimus\Pages;

use Illuminate\Http\Request;

class PageResolver
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function resolve()
    {
        return $this->query()->first();
    }

    public function resolveOrFail()
    {
        return $this->query()->firstOrFail();
    }

    protected function query()
    {
        return Page::where('uri', $this->uri());
    }

    protected function uri()
    {
        $path = $this->request->path();

        return $path === '/' ? null : $path;
    }
}
