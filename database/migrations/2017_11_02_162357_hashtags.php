<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Hashtags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hashtags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tag',200);
            $table->timestamp('last_trend_time');
            $table->bigInteger('count');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hashtags', function (Blueprint $table) {
            //
        });
    }
}
