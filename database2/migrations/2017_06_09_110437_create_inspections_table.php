<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInspectionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('inspections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('station_id')->index()->unsigned()->nullable();
            $table->bigInteger('journey_id')->unsigned()->index();
            $table->string('status')->default('Pending');
            $table->string('from_station');
            $table->string('to_station');
            $table->text('fields');
            $table->integer('inspector_id')->index()->unsigned()->nullable();
            $table->integer('supervisor_id')->index()->unsigned()->nullable();
            $table->text('inspectors_comments')->nullable();
            $table->text('supervisors_comments')->nullable();
            $table->boolean('suitable_for_loading')->default(false);
            $table->timestamps();

            $table->foreign('journey_id')->references('id')->on('journeys')->onDelete('cascade');
            $table->foreign('station_id')->references('id')->on('stations')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('inspections');
    }
}
