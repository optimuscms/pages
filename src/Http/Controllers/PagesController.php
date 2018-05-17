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
    public function index()
    {
        $pages = Page::all();

        return PageResource::collection($pages);
    }

    public function store(Request $request)
    {
        $this->validatePage($request);

        $template = PageTemplate::find($request->input('template_id'));

        $this->validateContents(
            $contents = $request->input('contents'),
            $template->handler->validationRules()
        );

        $page = Page::create([
            'title' => $request->input('title'),
            'slug' => str_slug($request->input('title')),
            'parent_id' => $request->input('parent_id'),
            'template_id' => $template->id,
            'is_stand_alone' => $request->input('is_stand_alone'),
            'is_published' => $request->input('is_published')
        ]);

        UpdatePageUri::dispatch($page);

        $template->handler->saveContents($page, collect($contents));

        return new PageResource($page);
    }

    public function show($id)
    {
        $page = Page::findOrFail($id);

        return new PageResource($page);
    }

    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);

        $this->validatePage($request, $page);

        $template = ! $page->has_fixed_template
            ? PageTemplate::find($request->input('template_id'))
            : $page->template;

        $this->validateContents(
            $contents = $request->input('contents'),
            $template->handler->validationRules($page)
        );

        $page->update([
            'title' => $request->get('title'),
            'slug' => ! $page->has_fixed_slug
                ? str_slug($request->get('title'))
                : $page->slug,
            'parent_id' => $request->get('parent_id'),
            'template_id' => $template->id,
            'is_stand_alone' => $request->get('is_stand_alone'),
            'is_published' => $request->get('is_published')
        ]);

        if (! $page->has_fixed_slug) {
            UpdatePageUri::dispatch($page);
        }

        $page->deleteContents($template->id);
        $template->handler->saveContents($page, collect($contents));

        return new PageResource($page);
    }

    public function destroy($id)
    {
        Page::where('is_deletable', true)
            ->findOrFail($id)
            ->delete();

        return response(null, 204);
    }

    protected function validatePage(Request $request, Page $page = null)
    {
        $request->validate([
            'title' => [
                'required',
                Rule::unique('pages')->where(function ($query) use ($request, $page) {
                    $query->where('parent_id', $request->input('parent_id'))
                          ->when($page, function ($query) use ($page) {
                              $query->where('id', '<>', $page->id);
                          });
                })
            ],
            'template_id' => 'required|exists:page_templates,id',
            'parent_id' => 'exists:pages,id|nullable',
            'is_stand_alone' => 'required|boolean',
            'is_published' => 'required|boolean'
        ]);
    }

    protected function validateContents(array $contents, array $rules)
    {
        Validator::make($contents, $rules)->validate();
    }
}
