<?php

namespace Optimus\Pages\Http\Controllers;

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
        $this->templates->all();
        $this->templates->selectable();
        $this->templates->get($name);

        collect($this->templates->getSelectable())
            ->map(function ($template) {
                $template->toArray();
            });

        $templates = $this->templates->selectable()->toArray();

        return response()->json([
            'data' => $templates
        ]);
    }
}
