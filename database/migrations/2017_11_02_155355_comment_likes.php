<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CommentLikes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment_likes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('comment_id')->unsigned()->nullable();
            $table->timestamps();

						$table->foreign('user_id')
								->references('id')->on('users')
								->onDelete('RESTRICT');
						$table->foreign('comment_id')
								->references('id')->on('comments')
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
        Schema::table('comment_likes', function (Blueprint $table) {
            //
        });
    }
}
