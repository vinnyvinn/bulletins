<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnTemporaryDriverLsDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('l_s_deliveries', function (Blueprint $table) {
            $table->bigInteger('temporary_driver')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('l_s_deliveries', function (Blueprint $table) {
            $table->dropColumn('temporary_driver');
        });
    }
}
