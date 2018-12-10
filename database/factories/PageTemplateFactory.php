<?php

use Faker\Generator as Faker;
use Optimus\Pages\Models\PageTemplate;
use Optimus\Pages\Tests\DummyTemplate;

$factory->define(PageTemplate::class, function (Faker $faker) {
    return array(
        'label' => $faker->word,
        'name' => $faker->slug,
        'handler' => DummyTemplate::class,
        'is_selectable' => true
    );
});
