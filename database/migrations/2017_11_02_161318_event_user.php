<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EventUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('event_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->tinyInteger('paid');
            $table->text('transaction');
            $table->tinyInteger('status');
            $table->timestamps();

						$table->foreign('event_id')
								->references('id')->on('events')
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
        Schema::table('event_user', function (Blueprint $table) {
            //
        });
    }
}
