<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChecklistsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('checklists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('status')->default('Pending');
            $table->bigInteger('driver_id')->unsigned()->index()->nullable();
            $table->integer('truck_id')->unsigned()->index()->nullable();
            $table->string('type');
            $table->string('from_station');
            $table->string('to_station');
            $table->text('fields');
            $table->integer('inspector_id')->index()->unsigned();
            $table->integer('supervisor_id')->index()->unsigned();
            $table->text('inspectors_comments')->nullable();
            $table->text('supervisors_comments')->nullable();
            $table->boolean('suitable_for_loading')->default(false);
            $table->timestamps();
            $table->date('for_date');

            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('set null');
            $table->foreign('truck_id')->references('id')->on('trucks')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('checklists');
    }
}
