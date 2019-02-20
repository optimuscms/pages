<?php

namespace Optimus\Pages\Tests\Unit;

use Optimus\Pages\Tests\TestCase;
use Optimus\Pages\Models\PageTemplate;
use Optimus\Pages\Tests\DummyTemplate;

class PageTemplateTest extends TestCase
{
    /** @test */
    public function it_casts_the_handler_attribute_to_an_instance()
    {
        $template = new PageTemplate();

        $handler = DummyTemplate::class;

        $template->handler = $handler;

        $this->assertInstanceOf($handler, $template->handler);
    }
}
