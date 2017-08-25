<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAllowRouteChangeColumnToContractsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->boolean('allow_route_change')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('contracts', function (Blueprint $table) {
            //
        });
    }
}
