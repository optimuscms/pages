<?php

namespace Optimus\Pages\Tests\Feature;

use Mockery;
use Optimus\Pages\Template;
use Optimus\Pages\Tests\TestCase;
use Optimus\Pages\TemplateRepository;

class GetTemplatesTest extends TestCase
{
    /** @test */
    public function it_can_display_all_the_selectable_templates()
    {
        // Mock an hidden template
        $hiddenTemplate = Mockery::mock(Template::class)->makePartial();
        $hiddenTemplate->name = 'hidden';
        $hiddenTemplate->selectable = false;

        // Mock a selectable template
        $selectableTemplate = Mockery::mock(Template::class)->makePartial();
        $selectableTemplate->name = 'selectable';
        $selectableTemplate->selectable = true;

        // Register the templates
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
                    'name' => $selectableTemplate->name,
                    'label' => $selectableTemplate->label()
                ]]
            ]);
    }

    /** @test */
    public function it_will_display_templates_in_the_order_that_they_were_registered()
    {
        // Mock three selectable templates
        $templateOne = Mockery::mock(Template::class)->makePartial();
        $templateOne->name = 'one';
        $templateOne->selectable = true;

        $templateTwo = Mockery::mock(Template::class)->makePartial();
        $templateTwo->name = 'two';
        $templateTwo->selectable = true;

        $templateThree = Mockery::mock(Template::class)->makePartial();
        $templateThree->name = 'three';
        $templateThree->selectable = true;

        // Register the templates
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
                        'label'
                    ]
                ]
            ])
            ->assertJson([
                'data' => [
                    ['name' => $templateOne->name],
                    ['name' => $templateTwo->name],
                    ['name' => $templateThree->name]
                ]
            ]);
    }
}
