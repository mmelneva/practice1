<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReviewsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'reviews',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->default('');
                $table->string('email')->default('');
                $table->text('comment')->default('');
                $table->text('answer')->default('');
                $table->boolean('publish')->default(false);
                $table->timestamp('date_at');

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
        Schema::drop('reviews');
    }
}
