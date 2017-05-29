<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUDFsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('u_d_fs', function (Blueprint $table) {
            $table->increments('id');
            $table->string("name");
            $table->string("slug");
            $table->string('input_type');
            $table->string("status");
            $table->string("module");
            $table->string("value")->nullable();
            $table->string("description");
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
        Schema::dropIfExists('u_d_fs');
    }
}
