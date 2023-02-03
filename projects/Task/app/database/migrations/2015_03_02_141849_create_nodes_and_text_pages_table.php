<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodesAndTextPagesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'nodes',
            function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('parent_id')->nullable();
                $table->foreign('parent_id')->references('id')->on('nodes');

                $table->string('alias')->nullable()->unique();

                $table->string('name')->default('');
                $table->boolean('publish')->default(false);
                $table->integer('position')->default(0);
                $table->boolean('menu_top')->default(false);

                $table->string('type')->nullable();

                $table->timestamps();
            }
        );

        Schema::create(
            'text_pages',
            function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('node_id');
                $table->foreign('node_id')->references('id')->on('nodes');

                $table->string('header')->default('');
                $table->string('meta_title')->default('');
                $table->string('meta_keywords')->default('');
                $table->string('meta_description')->default('');
                $table->text('content')->default('');

                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('text_pages');
        Schema::drop('nodes');
    }
}
