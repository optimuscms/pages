<?php

namespace Optimus\Pages\Tests\Unit;

use Mockery;
use Optimus\Pages\Models\Page;
use Optimus\Pages\Tests\TestCase;
use Optimus\Pages\Models\PageContent;
use Optimus\Pages\TemplateRepository;
use Optimus\Pages\Tests\DummyTemplate;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageTest extends TestCase
{
    /** @test */
    public function it_sets_where_to_save_slug()
    {
        $page = new Page(['title' => 'Title']);
        $options = $page->getSlugOptions();
        $this->assertSame('slug', $options->slugField);
    }

    /** @test */
    public function it_sets_the_source_for_slug_generation()
    {
        $page = new Page(['title' => 'Title']);
        $options = $page->getSlugOptions();
        $this->assertSame(['title'], $options->generateSlugFrom);
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

    /** @test */
    public function it_can_generate_a_uri()
    {
        $parentPage = new Page();
        $parentPage->slug = 'foo';
        $parentPage->parent = null;
        $parentPage->uri = $parentPage->generateUri();

        $this->assertEquals('foo', $parentPage->uri);

        $childPage = new Page();
        $childPage->slug = 'bar';
        $childPage->setRelation('parent', $parentPage);

        $this->assertEquals('foo/bar', $childPage->generateUri());
    }

    /** @test */
    public function it_can_add_contents()
    {
        $page = Mockery::mock(Page::class)->makePartial();

        $relationship = Mockery::mock(HasMany::class);
        $relationship->shouldReceive('createMany')->once()->with(
            [
                [
                    'key' => 'foo',
                    'value' => 'bar',
                ],
                [
                    'key' => 'bar',
                    'value' => 'foo',
                ],
            ]
        )->andReturnTrue();

        $page->shouldReceive('contents')->once()->andReturn($relationship);

        $page->addContents(
            [
                'foo' => 'bar',
                'bar' => 'foo',
            ]
        );
    }

    /** @test */
    public function it_can_determine_if_a_piece_of_content_exists()
    {
        $page = new Page();

        $page->setRelation(
            'contents',
            $page->newCollection(
                [
                    new PageContent(
                        [
                            'key' => 'foo',
                            'value' => 'bar',
                        ]
                    ),
                ]
            )
        );

        $this->assertTrue($page->hasContent('foo'));
        $this->assertFalse($page->hasContent('bar'));
    }

    /** @test */
    public function it_can_get_the_value_of_a_piece_of_content()
    {
        $page = new Page();

        $page->setRelation(
            'contents',
            $page->newCollection(
                [
                    new PageContent(
                        [
                            'key' => 'foo',
                            'value' => 'bar',
                        ]
                    ),
                ]
            )
        );

        $this->assertEquals('bar', $page->getContent('foo'));
    }

    /** @test */
    public function it_can_delete_all_of_its_contents()
    {
        $page = Mockery::mock(Page::class)->makePartial();

        $relationship = Mockery::mock(HasMany::class);
        $relationship->shouldReceive('delete')->once()->andReturnTrue();

        $page->shouldReceive('contents')->once()->andReturn($relationship);

        $page->deleteContents();
    }

    /** @test */
    public function it_can_retrieve_the_template()
    {
        $template = new DummyTemplate();
        $page = new Page(['template' => $template->name]);
        $this->app[TemplateRepository::class]->register($template);

        $foundTemplate = $page->getTemplateHandlerAttribute();
        $this->assertSame($template, $foundTemplate);
    }
}
