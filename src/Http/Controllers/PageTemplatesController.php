<?php

namespace Optimus\Pages\Http\Controllers;

use Optimus\Pages\PageTemplate;
use Illuminate\Routing\Controller;
use Optimus\Pages\Http\Resources\PageTemplate as PageTemplateResource;

class PageTemplatesController extends Controller
{
    public function index()
    {
        $templates = PageTemplate::all();

        return PageTemplateResource::collection($templates);
    }
}
