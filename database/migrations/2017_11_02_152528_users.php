<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Users extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('timeline_id')->unsigned()->nullable();
            $table->string('email',250);
            $table->string('verification_code',250);
            $table->tinyInteger('verified');
            $table->tinyInteger('email_verified');
            $table->string('remember_token',250);
            $table->string('password',250);
            $table->decimal('balance',10,2);
            $table->date('birthday');
            $table->string('city',100);
            $table->string('country',100);
            $table->string('designation',255);
            $table->string('hobbies',255);
            $table->string('interests',255);
            $table->string('custom_option1',255);
            $table->string('custom_option2',255);
            $table->string('custom_option3',255);
            $table->string('custom_option4',255);
						$table->string('gender',255);
						$table->tinyInteger('active');
						$table->timestamp('last_logged');
						$table->string('timezone',255);
						$table->integer('affiliate_id')->unsigned()->nullable();
						$table->string('language',15);
						$table->string('facebook_link',250);
						$table->string('twitter_link',250);
						$table->string('dribbble_link',250);
						$table->string('instagram_link',250);
						$table->string('youtube_link',250);
						$table->string('linkedin_link',250);
						$table->timestamps();

						$table->foreign('affiliate_id')
								->references('id')->on('users')
								->onDelete('RESTRICT');
						$table->foreign('timeline_id')
								->references('id')->on('timelines')
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
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
