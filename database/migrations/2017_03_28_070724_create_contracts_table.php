<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use SmoDav\Support\Constants;

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
            $table->string('status')->default(Constants::STATUS_PENDING);
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
            $table->integer('loading_point_id')->nullable();
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
            $table->string('name')->nullable();
            $table->string('rate');
            $table->string('amount');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('quantity');

            $table->boolean('subcontracted')->default('false');
            $table->string('sub_company_name')->nullable();
            $table->string('sub_address_1')->nullable();
            $table->string('sub_address_2')->nullable();
            $table->string('sub_address_3')->nullable();
            $table->string('sub_address_4')->nullable();
            $table->string('sub_delivery_to')->nullable();
            $table->string('sub_delivery_address')->nullable();
            $table->string('sub_delivery_address_2')->nullable();
            $table->string('sub_delivery_address_3')->nullable();
            $table->string('sub_delivery_address_4')->nullable();
            $table->integer('user_id')->unsigned();
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
