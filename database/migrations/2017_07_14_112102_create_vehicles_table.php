<?php

use App\Support\Core;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('plate_number')->unique();
            $table->string('make')->nullable();
            $table->string('model')->nullable();
            $table->integer('max_load')->nullable();
            $table->string('type')->default(Core::TRUCK);
            $table->string('status')->default(Core::ACTIVE);
            $table->string('location')->default(Core::AWAITING_ALLOCATION);
            $table->integer('project_id')->index()->unsigned()->nullable();
            $table->integer('trailer_id')->nullable()->index()->unsigned();
            $table->integer('truck_id')->nullable()->index()->unsigned();
            $table->integer('driver_id')->nullable()->index()->unsigned();
            $table->integer('current_fuel')->default(0);
            $table->integer('current_km')->default(0);
            $table->softDeletes();
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
        Schema::dropIfExists('vehicles');
    }
}
