<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PageLikes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_likes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('page_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->timestamps();

						$table->foreign('page_id')
								->references('id')->on('pages')
								->onDelete('RESTRICT');
						$table->foreign('user_id')
								->references('id')->on('users')
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
        Schema::table('page_likes', function (Blueprint $table) {
            //
        });
    }
}

