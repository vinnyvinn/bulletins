<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLSMileagesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('l_s_mileages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contract_id');
            $table->integer('vehicle_id');
            $table->integer('amount');
            $table->boolean('is_advance')->default(false);
            $table->string('narration')->nullable();
            $table->integer('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('l_s_mileages');
    }
}
