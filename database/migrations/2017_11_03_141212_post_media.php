<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PostMedia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_media', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('post_id')->unsigned()->nullable();
            $table->integer('media_id')->unsigned()->nullable();
            $table->timestamps();

						$table->foreign('post_id')
								->references('id')->on('posts')
								->onDelete('RESTRICT');
						$table->foreign('media_id')
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
        Schema::table('post_media', function (Blueprint $table) {
            //
        });
    }
}
