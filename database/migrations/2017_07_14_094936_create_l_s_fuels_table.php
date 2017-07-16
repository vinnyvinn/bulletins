<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use SmoDav\Support\Constants;

class CreateLSFuelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('l_s_fuels', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('station_id')->index()->unsigned()->nullable();
            $table->integer('contract_id')->unsigned();
            $table->integer('vehicle_id');
            $table->string('narration')->nullable();
            $table->integer('fuel_issued')->default(0);
            $table->integer('current_km')->default(0);
            $table->integer('total_in_tank')->default(0);
            $table->string('status')->default(Constants::STATUS_PENDING);
            $table->integer('created_by');
            $table->integer('approved_by')->nullable();
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
        Schema::dropIfExists('l_s_fuels');
    }
}
