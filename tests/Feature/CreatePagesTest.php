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

    /** @test */
    public function there_are_required_fields()
    {
        $response = $this->postJson(route('admin.pages.store'));

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'title', 'template', 'is_stand_alone', 'is_published'
            ]);
    }

    /** @test */
    public function the_template_field_must_be_the_name_of_a_registered_template()
    {
        $response = $this->postJson(
            route('admin.pages.store'),
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
        $response = $this->postJson(
            route('admin.pages.store'),
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
        $response = $this->postJson(
            route('admin.pages.store'),
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
        $response = $this->postJson(
            route('admin.pages.store'),
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

    protected function validData(array $overrides = [])
    {
        $this->registerTemplate($template = new DummyTemplate);

        return array_merge([
            'title' => 'Title',
            'template' => $template->name(),
            'parent_id' => factory(Page::class)->create()->id,
            'content' => 'Content', // Required by the dummy template...
            'is_stand_alone' => false,
            'is_published' => true
        ], $overrides);
    }
}
