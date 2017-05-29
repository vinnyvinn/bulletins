<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobCardInspectionCheckListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_card_inspection_check_lists', function (Blueprint $table) {
            $table->bigInteger('job_card_inspection_id')->unsigned()->index();
            $table->integer('workshop_inspection_check_list_id')->unsigned()->index();
            $table->integer('employee_id')->unsigned()->index();
            $table->string('status');
            $table->timestamps();

            $table->foreign('job_card_inspection_id')
                ->references('id')
                ->on('job_card_inspections')
                ->onDelete('cascade');

            $table->foreign('workshop_inspection_check_list_id')
                ->references('id')
                ->on('workshop_inspection_check_lists')
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
        Schema::dropIfExists('job_card_inspection_check_lists');
    }
}
