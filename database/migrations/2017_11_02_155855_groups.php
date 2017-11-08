<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Groups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('timeline_id')->unsigned()->nullable();
            $table->tinyInteger('active');
            $table->string('type',200);
            $table->string('member_privacy',200);
            $table->string('post_privacy',200);
            $table->string('event_privacy',200);
            $table->timestamps();

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
        Schema::table('groups', function (Blueprint $table) {
            //
        });
    }
}
