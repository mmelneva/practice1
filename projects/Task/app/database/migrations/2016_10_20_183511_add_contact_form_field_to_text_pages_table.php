<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use App\Models\CallbackTypeConstants as CallbackType;

class AddContactFormFieldToTextPagesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'text_pages',
            function (Blueprint $table) {
                $table->boolean('contact_form')->default(false);
            }
        );

        $allowedTypes = [
            CallbackType::CALLBACK,
            CallbackType::MEASUREMENT,
            CallbackType::CONTACTS,
        ];

        DB::update(" ALTER TABLE `callbacks` CHANGE `type` `type` enum('".implode("','", $allowedTypes)."')  COLLATE utf8_unicode_ci NOT NULL DEFAULT '{$allowedTypes[0]}';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(
            'text_pages',
            function (Blueprint $table) {
                $table->dropColumn('contact_form');
            }
        );

        $allowedTypes = [
            CallbackType::CALLBACK,
            CallbackType::MEASUREMENT,
        ];

        DB::update(" ALTER TABLE `callbacks` CHANGE `type` `type` enum('".implode("','", $allowedTypes)."')  COLLATE utf8_unicode_ci NOT NULL DEFAULT '{$allowedTypes[0]}';");
    }

}
