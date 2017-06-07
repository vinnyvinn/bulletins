<?php

use App\Support\Core;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrucksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trucks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('plate_number')->unique();
            $table->string('make')->nullable();
            $table->string('model')->nullable();
            $table->integer('max_load')->nullable();
            $table->string('status')->default(Core::ACTIVE);
            $table->string('location')->default(Core::AWAITING_ALLOCATION);
            $table->integer('project_id')->index()->unsigned()->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('trucks');
    }
}
