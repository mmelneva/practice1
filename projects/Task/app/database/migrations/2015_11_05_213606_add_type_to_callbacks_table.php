<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\CallbackTypeConstants as CallbackType;

class AddTypeToCallbacksTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $allowedTypes = [
            CallbackType::CALLBACK,
            CallbackType::MEASUREMENT,
        ];

        Schema::table(
            'callbacks',
            function (Blueprint $table) use ($allowedTypes) {
                $table->enum('type', $allowedTypes)->default($allowedTypes[0]);
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
        Schema::table(
            'callbacks',
            function (Blueprint $table) {
                $table->dropColumn('type');
            }
        );
    }
}
