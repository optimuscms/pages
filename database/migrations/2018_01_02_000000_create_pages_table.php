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
            $table->string('name');
            $table->string('handler');
            $table->boolean('is_selectable')->default(true);
            $table->timestamps();
        });

        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('slug')->nullable();
            $table->boolean('has_fixed_slug')->default(false);
            $table->string('uri')->nullable();
            $table->nestedSet();
            $table->unsignedInteger('template_id')->index();
            $table->boolean('has_fixed_template')->default(false);
            $table->boolean('is_stand_alone');
            $table->boolean('is_published');
            $table->boolean('is_deletable')->default(true);
            $table->timestamps();

            $table->foreign('template_id')
                  ->references('id')
                  ->on('page_templates')
                  ->onDelete('cascade');
        });

        Schema::create('page_contents', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('template_id')->index();
            $table->unsignedInteger('page_id')->index();
            $table->string('key');
            $table->text('value')->nullable();
            $table->timestamps();

            $table->foreign('template_id')
                  ->references('id')
                  ->on('page_templates')
                  ->onDelete('cascade');

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
