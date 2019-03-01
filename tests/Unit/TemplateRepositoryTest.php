<?php

namespace Optimus\Pages\Tests;

use Optimus\Pages\TemplateRepository;

/**
 * @property TemplateRepository templateRepository
 */
class TemplateRepositoryTest extends TestCase
{
    public function setUp()
    {
        $this->templateRepository = new TemplateRepository();
    }

    /** @test */
    public function it_can_register_templates()
    {
        $template1 = new DummyTemplate();
        $template2 = new DummyTemplate();

        $this->templateRepository->register($template1);
        $this->assertEquals(1, count($this->templateRepository->all()));

        $this->templateRepository->register($template2);
        $this->assertEquals(2, count($this->templateRepository->all()));

        $templates = $this->templateRepository->all();

        $this->assertSame($template1, array_shift($templates));
        $this->assertSame($template2, array_shift($templates));
    }

    /** @test */
    public function it_can_register_templates_in_bulk()
    {
        $template1 = new DummyTemplate();
        $template2 = new DummyTemplate();

        $this->templateRepository->registerMany([$template1, $template2]);
        $templates = $this->templateRepository->all();

        $this->assertEquals(2, count($templates));

        $this->assertSame($template1, array_shift($templates));
        $this->assertSame($template2, array_shift($templates));
    }

    /** @test */
    public function it_only_returns_selectable_templates()
    {
        $template1 = new DummyTemplate();
        $template2 = new DummyTemplate();
        $template3 = new DummyTemplate();

        $template1->selectable = false;
        $template2->selectable = true;
        $template3->selectable = true;

        $this->templateRepository->registerMany([$template1, $template2, $template3]);
        $selectableTemplates = $this->templateRepository->selectable();

        $this->assertEquals(2, count($selectableTemplates));

        $this->assertSame($template2, array_shift($selectableTemplates));
        $this->assertSame($template3, array_shift($selectableTemplates));
    }

    /** @test */
    public function it_can_find_a_template_by_name()
    {
        $template1 = new DummyTemplate();
        $template2 = new DummyTemplate();

        $template1->name = 'one';
        $template2->name = 'two';

        $this->templateRepository->registerMany([$template1, $template2]);

        $foundTemplate = $this->templateRepository->find('two');
        $this->assertSame($template2, $foundTemplate);

        $foundTemplate = $this->templateRepository->find('one');
        $this->assertSame($template1, $foundTemplate);
    }

    /**
     * @test
     * @expectedException \Exception
     */
    public function it_will_throw_exception_when_template_not_found()
    {
        $template1 = new DummyTemplate();
        $template1->name = 'one';

        $this->templateRepository->registerMany([$template1]);
        $this->templateRepository->find('two');
    }
}
