<?php

namespace Optimus\Pages\Tests\Feature;

use Optimus\Pages\Models\Page;
use Optimus\Pages\Tests\TestCase;
use Optimus\Pages\Tests\DummyTemplate;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdatePagesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_update_a_page()
    {
        $page = $this->createPage();

        $response = $this->patchJson(
            route('admin.pages.update', ['id' => $page->id]),
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

    /** @test */
    public function there_are_required_fields()
    {
        $page = $this->createPage();

        $response = $this->patchJson(
            route('admin.pages.update', ['id' => $page->id])
        );

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'title', 'template', 'is_stand_alone', 'is_published'
            ]);
    }

    /** @test */
    public function the_template_field_must_be_the_name_of_a_registered_template()
    {
        $page = $this->createPage();

        $response = $this->patchJson(
            route('admin.pages.update', ['id' => $page->id]),
            $this->validData(['template' => 'unregistered'])
        );

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'template'
            ]);

        $this->assertEquals(
            trans('validation.in', ['attribute' => 'template']),
            array_first($response->decodeResponseJson('errors.template'))
        );
    }
    
    /** @test */
    public function the_parent_id_field_must_be_a_valid_page_id_if_not_null()
    {
        $page = $this->createPage();

        $response = $this->patchJson(
            route('admin.pages.update', ['id' => $page->id]),
            $this->validData(['parent_id' => -1])
        );

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'parent_id'
            ]);

        $this->assertEquals(
            trans('validation.exists', ['attribute' => 'parent id']),
            array_first($response->decodeResponseJson('errors.parent_id'))
        );
    }

    /** @test */
    public function the_parent_id_field_cannot_be_a_descendant_of_the_page_being_edited()
    {
        // Todo...
    }

    /** @test */
    public function the_is_stand_alone_field_must_be_a_boolean()
    {
        $page = $this->createPage();

        $response = $this->patchJson(
            route('admin.pages.update', ['id' => $page->id]),
            $this->validData(['is_stand_alone' => 'string'])
        );

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'is_stand_alone'
            ]);

        $this->assertEquals(
            trans('validation.boolean', ['attribute' => 'is stand alone']),
            array_first($response->decodeResponseJson('errors.is_stand_alone'))
        );
    }

    /** @test */
    public function the_is_published_field_must_be_a_boolean()
    {
        $page = $this->createPage();

        $response = $this->patchJson(
            route('admin.pages.update', ['id' => $page->id]),
            $this->validData(['is_published' => 'string'])
        );

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'is_published'
            ]);

        $this->assertEquals(
            trans('validation.boolean', ['attribute' => 'is published']),
            array_first($response->decodeResponseJson('errors.is_published'))
        );
    }

    protected function createPage(array $overrides = [])
    {
        return factory(Page::class)->create(array_merge([
            'title' => 'Old title',
            'template' => 'old-template',
            'parent_id' => factory(Page::class)->create(),
            'is_stand_alone' => true,
            'published_at' => null
        ], $overrides));
    }

    protected function validData(array $overrides = [])
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
