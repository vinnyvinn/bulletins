<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionRoleTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        //      Schema::create('permission_role', function (Blueprint $table) {
//            $table->increments('id');
//            $table->integer('role_id')->unsigned();
//            $table->integer('permission_id')->unsigned();
//            $table->timestamps();
//
//            $table->foreign('role_id')
//                ->references('id')
//                ->on('roles')
//                ->onDelete('cascade');
//
//            $table->foreign('permission_id')
//                ->references('id')
//                ->on('permissions')
//                ->onDelete('cascade');
//        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('permission_role');
    }
}