<?php

namespace Optimus\Pages\Tests\Feature;

use Optimus\Pages\Models\Page;
use Optimus\Pages\Tests\TestCase;
use Optimus\Pages\Tests\DummyTemplate;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreatePagesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_page()
    {
        $response = $this->postJson(
            route('admin.pages.store'),
            $data = $this->validData()
        );

        $response
            ->assertStatus(201)
            ->assertJsonStructure([
                'data' => $this->expectedPageJsonStructure()
            ])
            ->assertJson([
                'data' => [
                    'title' => $data['title'],
                    'template' => $data['template'],
                    'parent_id' => $data['parent_id'],
                    'contents' => [[
                        'key' => 'content',
                        'value' => $data['content']
                    ]],
                    'is_stand_alone' => $data['is_stand_alone'],
                    'is_published' => $data['is_published'],
                    'is_deletable' => true
                ]
            ]);
    }

    protected function validData(array $overrides = [])
    {
        $this->registerTemplate($template = new DummyTemplate);

        return array_merge([
            'title' => 'Title',
            'template' => $template->name,
            'parent_id' => factory(Page::class)->create()->id,
            'content' => 'Content', // Required by the dummy template...
            'is_stand_alone' => false,
            'is_published' => true
        ], $overrides);
    }
}
