<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Events extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('timeline_id')->unsigned()->nullable();
            $table->string('type',255);
            $table->string('location',255);
						$table->integer('user_id')->unsigned()->nullable();
						$table->date('start_date');
						$table->date('end_date');
						$table->tinyInteger('active');
						$table->decimal('price',10,2);
						$table->integer('user_limit');
						$table->string('invite_privacy',255);
						$table->string('timeline_post_privacy',255);
						$table->integer('group_id')->unsigned()->nullable();
						$table->string('gender',255);
						$table->string('focus',255);
						$table->string('frequency',255);

						$table->foreign('group_id')
								->references('id')->on('groups')
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
        Schema::table('events', function (Blueprint $table) {
            //
        });
    }
}
