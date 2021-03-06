<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use SmoDav\Support\Constants;

class CreateJobCardsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('job_cards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('status');
            $table->enum('service_type', [Constants::WORK_NORMAL, Constants::WORK_SERVICE]);
            $table->integer('vehicle_id');
            $table->string('vehicle_type')->default('truck');
            $table->string('vehicle_number')->nullable();
            $table->integer('workshop_job_type_id')->index()->unsigned()->nullable();
            $table->date('expected_completion')->nullable();
            $table->timestamp('time_in')->nullable();
            $table->text('job_description')->nullable();
            $table->text('mechanic_findings')->nullable();
            $table->string('current_km_reading')->nullable();
            $table->string('fuel_balance')->nullable();
            $table->boolean('has_trailer')->default(false);
            $table->text('raw_data')->nullable();

            $table->timestamps();

            $table->foreign('workshop_job_type_id')
                ->references('id')
                ->on('workshop_job_types')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('job_cards');
    }
}
