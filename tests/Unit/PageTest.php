<?php

namespace Optimus\Pages\Tests\Unit;

use Optimus\Pages\Models\Page;
use Optimus\Pages\Tests\TestCase;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageTest extends TestCase
{
    /**
     * @test
     * @dataProvider castToBooleanProvider
     */
    public function it_casts_the_has_fixed_template_attribute_to_a_boolean($value, bool $expected)
    {
        $page = new Page();

        $page->has_fixed_template = $value;

        $this->assertEquals($expected, $page->has_fixed_template);
    }

    /**
     * @test
     * @dataProvider castToBooleanProvider
     */
    public function it_casts_the_has_is_stand_alone_attribute_to_a_boolean($value, bool $expected)
    {
        $page = new Page();

        $page->is_stand_alone = $value;

        $this->assertEquals($expected, $page->is_stand_alone);
    }

    public function castToBooleanProvider()
    {
        return [
            [ 1, true ],
            [ 'false', true ],
            [ 0, false ],
            [ '0', false ],
            [ '', false ]
        ];
    }

    /** @test */
    public function it_registers_the_template_relationship()
    {
        $page = new Page();

        $this->assertInstanceOf(BelongsTo::class, $page->template());
    }

    /** @test */
    public function it_registers_the_parent_relationship()
    {
        $page = new Page();

        $this->assertInstanceOf(BelongsTo::class, $page->parent());
    }

    /** @test */
    public function it_registers_the_children_relationship()
    {
        $page = new Page();

        $this->assertInstanceOf(HasMany::class, $page->children());
    }

    /** @test */
    public function it_registers_the_contents_relationship()
    {
        $page = new Page();

        $this->assertInstanceOf(HasMany::class, $page->contents());
    }
}
