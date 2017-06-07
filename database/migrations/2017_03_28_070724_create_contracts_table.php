<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('raw')->nullale();
            $table->integer('cargo_classification_id');
            $table->integer('cargo_type_id');
            $table->integer('trucks_allocated');
            $table->text('job_description');
            $table->boolean('capture_loading_weights')->default(false);
            $table->boolean('capture_offloading_weights')->default(false);
            $table->boolean('ls_loading_weights')->default(false);
            $table->boolean('ls_offloading_weights')->default(false);
            $table->boolean('lh_loading_weights')->default(false);
            $table->boolean('lh_offloading_weights')->default(false);
            $table->boolean('loading_point_id')->default(false);
            $table->text('unloading_points')->nullable();
            $table->string('enquiry_from')->nullable();
            $table->string('contract_head')->nullable();
            $table->boolean('packages_captured')->default(false);
            $table->integer('estimated_days');
            $table->string('lot_number')->nullable();
            $table->string('shipping_line')->nullable();
            $table->string('berth_no')->nullable();
            $table->string('vessel_name')->nullable();
            $table->date('berthing_date')->nullable();
            $table->date('vessel_arrival_date')->nullable();
            $table->string('no_of_shifts')->nullable();
            $table->text('shifts')->nullable();
            $table->integer('stock_item_id')->nullable();
            $table->integer('client_id')->unsigned()->index();
            $table->integer('route_id')->unsigned()->index()->nullable();
            $table->string('name');
            $table->string('rate');
            $table->string('amount');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('quantity');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('route_id')->references('id')->on('routes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contracts');
    }
}
