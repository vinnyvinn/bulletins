<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkshopEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('workshop_employees', function (Blueprint $table) {
            $table->integer('id')->unique();
            $table->string('payroll_number')->nullable();
            $table->string('avatar')->nullable();
            $table->string('identification_number');
            $table->enum('identification_type', ['National ID', 'Passport']);
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('mobile_phone', 13)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('workshop_employees');
    }
}
