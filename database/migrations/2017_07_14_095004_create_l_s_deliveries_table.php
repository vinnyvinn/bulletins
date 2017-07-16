<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use SmoDav\Support\Constants;

class CreateLSDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('l_s_deliveries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vehicle_id')->index()->unsigned();
            $table->integer('contract_id')->index()->unsigned();
            $table->integer('station_id')->index()->unsigned()->nullable();
            $table->text('narration')->nullable();
            $table->integer('bags_loaded')->nullable();
            $table->float('loading_gross_weight')->nullable();
            $table->float('loading_tare_weight')->nullable();
            $table->string('loading_weighbridge_number')->nullable();
            $table->float('loading_net_weight')->nullable();
            $table->float('offloading_gross_weight')->nullable();
            $table->float('offloading_tare_weight')->nullable();
            $table->float('offloading_net_weight')->nullable();
            $table->string('offloading_weighbridge_number')->nullable();
            $table->timestamp('loading_time');
            $table->timestamp('offloading_time')->nullable();
            $table->string('status')->default(Constants::LOADED);
            $table->integer('user_id')->unsigned();
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
        Schema::dropIfExists('l_s_deliveries');
    }
}
