<?php

namespace Optimus\Pages\Tests;

use Optimus\Media\Models\Media;
use Optimus\Pages\PageServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            PageServiceProvider::class
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('media.model', Media::class);
    }

    public function setUp()
    {
        parent::setUp();

        $this->withFactories(__DIR__ . '/../database/factories');

        $schemaBuilder = $this->app['db']->connection()->getSchemaBuilder();

        $schemaBuilder->create('media', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });

        $schemaBuilder->create('mediables', function (Blueprint $table) {
            $table->unsignedInteger('media_id')->index();
            $table->unsignedInteger('mediable_id')->index();
            $table->string('mediable_type');
            $table->string('collection');

            $table->foreign('media_id')
                  ->references('id')
                  ->on('media')
                  ->onDelete('cascade');
        });
    }

    public function expectedPageJsonStructure()
    {
        return [
            'id',
            'title',
            'slug',
            'uri',
            'has_fixed_uri',
            'parent_id',
            'template',
            'has_fixed_template',
            'contents' => [
                '*' => [
                    'key',
                    'value'
                ]
            ],
            'media' => [],
            'is_stand_alone',
            'is_published',
            'is_deletable',
            'created_at',
            'updated_at'
        ];
    }
}
