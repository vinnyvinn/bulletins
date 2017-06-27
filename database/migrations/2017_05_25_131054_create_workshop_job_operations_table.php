<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkshopJobOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workshop_job_operations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('workshop_job_type_id')->index()->unsigned();
            $table->string('name');
            $table->timestamps();

            $table->foreign('workshop_job_type_id')->references('id')->on('workshop_job_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workshop_job_operations');
    }
}
