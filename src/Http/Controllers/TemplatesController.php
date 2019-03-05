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
        return response()->json([
            'data' => collect($templates->all())->map->toArray()
        ]);
    }
}
