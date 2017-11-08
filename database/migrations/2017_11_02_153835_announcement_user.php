<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AnnouncementUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('announcement_user', function (Blueprint $table) {
						$table->increments('id');
						$table->integer('announcement_id')->unsigned()->nullable();
						$table->integer('user_id')->unsigned()->nullable();
						$table->timestamps();

						$table->foreign('announcement_id')
								->references('id')->on('announcements')
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
        Schema::table('announcement_user', function (Blueprint $table) {
            //
        });
    }
}
