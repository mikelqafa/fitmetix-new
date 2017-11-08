<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Comments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('post_id')->unsigned()->nullable();
            $table->text('description');
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('parent_id')->unsigned()->nullable();
            $table->integer('media_id')->unsigned()->nullable();
            $table->timestamps();

						$table->foreign('post_id')
								->references('id')->on('posts')
								->onDelete('RESTRICT');
						$table->foreign('user_id')
								->references('id')->on('users')
								->onDelete('RESTRICT');
						$table->foreign('parent_id')
								->references('id')->on('comments')
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
        Schema::table('comments', function (Blueprint $table) {
            //
        });
    }
}
