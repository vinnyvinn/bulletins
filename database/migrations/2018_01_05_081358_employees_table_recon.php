<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EmployeesTableRecon extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //drop employees table
        Schema::dropIfExists('employees');
        //create it again
        Schema::create('employees', function (Blueprint $table) {
            $table->integer('id')->unique();
            $table->string('payroll_number')->nullable();
            $table->string('identification_number');
            $table->enum('identification_type', ['National ID', 'Passport']);
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('mobile_phone', 13)->nullable();
            $table->string('category');
            $table->integer('contract_id')->nullable();
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
        //
        Schema::dropIfExists('employees');
    }
}
