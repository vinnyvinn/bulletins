<?php

use App\Support\Core;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->increments('id');
            $table->date('trip_date');
            $table->integer('truck_id')->index()->unsigned();
            $table->integer('contract_id')->index()->unsigned();
            $table->integer('driver_id')->index()->unsigned();
            $table->integer('route_id')->index()->unsigned();
            $table->string('source')->nullable();
            $table->string('destination')->nullable();
            $table->integer('pre_loading_checklist_id')->index()->unsigned()->nullable();
            $table->integer('delivery_note_id')->index()->unsigned()->nullable();
            $table->integer('receive_delivery_note_id')->index()->unsigned()->nullable();
            $table->string('stage')->default(Core::PRE_LOADING);
            $table->boolean('is_complete')->default(false);
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
        Schema::dropIfExists('trips');
    }
}
