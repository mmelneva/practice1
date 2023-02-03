<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewDateChanges extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->boolean('keep_review_date')->default(false);
        });

        Schema::create('reviews_date_changes', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->unsignedInteger('reviews_id');
            $table->unsignedInteger('iteration');

            $table->foreign('reviews_id', 'reviews_date_review_fk')->references('id')->on('reviews');
            $table->unique(['reviews_id', 'iteration'], 'reviews_date_change_unique');

            $table->timestamp('old_value');
            $table->timestamp('new_value');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('reviews_date_changes');

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('keep_review_date');
        });
    }

}
