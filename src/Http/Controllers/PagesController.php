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
            'order' => Page::max('order') + 1
        ]);

        if ($request->input('is_published')) {
            $page->publish();
        }

        UpdatePageUri::dispatch($page);

        $template->handler->saveContents($page, collect($contents));

        return new PageResource($page);
    }

    public function show($id)
    {
        $page = Page::withDrafts()->findOrFail($id);

        return new PageResource($page);
    }

    public function update(Request $request, $id)
    {
        $page = Page::withDrafts()->findOrFail($id);

        $this->validatePage($request, $page);

        $template = ! $page->has_fixed_template
            ? PageTemplate::find($request->input('template_id'))
            : $page->template;

        $this->validateContents(
            $contents = $request->input('contents'),
            $template->handler->validationRules($page)
        );

        $page->update([
            'title' => $request->input('title'),
            'slug' => ! $page->has_fixed_slug
                ? str_slug($request->input('title'))
                : $page->slug,
            'parent_id' => $request->input('parent_id'),
            'template_id' => $template->id,
            'is_stand_alone' => $request->input('is_stand_alone'),
        ]);

        $request->input('published_at') ? $page->publish() : $page->draft();

        if (! $page->has_fixed_slug) {
            UpdatePageUri::dispatch($page);
        }

        $page->deleteContents();
        $page->detachMedia();
        
        $template->handler->saveContents($page, collect($contents));

        return new PageResource($page);
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'pages' => 'required|array',
            'pages.*' => 'exists:pages,id'
        ]);

        $order = 1;
        foreach ($request->input('pages') as $id) {
            Page::withDrafts()->find($id)->update(['order' => $order]);
            $order++;
        }

        return response(null, 204);
    }

    public function destroy($id)
    {
        Page::withDrafts()
            ->where('is_deletable', true)
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
