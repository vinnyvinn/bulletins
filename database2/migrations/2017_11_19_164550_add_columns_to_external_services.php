<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToExternalServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('external_services', function (Blueprint $table) {
            $table->integer('vendor_id')->nullable()->index();
            $table->double('approximate_cost')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('external_services', function (Blueprint $table) {
            $table->dropColumn(['vendor_id', 'approximate_cost']);
        });
    }
}
