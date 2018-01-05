<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractEmployees extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('ls_contract_employees', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contract_id');
            $table->integer('employee_id');
            $table->integer('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('ls_contract_employees');
    }
}
