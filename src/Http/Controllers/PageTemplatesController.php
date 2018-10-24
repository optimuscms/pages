<?php

namespace Optimus\Pages\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Optimus\Pages\Models\PageTemplate;
use Optimus\Pages\Http\Resources\PageTemplate as PageTemplateResource;

class PageTemplatesController extends Controller
{
    public function index(Request $request)
    {
        $templates = PageTemplate::filter($request)->get();

        return PageTemplateResource::collection($templates);
    }
}
