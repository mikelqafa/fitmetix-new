<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Reviews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('rating');
            $table->string('message',250);
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('event_id')->unsigned()->nullable();
            $table->tinyInteger('status');
            $table->timestamps();

						$table->foreign('user_id')
								->references('id')->on('users')
								->onDelete('RESTRICT');
						$table->foreign('event_id')
								->references('id')->on('events')
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
        Schema::table('reviews', function (Blueprint $table) {
            //
        });
    }
}
