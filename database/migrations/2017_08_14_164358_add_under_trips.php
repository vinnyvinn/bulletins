<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUnderTrips extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('l_s_fuels', function (Blueprint $table) {
            $table->integer('under_trips')->nullable();
            $table->string('reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('l_s_fuels', function (Blueprint $table) {
            //
        });
    }
}
