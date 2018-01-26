<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToRoutes extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('routes', function (Blueprint $table) {
            $table->integer('return_mileage')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('routes', function (Blueprint $table) {
            $table->dropColumn('return_mileage');
        });
    }
}
