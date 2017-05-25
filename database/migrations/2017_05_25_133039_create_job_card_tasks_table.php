<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobCardTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_card_tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('workshop_job_task_id')->unsigned()->index();
            $table->integer('employee_id')->unsigned()->index()->nullable();
            $table->string('employee_name')->nullable();
            $table->timestamp('start_time')->nullable();
            $table->float('total_minutes')->default(0);
            $table->string('status');
            $table->timestamps();

            $table->foreign('workshop_job_task_id')
                ->references('id')
                ->on('workshop_job_tasks')
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
        Schema::dropIfExists('job_card_tasks');
    }
}
