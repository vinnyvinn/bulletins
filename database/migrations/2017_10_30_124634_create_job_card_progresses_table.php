<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobCardProgressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_card_progresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('job_card_id')->index();
            $table->unsignedInteger('user_id')->index();
            $table->unsignedInteger('employee_id')->index();
            $table->text('update');
            $table->string('job_status');
            $table->string('job_depends_on');
            $table->timestamps();

            $table->foreign('job_card_id')->references('id')->on('job_cards')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_card_progresses');
    }
}
