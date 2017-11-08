<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Timelines extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timelines', function (Blueprint $table) {
						$table->increments('id');
            $table->integer('username')->unsigned()->nullable();
            $table->string('name',250);
            $table->text('about');
            $table->integer('avatar_id')->unsigned()->nullable();
            $table->integer('cover_id')->unsigned()->nullable();
            $table->string('cover_position',255);
            $table->string('type',255);
            $table->integer('hide_cover');
            $table->integer('background_id');
            $table->timestamps();

            //$table->index(['username', 'avatar_id','cover_id']);

						$table->foreign('username')
								->references('id')->on('media')
								->onDelete('RESTRICT');
						$table->foreign('avatar_id')
								->references('id')->on('media')
								->onDelete('RESTRICT');
						$table->foreign('cover_id')
								->references('id')->on('media')
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
        Schema::table('timelines', function (Blueprint $table) {
            //
        });
    }
}
