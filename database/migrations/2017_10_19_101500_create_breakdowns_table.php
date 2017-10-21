<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBreakdownsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('breakdowns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('vehicle_id')->index();
            $table->unsignedInteger('breakdown_area_id')->index()->nullable();
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('driver_id')->index()->nullable();
            $table->text('incident_details');
            $table->string('vehicle_sent')->nullable();
            $table->unsignedInteger('attended_by')->index();
            $table->text('break_down_recovered');
            $table->text('narration');
            $table->boolean('breakdown_approved')->default(false);
            $table->integer('breakdown_approved_by');
            $table->text('oims');
            $table->timestamps();

            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
            $table->foreign('breakdown_area_id')->references('id')->on('breakdown_areas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('breakdowns');
    }
}
