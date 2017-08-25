<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ContractTruck extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('contract_truck', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('contract_id')->unsigned()->nullable();
            $table->integer('vehicle_id')->unsigned()->nullable();
            $table->timestamps();
            $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('cascade');
            $table->foreign('vehicle_id')->references('id')->on('trucks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('contract_truck');
    }
}
