<?php

namespace Optimus\Pages\Http\Controllers;

use Optimus\Pages\Template;
use Illuminate\Routing\Controller;
use Optimus\Pages\TemplateManager;

class TemplatesController extends Controller
{
    protected $templates;

    public function __construct(TemplateManager $templates)
    {
        $this->templates = $templates;
    }

    /**
     * Display a list of page templates.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $templates = $this->templates->selectable()->toArray();

        return response()->json([
            'data' => $templates
        ]);
    }
}
