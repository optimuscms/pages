<?php

namespace Optimus\Pages\Tests\Feature;

use Optimus\Pages\Models\Page;
use Optimus\Pages\Tests\TestCase;
use Optimus\Pages\Models\PageTemplate;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PagesControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_display_all_pages()
    {
        factory(Page::class, 3)->create();

        $response = $this->getJson(route('pages.index'));

        $response
            ->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => array_merge($this->expectedJsonStructure(), [
                        'children_count'
                    ])
                ]
            ]);
    }
    
    /** @test */
    public function it_will_include_draft_pages_in_results()
    {
        factory(Page::class, 2)->create();

        $draftPage = factory(Page::class)
            ->state('draft')
            ->create();

        $response = $this->getJson(route('pages.index'));

        $response
            ->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => array_merge($this->expectedJsonStructure(), [
                        'children_count'
                    ])
                ]
            ]);

        $this->assertContains(
            $draftPage->id,
            array_pluck($response->decodeResponseJson('data'), 'id')
        );
    }

    /** @test */
    public function it_can_filter_pages_by_parent()
    {
        $this->assertTrue(true);
    }
    
    /** @test */
    public function it_can_create_a_page()
    {
        $template = factory(PageTemplate::class)->create();

        $response = $this->postJson(route('pages.store'), $data = [
            'title' => 'Title',
            'parent_id' => null,
            'template_id' => $template->id,
            'content' => 'Content',
            'is_stand_alone' => false,
            'is_published' => true
        ]);

        $response
            ->assertStatus(201)
            ->assertJsonStructure([
                'data' => $this->expectedJsonStructure()
            ])
            ->assertJson([
                'data' => [
                    'title' => $data['title'],
                    'parent_id' => $data['parent_id'],
                    'template_id' => $data['template_id'],
                    'contents' => [[
                        'key' => 'content',
                        'value' => $data['content']
                    ]],
                    'is_published' => $data['is_published']
                ]
            ]);
    }

    /** @test */
    public function it_can_display_a_page()
    {
        $page = factory(Page::class)
            ->create()
            ->fresh();

        $response = $this->getJson(route('pages.show', [
            'id' => $page->id
        ]));

        $response
            ->assertOk()
            ->assertJsonStructure([
                'data' => $this->expectedJsonStructure()
            ])
            ->assertExactJson([
                'data' => [
                    'id' => $page->id,
                    'title' => $page->title,
                    'slug' => $page->slug,
                    'uri' => $page->uri,
                    'has_fixed_uri' => $page->has_fixed_uri,
                    'media' => [],
                    'contents' => [],
                    'parent_id' => $page->parent_id,
                    'template_id' => $page->template_id,
                    'has_fixed_template' => $page->has_fixed_template,
                    'is_stand_alone' => $page->is_stand_alone,
                    'is_published' => $page->isPublished(),
                    'is_deletable' => $page->is_deletable,
                    'created_at' => (string) $page->created_at,
                    'updated_at' => (string) $page->updated_at
                ]
            ]);

        $this->assertTrue(true);
    }

    /** @test */
    public function it_can_update_a_page()
    {
        $page = Page::forceCreate([
            'title' => 'Old title',
            'slug' => 'old-title',
            'parent_id' => null,
            'template_id' => factory(PageTemplate::class)->create(),
            'is_stand_alone' => true,
            'order' => 1,
            'published_at' => null
        ]);

        $page->addContents([
            'content' => 'Old content'
        ]);

        $response = $this->patchJson(route('pages.update', [
            'id' => $page->id
        ]), $newData = [
            'title' => 'New title',
            'parent_id' => factory(Page::class)->create()->id,
            'template_id' => factory(PageTemplate::class)->create()->id,
            'content' => 'New content',
            'is_stand_alone' => false,
            'is_published' => true
        ]);

        $response
            ->assertOk()
            ->assertJsonStructure([
                'data' => $this->expectedJsonStructure()
            ])
            ->assertJson([
                'data' => [
                    'title' => $newData['title'],
                    'parent_id' => $newData['parent_id'],
                    'template_id' => $newData['template_id'],
                    'contents' => [[
                        'key' => 'content',
                        'value' => $newData['content']
                    ]],
                    'is_stand_alone' => $newData['is_stand_alone'],
                    'is_published' => $newData['is_published']
                ]
            ]);
    }
    
    /** @test */
    public function it_can_delete_a_page()
    {
        $page = factory(Page::class)->create();

        $response = $this->deleteJson(route('pages.destroy', [
            'id' => $page->id
        ]));

        $response->assertStatus(204);

        $this->assertDatabaseMissing(
            $page->getTable(), ['id' => $page->id]
        );
    }

    protected function expectedJsonStructure()
    {
        return [
            'id',
            'title',
            'slug',
            'uri',
            'has_fixed_uri',
            'parent_id',
            'template_id',
            'has_fixed_template',
            'contents',
            'media',
            'is_stand_alone',
            'is_published',
            'is_deletable',
            'created_at',
            'updated_at'
        ];
    }
}
