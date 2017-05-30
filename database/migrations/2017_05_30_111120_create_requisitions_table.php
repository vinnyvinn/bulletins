<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use SmoDav\Support\Constants;

class CreateRequisitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requisitions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('job_card_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index();
            $table->text('mechanic_findings')->nullable();
            $table->text('raw_data')->nullable();
            $table->string('status')->default(Constants::STATUS_PENDING);
            $table->timestamps();

            $table->foreign('job_card_id')
                ->references('id')
                ->on('job_cards')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requisitions');
    }
}
