<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PageUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('page_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('role_id')->unsigned()->nullable();
            $table->tinyInteger('active');
            $table->timestamps();

						$table->foreign('page_id')
								->references('id')->on('pages')
								->onDelete('RESTRICT');
						$table->foreign('user_id')
								->references('id')->on('users')
								->onDelete('RESTRICT');
						$table->foreign('role_id')
								->references('id')->on('roles')
								->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('page_user', function (Blueprint $table) {
            //
        });
    }
}
