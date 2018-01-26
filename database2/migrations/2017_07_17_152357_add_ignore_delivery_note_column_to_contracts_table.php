<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIgnoreDeliveryNoteColumnToContractsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->boolean('ignore_delivery_note')->default(false);
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
