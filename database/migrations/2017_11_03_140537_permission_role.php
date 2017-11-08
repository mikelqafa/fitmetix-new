<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PermissionRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission_role', function (Blueprint $table) {
            $table->integer('permission_id')->unsigned()->nullable();
            $table->integer('role_id')->unsigned()->nullable();

						$table->primary(['permission_id', 'role_id']);
						$table->foreign('permission_id')
								->references('id')->on('permissions')
								->onDelete('RESTRICT');
						$table->foreign('role_id')
								->references('id')->on('roles')
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
        Schema::table('permission_role', function (Blueprint $table) {
            //
        });
    }
}
