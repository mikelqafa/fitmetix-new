<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TimelineReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timeline_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('timeline_id')->unsigned()->nullable();
            $table->integer('reporter_id')->unsigned()->nullable();
            $table->string('status',200);
            $table->timestamps();

						$table->foreign('timeline_id')
								->references('id')->on('timelines')
								->onDelete('RESTRICT');
						$table->foreign('reporter_id')
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
        Schema::table('timeline_reports', function (Blueprint $table) {
            //
        });
    }
}
