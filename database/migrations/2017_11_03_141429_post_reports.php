<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PostReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('post_id')->unsigned()->nullable();
            $table->integer('reporter_id')->unsigned()->nullable();
            $table->timestamp('status',200);
            $table->timestamps();

						$table->foreign('reporter_id')
								->references('id')->on('users')
								->onDelete('RESTRICT');
						$table->foreign('post_id')
								->references('id')->on('permissions')
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
        Schema::table('post_reports', function (Blueprint $table) {
            //
        });
    }
}
