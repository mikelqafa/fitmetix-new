x`<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('comment_id')->unsigned()->nullable();
            $table->integer('reporter_id')->unsigned()->nullable();
            $table->string('description')->nullable();
            $table->string('status',200);
            $table->timestamps();

                        $table->foreign('reporter_id')
                                ->references('id')->on('users');
                        $table->foreign('comment_id')
                                ->references('id')->on('comments');
        });   
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
