<?php


namespace Optimus\Pages\Tests\Api;

use Optimus\Pages\Models\Page;
use Optimus\Pages\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

//class PagesTest extends TestCase
//{
//    use RefreshDatabase;
//
//    /** @test */
//    public function it_can_display_all_pages_in_the_correct_order()
//    {
//        $pageOne = factory(Page::class)->create(['order' => 2]);
//        $pageTwo = factory(Page::class)->create(['order' => 3]);
//        $pageThree = factory(Page::class)->create(['order' => 1]);
//
//        $response = $this->getJson(route('admin.pages.index'));
//
//        $response
//            ->assertOk()
//            ->assertJsonCount(3, 'data')
//            ->assertJsonStructure([
//                'data' => [
//                    '*' => array_merge($this->expectedJsonStructure(), [
//                        'children_count'
//                    ])
//                ]
//            ]);
//
//        // Assert results are ordered by their given order...
//        $this->assertEquals(
//            [$pageThree->id, $pageOne->id, $pageTwo->id],
//            $response->decodeResponseJson('data.*.id')
//        );
//    }
//
//    /** @test */
//    public function it_will_include_draft_pages_in_results()
//    {
//        factory(Page::class, 2)->create();
//
//        $draftPage = factory(Page::class)
//            ->state('draft')
//            ->create();
//
//        $response = $this->getJson(route('admin.pages.index'));
//
//        $response
//            ->assertOk()
//            ->assertJsonCount(3, 'data')
//            ->assertJsonStructure([
//                'data' => [
//                    '*' => array_merge($this->expectedJsonStructure(), [
//                        'children_count'
//                    ])
//                ]
//            ]);
//
//        $this->assertContains(
//            $draftPage->id, $response->decodeResponseJson('data.*.id')
//        );
//    }
//
//    /** @test */
//    public function it_can_filter_pages_by_parent()
//    {
//        $parentPage = factory(Page::class)->create();
//        $childPage = factory(Page::class)->create([
//            'parent_id' => $parentPage->id
//        ]);
//
//        $response = $this->getJson(
//            route('admin.pages.index') . '?parent=' . $parentPage->id
//        );
//
//        $response
//            ->assertOk()
//            ->assertJsonCount(1, 'data')
//            ->assertJsonStructure([
//                'data' => [
//                    array_merge($this->expectedJsonStructure(), [
//                        'children_count'
//                    ])
//                ]
//            ])
//            ->assertJson([
//                'data' => [[
//                    'id' => $childPage->id,
//                    'parent_id' => $parentPage->id
//                ]]
//            ]);
//    }
//
//    /** @test */
//    public function it_can_create_a_page()
//    {
//        $template = factory(PageTemplate::class)->create();
//
//        $response = $this->postJson(route('admin.pages.store'), $data = [
//            'title' => 'Title',
//            'parent_id' => null,
//            'template_id' => $template->id,
//            'content' => 'Content',
//            'is_stand_alone' => false,
//            'is_published' => true
//        ]);
//
//        $response
//            ->assertStatus(201)
//            ->assertJsonStructure([
//                'data' => $this->expectedJsonStructure()
//            ])
//            ->assertJson([
//                'data' => [
//                    'title' => $data['title'],
//                    'parent_id' => $data['parent_id'],
//                    'template_id' => $data['template_id'],
//                    'contents' => [[
//                        'key' => 'content',
//                        'value' => $data['content']
//                    ]],
//                    'is_published' => $data['is_published']
//                ]
//            ]);
//    }
//
//    /** @test */
//    public function it_can_display_a_page()
//    {
//        $page = factory(Page::class)
//            ->create()
//            ->fresh();
//
//        $response = $this->getJson(route('admin.pages.show', [
//            'id' => $page->id
//        ]));
//
//        $response
//            ->assertOk()
//            ->assertJsonStructure([
//                'data' => $this->expectedJsonStructure()
//            ])
//            ->assertExactJson([
//                'data' => [
//                    'id' => $page->id,
//                    'title' => $page->title,
//                    'slug' => $page->slug,
//                    'uri' => $page->uri,
//                    'has_fixed_uri' => $page->has_fixed_uri,
//                    'media' => [],
//                    'contents' => [],
//                    'parent_id' => $page->parent_id,
//                    'template_id' => $page->template_id,
//                    'has_fixed_template' => $page->has_fixed_template,
//                    'is_stand_alone' => $page->is_stand_alone,
//                    'is_published' => $page->isPublished(),
//                    'is_deletable' => $page->is_deletable,
//                    'created_at' => (string) $page->created_at,
//                    'updated_at' => (string) $page->updated_at
//                ]
//            ]);
//    }
//
//    /** @test */
//    public function it_can_update_a_page()
//    {
//        $page = Page::forceCreate([
//            'title' => 'Old title',
//            'slug' => 'old-title',
//            'parent_id' => null,
//            'template_id' => factory(PageTemplate::class)->create()->id,
//            'is_stand_alone' => true,
//            'order' => 1,
//            'published_at' => null
//        ]);
//
//        $page->addContents([
//            'content' => 'Old content'
//        ]);
//
//        $response = $this->patchJson(route('admin.pages.update', [
//            'id' => $page->id
//        ]), $newData = [
//            'title' => 'New title',
//            'parent_id' => factory(Page::class)->create()->id,
//            'template_id' => factory(PageTemplate::class)->create()->id,
//            'content' => 'New content',
//            'is_stand_alone' => false,
//            'is_published' => true
//        ]);
//
//        $response
//            ->assertOk()
//            ->assertJsonStructure([
//                'data' => $this->expectedJsonStructure()
//            ])
//            ->assertJson([
//                'data' => [
//                    'title' => $newData['title'],
//                    'parent_id' => $newData['parent_id'],
//                    'template_id' => $newData['template_id'],
//                    'contents' => [[
//                        'key' => 'content',
//                        'value' => $newData['content']
//                    ]],
//                    'is_stand_alone' => $newData['is_stand_alone'],
//                    'is_published' => $newData['is_published']
//                ]
//            ]);
//    }
//
//    /** @test */
//    public function it_can_delete_a_page()
//    {
//        $page = factory(Page::class)->create();
//
//        $response = $this->deleteJson(route('admin.pages.destroy', [
//            'id' => $page->id
//        ]));
//
//        $response->assertStatus(204);
//
//        $this->assertDatabaseMissing($page->getTable(), [
//            'id' => $page->id
//        ]);
//    }
//
//    protected function expectedJsonStructure()
//    {
//        return [
//            'id',
//            'title',
//            'slug',
//            'uri',
//            'has_fixed_uri',
//            'parent_id',
//            'template_id',
//            'has_fixed_template',
//            'contents',
//            'media',
//            'is_stand_alone',
//            'is_published',
//            'is_deletable',
//            'created_at',
//            'updated_at'
//        ];
//    }
//}
