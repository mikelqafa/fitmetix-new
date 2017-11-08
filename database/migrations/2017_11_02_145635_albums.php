<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Albums extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('albums', function (Blueprint $table) {
						$table->increments('id');
            $table->integer('timeline_id')->unsigned()->nullable();
            $table->string('name',250);
            $table->string('slug',250);
            $table->text('about');
            $table->integer('preview_id')->unsigned()->nullable();
            $table->tinyInteger('active');
            $table->string('privacy',15);
            $table->timestamps();

						$table->foreign('preview_id')
								->references('id')->on('media')
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
        Schema::table('albums', function (Blueprint $table) {
            //
        });
    }
}
