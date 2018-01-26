<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrailersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('trailers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('trailer_number')->unique();
            $table->string('make')->nullable();
            $table->string('type')->nullable();
            $table->integer('truck_id')->nullable()->unsigned()->index();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('truck_id')->references('id')->on('trucks')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('trailers');
    }
}
