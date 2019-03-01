<?php

namespace Optimus\Pages\Tests;

class TemplateTest extends TestCase
{
    /**
     * @dataProvider provide_template_names
     *
     * @param string $providedName
     * @param string $expectedName
     */
    public function test_label_name_is_standardised(string $providedName, string $expectedName)
    {
        $template = new DummyTemplate();
        $template->name = $providedName;

        $this->assertSame($expectedName, $template->label());
    }

    public function provide_template_names()
    {
        return [
            ['a-template-name', 'A Template Name'],
            ['template_name', 'Template Name'],
            ['theTemplateName', 'The Template Name'],
        ];
    }

    /** @test */
    public function test_template_can_be_converted_to_an_array()
    {
        $template = new DummyTemplate();
        $template->name = 'template 1';

        $arrayRepresentation = $template->toArray();
        $this->assertSame($template->name, $arrayRepresentation['name']);
        $this->assertSame($template->label(), $arrayRepresentation['label']);
    }
}
