<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use SmoDav\Support\Constants;

class CreateJourneysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('journeys', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('raw');
            $table->string('status')->default(Constants::STATUS_PENDING);
            $table->boolean('is_contract_related')->default('false');
            $table->bigInteger('contract_id')->index()->unsigned();
            $table->string('journey_type');
            $table->date('job_date');
            $table->integer('truck_id')->index()->unsigned();
            $table->bigInteger('driver_id')->index()->unsigned();
            $table->string('ref_no')->nullable();

            $table->integer('route_id')->index()->unsigned()->nullable();
            $table->text('job_description')->nullable();
            $table->string('enquiry_from')->nullable();

            $table->boolean('subcontracted')->default('false');
            $table->string('sub_company_name')->nullable();
            $table->string('sub_address_1')->nullable();
            $table->string('sub_address_2')->nullable();
            $table->string('sub_address_3')->nullable();
            $table->string('sub_address_4')->nullable();

            $table->timestamps();

            $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('cascade');
            $table->foreign('truck_id')->references('id')->on('trucks')->onDelete('cascade');
            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('cascade');
            $table->foreign('route_id')->references('id')->on('routes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('journeys');
    }
}
