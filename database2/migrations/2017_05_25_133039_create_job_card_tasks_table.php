<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobCardTasksTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('job_card_tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('job_card_id')->index()->unsigned();
            $table->integer('operation_id')->unsigned()->index();
            $table->integer('workshop_job_task_id')->unsigned()->index();
            $table->integer('employee_id')->unsigned()->index()->nullable();
            $table->timestamp('start_time')->nullable();
            $table->float('total_minutes')->default(0);
            $table->string('status');
            $table->timestamps();

            $table->foreign('job_card_id')
                ->references('id')
                ->on('job_cards')
                ->onDelete('cascade');

            $table->foreign('workshop_job_task_id')
                ->references('id')
                ->on('workshop_job_tasks')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('job_card_tasks');
    }
}
