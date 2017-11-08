<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Followers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('followers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('follower_id')->unsigned()->nullable();
            $table->integer('leader_id')->unsigned()->nullable();
            $table->string('status',50);
            $table->timestamps();

						$table->foreign('follower_id')
								->references('id')->on('users')
								->onDelete('RESTRICT');
						$table->foreign('leader_id')
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
        Schema::table('followers', function (Blueprint $table) {
            //
        });
    }
}
