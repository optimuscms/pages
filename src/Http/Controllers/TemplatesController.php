<?php

namespace Optimus\Pages\Http\Controllers;

use Illuminate\Routing\Controller;
use Optimus\Pages\TemplateRepository;

class TemplatesController extends Controller
{
    /**
     * Display a list of page templates.
     *
     * @param  \Optimus\Pages\TemplateRepository  $templates
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(TemplateRepository $templates)
    {
        $templates = collect($templates->selectable());

        return response()->json([
            'data' => $templates->map->toArray()
        ]);
    }
}
