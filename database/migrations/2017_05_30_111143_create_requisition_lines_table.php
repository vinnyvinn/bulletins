<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequisitionLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requisition_lines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('requisition_id')->unsigned()->index();
            $table->integer('item_id')->unsigned()->index();
            $table->string('item_name');
            $table->integer('requested_quantity');
            $table->integer('approved_quantity')->nullable();
            $table->integer('issued_quantity')->nullable();
            $table->timestamps();

            $table->foreign('requisition_id')
                ->references('id')
                ->on('requisitions')
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
        Schema::dropIfExists('requisition_lines');
    }
}
