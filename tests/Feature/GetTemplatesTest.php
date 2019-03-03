<?php

namespace Optimus\Pages\Tests\Feature;

use Optimus\Pages\Tests\TestCase;
use Optimus\Pages\TemplateRepository;

class GetTemplatesTest extends TestCase
{
    /** @test */
    public function it_can_display_all_the_selectable_templates()
    {
        // Mock a hidden template...
        $hiddenTemplate = $this->mockTemplate('hidden', false);

        // Mock a selectable template...
        $selectableTemplate = $this->mockTemplate('selectable')->makePartial();

        // Register the templates...
        $this->app[TemplateRepository::class]->registerMany([
            $hiddenTemplate,
            $selectableTemplate
        ]);

        $response = $this->getJson(route('admin.page-templates.index'));

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'name',
                        'label'
                    ]
                ]
            ])
            ->assertJson([
                'data' => [[
                    'name' => $selectableTemplate->name(),
                    'label' => $selectableTemplate->label()
                ]]
            ]);
    }

    /** @test */
    public function it_will_display_templates_in_the_order_that_they_were_registered()
    {
        // Mock three selectable templates...
        $templateOne = $this->mockTemplate('one')->makePartial();
        $templateTwo = $this->mockTemplate('two')->makePartial();
        $templateThree = $this->mockTemplate('three')->makePartial();

        // Register the templates...
        $this->registerTemplates([
            $templateOne,
            $templateTwo,
            $templateThree
        ]);

        $response = $this->getJson(route('admin.page-templates.index'));

        $response
            ->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'name',
                        'label',
                        'is_selectable'
                    ]
                ]
            ])
            ->assertJson([
                'data' => [
                    ['name' => $templateOne->name()],
                    ['name' => $templateTwo->name()],
                    ['name' => $templateThree->name()]
                ]
            ]);
    }
}
