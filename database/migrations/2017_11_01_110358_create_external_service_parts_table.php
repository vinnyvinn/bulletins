<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExternalServicePartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('external_service_parts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('external_service_id')->index();
            $table->integer('item_id')->unsigned()->index();
            $table->string('item_name')->nullable();
            $table->string('item_description')->nullable();
            $table->string('item_code')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('location')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('external_service_parts');
    }
}
