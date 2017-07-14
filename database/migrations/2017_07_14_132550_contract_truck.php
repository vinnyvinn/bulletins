<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ContractTruck extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('contract_truck', function (Blueprint $table) {
          $table->integer('contract_id');
          $table->integer('vehicle_id');

          $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('cascade');
          $table->foreign('vehicle_id')->references('id')->on('trucks')->onDelete('cascade');
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
