<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChangesToContractsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->integer('trucks_allocated')->nullable()->change();
            $table->text('job_description')->nullable()->change();
            $table->integer('estimated_days')->nullable()->change();
            $table->string('rate')->nullable()->change();
            $table->string('amount')->nullable()->change();
            $table->date('start_date')->nullable()->change();
            $table->date('end_date')->nullable()->change();
            $table->integer('quantity')->nullable()->change();
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
