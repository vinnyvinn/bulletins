<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFuelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fuels', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('journey_id')->unsigned();
            $table->date('date');
            $table->float('current_fuel');
            $table->float('fuel_requested');
            $table->float('fuel_issued');
            $table->float('fuel_total');
            $table->string('narration')->nullable();
            $table->integer('previous_km')->default(0);
            $table->integer('previous_fuel')->default(0);
            $table->integer('current_km')->default(0);
            $table->string('status')->default('Awaiting Approval');
            $table->integer('user_id')->nullable();
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
        Schema::dropIfExists('fuels');
    }
}
