<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Attribute;

class CreateAttributesStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'attributes',
            function (Blueprint $table) {
                $table->increments('id');
                $table->timestamps();
                $table->string('name')->default('');
                $table->string('type')->default(Attribute::TYPE_STRING);
                $table->integer('position')->default(0);
                $table->boolean('publish')->default(false);
                $table->boolean('use_in_filter')->default(false);
            }
        );

        Schema::create(
            'attribute_allowed_values',
            function (Blueprint $table) {
                $table->increments('id');
                $table->timestamps();

                $table->string('value');
                $table->integer('position')->default(0);

                $table->unsignedInteger('attribute_id');
                $table->foreign('attribute_id')->references('id')->on('attributes');
            }
        );

        Schema::create(
            'attribute_values',
            function (Blueprint $table) {
                $table->increments('id');
                $table->timestamps();

                $table->string('value')->nullable();

                $table->unsignedInteger('allowed_value_id')->nullable();
                $table->foreign('allowed_value_id')->references('id')->on('attribute_allowed_values');

                $table->unsignedInteger('attribute_id');
                $table->foreign('attribute_id')->references('id')->on('attributes');

                $table->unsignedInteger('product_id');
                $table->foreign('product_id')->references('id')->on('catalog_products');

                $table->unique(['attribute_id', 'product_id']);
            }
        );

        Schema::create(
            'attribute_multiple_values',
            function (Blueprint $table) {

                $table->unsignedInteger('attribute_value_id');
                $table->foreign('attribute_value_id')->references('id')->on('attribute_values');

                $table->unsignedInteger('allowed_value_id');
                $table->foreign('allowed_value_id')->references('id')->on('attribute_allowed_values');
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
        Schema::drop('attribute_multiple_values');
        Schema::drop('attribute_values');
        Schema::drop('attribute_allowed_values');
        Schema::drop('attributes');
    }
}
