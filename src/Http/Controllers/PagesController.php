<?php

namespace Optimus\Pages\Http\Controllers;

use Optimus\Pages\Page;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Optimus\Pages\PageTemplate;
use Illuminate\Routing\Controller;
use Optimus\Pages\Jobs\UpdatePageUri;
use Illuminate\Support\Facades\Validator;
use Optimus\Pages\Http\Resources\Page as PageResource;

class PagesController extends Controller
{
    public function index(Request $request)
    {
        $pages = Page::withDrafts()
            ->withCount('children')
            ->filter($request)
            ->get();

        return PageResource::collection($pages);
    }

    public function store(TemplateLoader $templateLoader, Request $request)
    {
        $this->validatePage($request);

        $template = $templateLoader->load($request->input('template'));

        $template->validate(
            $contents = collect($request->input('contents'))
        );

        $page = Page::create([
            'title' => $request->input('title'),
            'parent_id' => $request->input('parent_id'),
            'template_id' => $request->input('template_id'),
            'is_stand_alone' => $request->input('is_stand_alone')
        ]);

        $template->save($contents);

        // Todo: Meta

        $page->publishIf($request->input('is_published'));

        return new PageResource($page);
    }

    protected function validatePage(Request $request, Page $page = null)
    {
        $request->validate([
            'title' => 'required',
            'template_id' => 'required|exists:page_templates,id',
            'parent_id' => 'exists:pages,id|nullable',
            'is_stand_alone' => 'required|boolean',
            'is_published' => 'required|boolean'
        ]);
    }
}
