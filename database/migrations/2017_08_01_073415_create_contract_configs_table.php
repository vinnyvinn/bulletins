<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_configs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contract_id')->unique();
            $table->integer('average_fuel')->nullable();
            $table->integer('supervisors')->nullable();
            $table->integer('supervisors_rate')->nullable();
            $table->integer('casuals')->nullable();
            $table->integer('casuals_rate')->nullable();
            $table->integer('operators')->nullable();
            $table->integer('operators_rate')->nullable();
            $table->integer('mechanics')->nullable();
            $table->integer('mechanics_rate')->nullable();
            $table->integer('tail_gates')->nullable();
            $table->integer('tail_gates_rate')->nullable();
            $table->integer('trips_before_refuel')->nullable();
            $table->integer('initial_fuel')->nullable();
            $table->integer('refuel_1')->nullable();
            $table->integer('refuel_2')->nullable();
            $table->integer('refuel_3')->nullable();
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
        Schema::dropIfExists('contract_configs');
    }
}
