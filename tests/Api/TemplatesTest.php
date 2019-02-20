<?php

namespace Optimus\Pages\Tests\Api;

use Optimus\Pages\Tests\TestCase;
use Optimus\Pages\Models\PageTemplate;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TemplatesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_display_all_page_templates_in_the_correct_order()
    {
        $templateOne = factory(PageTemplate::class)->create(['name' => 'B']);
        $templateTwo = factory(PageTemplate::class)->create(['name' => 'C']);
        $templateThree = factory(PageTemplate::class)->create(['name' => 'A']);

        $response = $this->getJson(route('admin.page-templates.index'));

        $response
            ->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => $this->expectedJsonStructure()
                ]
            ]);

        // Assert results are ordered alphabetically...
        $this->assertEquals(
            [$templateThree->id, $templateOne->id, $templateTwo->id],
            $response->decodeResponseJson('data.*.id')
        );
    }

    /** @test */
    public function it_can_display_only_selectable_page_templates()
    {
        $selectable = factory(PageTemplate::class, 2)->create();

        factory(PageTemplate::class)->create([
            'is_selectable' => false
        ]);

        $response = $this->getJson(
            route('admin.page-templates.index') . '?is_selectable=1'
        );

        $response
            ->assertOk()
            ->assertJsonCount(2, 'data');

        $this->assertEmpty($selectable->pluck('id')->diff(
            $response->decodeResponseJson('data.*.id')
        ));
    }

    /** @test */
    public function it_can_display_a_page_template()
    {
        $template = factory(PageTemplate::class)->create();

        $response = $this->getJson(route('admin.page-templates.show', [
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
