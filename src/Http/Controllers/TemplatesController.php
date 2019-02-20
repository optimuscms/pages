<?php

namespace Optimus\Pages\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Optimus\Pages\Models\PageTemplate;
use Optimus\Pages\Http\Resources\TemplateResource;

class TemplatesController extends Controller
{
    /**
     * Display a list of page templates.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $templates = PageTemplate::filter($request)
            ->orderBy('name')
            ->get();

        return TemplateResource::collection($templates);
    }

    /**
     * Display a specified page template.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $template = PageTemplate::findOrFail($id);

        return new TemplateResource($template);
    }
}
