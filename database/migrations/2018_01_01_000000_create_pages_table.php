<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('label');
            $table->string('handler');
            $table->boolean('is_selectable')->default(true);
            $table->timestamps();
        });

        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('slug')->nullable();
            $table->string('uri')->nullable();
            $table->boolean('has_fixed_uri')->default(false);
            $table->unsignedInteger('parent_id')->index()->nullable();
            $table->unsignedInteger('template_id')->index();
            $table->boolean('has_fixed_template')->default(false);
            $table->boolean('is_stand_alone');
            $table->boolean('is_deletable')->default(true);
            $table->unsignedInteger('order');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->foreign('template_id')
                  ->references('id')
                  ->on('page_templates')
                  ->onDelete('cascade');
        });

        Schema::create('page_contents', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('page_id')->index();
            $table->string('key');
            $table->text('value')->nullable();
            $table->timestamps();

            $table->foreign('page_id')
                  ->references('id')
                  ->on('pages')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('page_contents');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('page_templates');
    }
}
