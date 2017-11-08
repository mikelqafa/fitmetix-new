<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Posts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->text('description');
            $table->integer('timeline_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->tinyInteger('active');
            $table->string('soundcloud_title',250);
            $table->string('soundcloud_id',255);
            $table->string('youtube_title',255);
            $table->string('youtube_video_id',255);
            $table->string('location',250);
            $table->string('type',100);
            $table->integer('shared_post_id')->unsigned()->nullable();
            $table->timestamps();


						$table->foreign('shared_post_id')
								->references('id')->on('posts')
								->onDelete('RESTRICT');
						$table->foreign('timeline_id')
								->references('id')->on('timelines')
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
        Schema::table('posts', function (Blueprint $table) {
            //
        });
    }
}
