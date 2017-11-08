<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Pages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('timeline_id')->unsigned()->nullable();
            $table->text('address');
            $table->tinyInteger('active');
            $table->integer('category_id')->unsigned()->nullable();
            $table->string('message_privacy',15);
            $table->string('member_privacy',15);
            $table->string('phone',15);
            $table->string('timeline_post_privacy',15);
            $table->string('website',255);
            $table->tinyInteger('verified');
            $table->timestamps();

						$table->foreign('timeline_id')
								->references('id')->on('timelines')
								->onDelete('RESTRICT');
						$table->foreign('category_id')
								->references('id')->on('categories')
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
        Schema::table('pages', function (Blueprint $table) {
            //
        });
    }
}
