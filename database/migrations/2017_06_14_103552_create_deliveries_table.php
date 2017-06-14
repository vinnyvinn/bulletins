<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use SmoDav\Support\Constants;

class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('journey_id')->index()->unsigned();
            $table->text('narration')->nullable();
            $table->integer('bags_loaded')->nullable();
            $table->float('loading_gross_weight')->nullable();
            $table->float('loading_tare_weight')->nullable();
            $table->float('loading_net_weight')->nullable();
            $table->string('loading_weighbridge_number')->nullable();
            $table->float('offloading_gross_weight')->nullable();
            $table->float('offloading_tare_weight')->nullable();
            $table->float('offloading_net_weight')->nullable();
            $table->string('offloading_weighbridge_number')->nullable();
            $table->timestamp('loading_time');
            $table->timestamp('offloading_time')->nullable();
            $table->text('raw');
            $table->string('status')->default(Constants::LOADED);
            $table->timestamps();

            $table->foreign('journey_id')->references('id')->on('journeys')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deliveries');
    }
}
