<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->string('comment_privacy',15);
            $table->string('follow_privacy',15);
            $table->string('post_privacy',15);
            $table->string('confirm_follow',15);
            $table->string('timeline_post_privacy',15);
            $table->string('message_privacy',255);
            $table->string('email_follow',15);
            $table->string('email_like_post',15);
            $table->string('email_post_share',15);
            $table->string('email_comment_post',15);
            $table->string('email_like_comment',15);
            $table->string('email_reply_comment',15);
            $table->string('email_join_group',15);
            $table->string('email_like_page',15);

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
        Schema::table('user_settings', function (Blueprint $table) {
            //
        });
    }
}
