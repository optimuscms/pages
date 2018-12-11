<?php

namespace Optimus\Pages\Tests\Feature;

use Optimus\Pages\Tests\TestCase;
use Optimus\Pages\Models\PageTemplate;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TemplatesControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_display_all_page_templates()
    {
        factory(PageTemplate::class, 3)->create();

        $response = $this->getJson(route('page-templates.index'));

        $response
            ->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => $this->expectedJsonStructure()
                ]
            ]);
    }
    
    /** @test */
    public function it_can_display_only_selectable_page_templates()
    {
        $selectable = factory(PageTemplate::class, 2)->create();

        factory(PageTemplate::class)->create([
            'is_selectable' => false
        ]);

        $response = $this->getJson(
            route('page-templates.index') . '?is_selectable=1'
        );

        $response
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => $this->expectedJsonStructure()
                ]
            ]);

        $this->assertEmpty(array_diff(
            array_pluck($response->decodeResponseJson('data'), 'id'),
            $selectable->pluck('id')->toArray()
        ));
    }

    /** @test */
    public function it_can_display_a_page_template()
    {
        $template = factory(PageTemplate::class)->create();

        $response = $this->getJson(route('page-templates.show', [
            'id' => $template->id
        ]));

        $response
            ->assertOk()
            ->assertJsonStructure([
                'data' => $this->expectedJsonStructure()
            ])
            ->assertExactJson([
                'data' => [
                    'id' => $template->id,
                    'label' => $template->label,
                    'name' => $template->name,
                    'is_selectable' => $template->is_selectable
                ]
            ]);
    }

    protected function expectedJsonStructure()
    {
        return [
            'id',
            'label',
            'name',
            'is_selectable'
        ];
    }
}
