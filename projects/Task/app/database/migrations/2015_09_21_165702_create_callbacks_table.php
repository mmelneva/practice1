<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\CallbackStatusConstants as CallbackStatus;

class CreateCallbacksTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $allowedStatuses = [
            CallbackStatus::NOVEL,
            CallbackStatus::EXECUTED,
        ];

        Schema::create(
            'callbacks',
            function (Blueprint $table) use ($allowedStatuses) {
                $table->increments('id');
                $table->timestamps();

                $table->string('name')->default('');
                $table->string('phone')->default('');
                $table->text('comment')->default('');
                $table->enum('callback_status', $allowedStatuses)->default($allowedStatuses[0]);
                $table->string('url_referer')->default('');
                $table->string('appropriate_time')->default('');
            }
        );

        DB::update("ALTER TABLE callbacks AUTO_INCREMENT = 301;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('callbacks');
    }

}
