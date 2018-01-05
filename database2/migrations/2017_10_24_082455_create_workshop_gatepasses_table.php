
<?php

use App\WorkshopGatepass;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkshopGatepassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workshop_gatepasses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('external_service_id')->index()->nullable();
            $table->unsignedBigInteger('job_card_id')->nullable();
            $table->unsignedInteger('driver_id')->nullable();
            $table->unsignedInteger('supplier_id')->nullable();
            $table->string('supplier_name');
            $table->string('type');
            $table->double('fuel_reading')->nullable();
            $table->double('km_reading')->nullable();
            $table->text('remarks');
            $table->text('parts');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workshop_gatepasses');
    }
}
