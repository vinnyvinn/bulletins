<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToJobCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_cards', function (Blueprint $table) {
            $table->unsignedInteger('breakdown_id')->nullable();
            $table->text('closing_remarks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('job_cards', function (Blueprint $table) {
            $table->dropColumn('breakdown_id');
            $table->dropColumn('closing_remarks');
        });
    }
}
