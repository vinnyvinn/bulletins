<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobCardInspectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_card_inspections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('job_card_id')->index()->unsigned();
            $table->string('inspection');
            $table->string('done_by')->nullable();
            $table->string('status')->nullable();
            $table->text('mechanic_findings');
            $table->timestamps();

            $table->foreign('job_card_id')
                ->references('id')
                ->on('job_cards')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_card_inspections');
    }
}
