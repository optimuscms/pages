<?php

use Faker\Generator as Faker;
use Optimus\Pages\Models\Page;
use Optimus\Pages\Models\PageTemplate;

$factory->define(Page::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'parent_id' => null,
        'template_id' => function () {
            return factory(PageTemplate::class)->create()->id;
        },
        'is_stand_alone' => false,
        'published_at' => now(),
        'order' => Page::max('order') + 1
    ];
});

$factory->state(Page::class, 'draft', function () {
    return [
        'published_at' => null
    ];
});
