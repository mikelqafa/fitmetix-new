<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlbumMedia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('album_media', function (Blueprint $table) {
        		$table->increments('id');
            $table->integer('album_id')->unsigned()->nullable();
            $table->integer('media_id')->unsigned()->nullable();

						$table->foreign('media_id')
								->references('id')->on('media')
								->onDelete('RESTRICT');
						$table->foreign('album_id')
								->references('id')->on('albums')
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
        Schema::table('album_media', function (Blueprint $table) {
            //
        });
    }
}
