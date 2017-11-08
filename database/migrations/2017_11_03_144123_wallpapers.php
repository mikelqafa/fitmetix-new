<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Wallpapers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallpapers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title',255);
            $table->integer('media_id')->unsigned()->nullable();
            $table->timestamps();

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
        Schema::table('wallpapers', function (Blueprint $table) {
            //
        });
    }
}
