<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsStructure extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'setting_groups',
            function (Blueprint $table) {
                $table->increments('id');
                $table->timestamps();

                $table->string('name')->unique();
                $table->integer('position')->default(0);
            }
        );

        Schema::create(
            'settings',
            function (Blueprint $table) {
                $table->increments('id');
                $table->timestamps();

                $table->string('key')->unique();
                $table->string('title')->default('');
                $table->text('value')->default('');
                $table->text('description')->default('');
                $table->string('value_style')->default('');
                $table->integer('position')->default(0);

                $table->unsignedInteger('group_id');
                $table->foreign('group_id', 'fk_setting_group')->references('id')->on('setting_groups');
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
        Schema::drop('settings');
        Schema::drop('setting_groups');
    }
}
