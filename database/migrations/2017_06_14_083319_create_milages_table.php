<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use SmoDav\Support\Constants;

class CreateMilagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mileages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('station_id')->index()->unsigned()->nullable();
            $table->bigInteger('journey_id')->index()->unsigned();
            $table->string('mileage_type');
            $table->integer('standard_amount');
            $table->integer('requested_amount');
            $table->integer('approved_amount')->nullable();
            $table->integer('balance_amount')->nullable();
            $table->text('narration')->nullable();
            $table->text('raw');
            $table->string('status')->default(Constants::STATUS_PENDING);
            $table->boolean('top_up')->default(false);
            $table->string('top_up_reason')->nullable();
            $table->integer('top_up_amount')->nullable();
            $table->integer('user_id')->unsigned();
            $table->timestamps();

            $table->foreign('journey_id')->references('id')->on('journeys')->onDelete('cascade');
            $table->foreign('station_id')->references('id')->on('stations')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mileages');
    }
}
