<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use SmoDav\Support\Constants;

class CreateWorkshopJobTypesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('workshop_job_types', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('service_type', [Constants::WORK_SERVICE, Constants::WORK_NORMAL]);
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('workshop_job_types');
    }
}
