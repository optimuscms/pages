<?php

namespace Optimus\Pages\Tests\Feature;

use Mockery;
use Optimus\Pages\Template;
use Optimus\Pages\Models\Page;
use Optimus\Pages\Tests\TestCase;
use Optimus\Pages\TemplateRepository;
use Optimus\Pages\Tests\DummyTemplate;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdatePagesTest extends TestCase
{
    use RefreshDatabase;

    protected $page;

    public function setUp()
    {
        parent::setUp();

        $this->registerTemplate($template = $this->mockTemplate());
        
        $this->page = factory(Page::class)->create([
            'title' => 'Old title',
            'template' => $template->name,
            'parent_id' => factory(Page::class)->create()->id,
            'is_stand_alone' => true
        ]);
    }

    // /** @test */
    public function it_can_update_a_page()
    {
        $response = $this->patchJson(
            route('admin.pages.update'),
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
                ]
            ]);
    }

    protected function validData($overrides = [])
    {
        $this->app[TemplateRepository::class]->register(
            $template = new DummyTemplate
        );

        return array_merge([
            'title' => 'New title',
            'template' => $template->name,
            'parent_id' => null,
            'content' => 'Content', // Required by the dummy template...
            'is_stand_alone' => false,
            'is_published' => true
        ], $overrides);
    }

    protected function mockTemplate()
    {
        $template = Mockery::mock(Template::class);

        $template->name = 'old-template';

        return $template;
    }

    protected function registerTemplate(Template $template)
    {
        $this->app[TemplateRepository::class]->register($template);
    }
}
