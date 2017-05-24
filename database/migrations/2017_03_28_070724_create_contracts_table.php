<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('stock_item_id')->nullable();
            $table->integer('client_id')->unsigned()->index();
            $table->integer('route_id')->unsigned()->index()->nullable();
            $table->string('name');
            $table->string('rate');
            $table->string('amount');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('quantity');
            $table->softDeletes();
            $table->timestamps();

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
        Schema::dropIfExists('contracts');
    }
}
