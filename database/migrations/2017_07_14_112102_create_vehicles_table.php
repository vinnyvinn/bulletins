<?php

use App\Support\Core;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('sub_contracted')->default(false);
            $table->string('plate_number');
            $table->integer('make_id')->unsigned()->nullable();
            $table->integer('model_id')->unsigned()->nullable();
            $table->integer('max_load')->nullable();
            $table->string('type')->default(Core::TRUCK);
            $table->string('status')->default(Core::ACTIVE);
            $table->string('location')->default(Core::AWAITING_ALLOCATION);
            $table->integer('project_id')->unsigned()->nullable();
            $table->integer('trailer_id')->unsigned()->nullable();
            $table->integer('truck_id')->unsigned()->nullable();
            $table->integer('driver_id')->unsigned()->nullable();
            $table->integer('current_fuel')->default(0);
            $table->integer('current_km')->default(0);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
}
