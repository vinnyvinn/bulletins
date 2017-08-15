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
            $table->integer('casuals')->nullable();
            $table->integer('operators')->nullable();
            $table->integer('mechanics')->nullable();
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
