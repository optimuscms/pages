<?php

namespace Optimus\Pages\Tests\Unit;

use Mockery;
use Illuminate\Http\Request;
use Optimus\Pages\Tests\TestCase;
use Optimus\Pages\Models\PageTemplate;
use Optimus\Pages\Tests\DummyTemplate;
use Illuminate\Database\Eloquent\Builder;

class PageTemplateTest extends TestCase
{
    /** @test */
    public function it_casts_the_handler_attribute_to_an_instance()
    {
        $template = new PageTemplate();

        $template->handler = $handler = DummyTemplate::class;

        $this->assertInstanceOf($handler, $template->handler);
    }

    /** @test */
    public function it_can_filter_query_results()
    {
        $template = new PageTemplate();

        $request = Mockery::mock(Request::class);
        $request->shouldReceive('filled')->with('is_selectable')->once()->andReturnTrue();
        $request->shouldReceive('input')->with('is_selectable')->once()->andReturn(1);

        $query = Mockery::mock(Builder::class)->makePartial();
        $query->shouldReceive('where')->with('is_selectable', true)->once()->andReturnSelf();

        $template->scopeFilter($query, $request);
    }
}
