<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAclStructure extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'admin_roles',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->default('');
                $table->text('rules')->default('');
                $table->timestamps();
            }
        );

        Schema::table(
            'admin_users',
            function (Blueprint $table) {
                $table->boolean('super')->default(false);
                $table->unsignedInteger('admin_role_id')->nullable();
                $table->foreign('admin_role_id', 'admin_user_role')->references('id')->on('admin_roles');
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
            'admin_users',
            function (Blueprint $table) {
                $table->dropForeign('admin_user_role');
                $table->dropColumn('super');
                $table->dropColumn('admin_role_id');
            }
        );
        Schema::drop('admin_roles');
    }
}
