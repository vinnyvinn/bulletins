<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('username')->unique();
            $table->string('email')->unique()->nullable();
            $table->string('password');
            $table->text('permissions');

            $table->integer('country_id')->nullable();
            $table->string('mobile')->nullable();
            $table->string('address')->nullable();
            $table->string('website')->nullable();
            $table->string('phone')->nullable();
            $table->string('photo')->nullable();
            $table->enum('user_type', ['user', 'super_admin', 'sub_admin','shop_admin'])->default('super_admin');
            $table->enum('active_status', [0, 1, 2])->default(1);
            $table->enum('is_email_verified', [0, 1])->default(1);
            $table->string('activation_code')->nullable();
            $table->enum('is_online', [0, 1])->default(1);
            $table->integer('shop_id')->nullable();
            $table->string('referral_id', 20)->nullable();
            $table->integer('referred_by')->nullable();
            $table->timestamp('last_login')->nullable();

            $table->softDeletes();

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
