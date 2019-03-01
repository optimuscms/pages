<?php

namespace Optimus\Pages\Tests\Feature;

use Optimus\Pages\Models\Page;
use Optimus\Pages\Tests\TestCase;
use Optimus\Pages\Tests\DummyTemplate;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdatePagesTest extends TestCase
{
    use RefreshDatabase;

    protected $page;

    public function setUp()
    {
        parent::setUp();

        $this->page = factory(Page::class)->create([
            'title' => 'Old title',
            'template' => 'old-template',
            'parent_id' => factory(Page::class)->create()->id,
            'is_stand_alone' => true,
            'published_at' => null
        ]);
    }

    /** @test */
    public function it_can_update_a_page()
    {
        $response = $this->patchJson(
            route('admin.pages.update', ['id' => $this->page->id]),
            $newData = $this->validData()
        );

        $response
            ->assertOk()
            ->assertJsonStructure([
                'data' => $this->expectedPageJsonStructure()
            ])
            ->assertJson([
                'data' => [
                    'title' => $newData['title'],
                    'template' => $newData['template'],
                    'parent_id' => $newData['parent_id'],
                    'contents' => [[
                        'key' => 'content',
                        'value' => $newData['content']
                    ]],
                    'is_stand_alone' => $newData['is_stand_alone'],
                    'is_published' => $newData['is_published']
                ]
            ]);
    }

    protected function validData($overrides = [])
    {
        $this->registerTemplate($template = new DummyTemplate);

        return array_merge([
            'title' => 'New title',
            'template' => $template->name,
            'parent_id' => null,
            'content' => 'Content', // Required by the dummy template...
            'is_stand_alone' => false,
            'is_published' => true
        ], $overrides);
    }
}
