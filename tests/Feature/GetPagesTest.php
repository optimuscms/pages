<?php

namespace Optimus\Pages\Tests\Feature;

use Optimus\Pages\Models\Page;
use Optimus\Pages\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetPagesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_display_all_the_pages_in_the_correct_order()
    {
        $secondPage = factory(Page::class)->create(['order' => 2]);
        $firstPage = factory(Page::class)->create(['order' => 1]);
        $thirdPage = factory(Page::class)->create(['order' => 3]);

        $response = $this->getJson(route('admin.pages.index'));

        $response
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => $this->expectedJsonStructure()
                ]
            ])
            ->assertJson([
                'data' => [
                    ['id' => $firstPage->id],
                    ['id' => $secondPage->id],
                    ['id' => $thirdPage->id]
                ]
            ]);
    }

    /** @test */
    public function it_can_filter_pages_by_their_parent()
    {
        $this->assertTrue(true);
    }

    protected function expectedJsonStructure()
    {
        return [
            'id',
            //
        ];
    }
}
