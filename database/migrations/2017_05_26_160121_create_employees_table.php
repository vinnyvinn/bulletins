<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type')->default('Employee');
            $table->string('payroll_number'); // instead of using employee_id we
            // use payroll number to prevent duplicate keys on relationships
            $table->string('avatar')->nullable();
            $table->string('identification_number');
            $table->enum('identification_type', ['National ID', 'Passport']);
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('mobile_phone', 13);
            $table->softDeletes(); // works also as termination date
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
        Schema::dropIfExists('employees');
    }
}
