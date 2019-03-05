<?php

namespace Optimus\Pages\Http\Controllers;

use Optimus\Pages\Template;
use Illuminate\Http\Request;
use Optimus\Pages\Models\Page;
use Illuminate\Routing\Controller;
use Optimus\Pages\Jobs\UpdatePageUri;
use Optimus\Pages\TemplateRepository;
use Optimus\Pages\Http\Resources\PageResource;

class PagesController extends Controller
{
    protected $templates;

    public function __construct(TemplateRepository $templates)
    {
        $this->templates = $templates;
    }

    public function index(Request $request)
    {
        $pages = Page::withDrafts()
            ->withCount('children')
            ->filter($request)
            ->orderBy('order')
            ->get();

        return PageResource::collection($pages);
    }

    public function store(Request $request)
    {
        $this->validatePage($request);

        $template = $this->templates->find(
            $request->input('template')
        );

        $template->validate($request);

        $page = Page::create([
            'title' => $request->input('title'),
            'slug' => $request->input('slug'),
            'template' => $template->name(),
            'parent_id' => $request->input('parent_id'),
            'is_stand_alone' => $request->input('is_stand_alone'),
            'is_deletable' => true,
            'order' => Page::max('order') + 1
        ]);

        UpdatePageUri::dispatch($page);

        $template->save($page, $request);

        if ($request->input('is_published')) {
            $page->publish();
        }

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

        $this->validatePage($request);

        $templateName = ! $page->has_fixed_template
            ? $request->input('template')
            : $page->template;

        $template = $this->templates->find($templateName);

        $template->validate($request);

        $page->update([
            'title' => $request->input('title'),
            'slug' => ! $page->has_fixed_uri
                ? $request->input('slug')
                : $page->slug,
            'template' => $templateName,
            'parent_id' => $request->input('parent_id'),
            'is_stand_alone' => $request->input('is_stand_alone')
        ]);

        if (! $page->has_fixed_uri) {
            UpdatePageUri::dispatch($page);
        }

        $page->detachMedia();
        $page->deleteContents();

        $template->save($page, $request);

        if ($page->isDraft() && $request->input('is_published')) {
            $page->publish();
        } elseif ($page->isPublished() && ! $request->input('is_published')) {
            $page->draft();
        }

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
            Page::where('id', $id)->update([
                'order' => $order
            ]);

            $order++;
        }

        return response(null, 204);
    }

    public function destroy($id)
    {
        $page = Page::withDrafts()->findOrFail($id);

        if (! $page->is_deletable) {
            abort(403);
        }

        $page->delete();

        return response(null, 204);
    }

    protected function validatePage(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'template' => 'required|in:' . collect($this->templates->all())
                ->map(function (Template $template) {
                    return $template->name();
                })
                ->implode(','),
            'parent_id' => 'exists:pages,id|nullable',
            'is_stand_alone' => 'present|boolean',
            'is_published' => 'present|boolean'
        ]);
    }
}
