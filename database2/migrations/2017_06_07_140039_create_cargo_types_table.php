<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCargoTypesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('cargo_types', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cargo_classification_id')->index()->unsigned();
            $table->string('name');
            $table->timestamps();

            $table->foreign('cargo_classification_id')
                ->references('id')
                ->on('cargo_classifications')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('cargo_types');
    }
}
