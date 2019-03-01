<?php

namespace Optimus\Pages\Tests\Feature;

use Optimus\Pages\Tests\TestCase;
use Optimus\Pages\TemplateRepository;
use Optimus\Pages\Tests\DummyTemplate;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreatePagesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_page()
    {
        $this->app[TemplateRepository::class]->register(
            $template = new DummyTemplate
        );

        $response = $this->postJson(route('admin.pages.store'), $data = [
            'title' => 'Title',
            'template' => $template->name,
            'parent_id' => null,
            'content' => 'Content', // Required by the dummy template...
            'is_stand_alone' => false,
            'is_published' => true
        ]);

        $response
            ->assertStatus(201)
            ->assertJsonStructure([
                'data' => $this->expectedPageJsonStructure()
            ])
            ->assertJson([
                'data' => [
                    'title' => $data['title'],
                    'slug' => 'title',
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
}
