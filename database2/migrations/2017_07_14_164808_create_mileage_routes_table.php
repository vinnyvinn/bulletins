<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMileageRoutesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Schema::create('mileage_routes', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->integer('mileage_type_id')->unsigned()->index();
        //     $table->integer('route_id')->unsigned()->index();
        //     $table->integer('amount');
        //     $table->timestamps();

        //     $table->foreign('mileage_type_id')->references('id')->on('mileage_types')->onDelete('cascade');
        //     $table->foreign('route_id')->references('id')->on('routes')->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('mileage_routes');
    }
}
