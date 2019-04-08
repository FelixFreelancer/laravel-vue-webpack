<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('password')->nullable();
            $table->tinyInteger('gender')->unsigned();
            $table->string('company_name');
            $table->string('cd_address');
            $table->smallInteger('cd_country')->unsigned();
            $table->mediumInteger('cd_country_code')->unsigned();
            $table->smallInteger('cd_state')->unsigned();
            $table->string('cd_city');
            $table->string('cd_postalcode',64);
            $table->string('cd_phone');
            $table->mediumInteger('ba_country')->unsigned();
            $table->mediumInteger('ba_country_code')->unsigned();
            $table->string('ba_state');
            $table->string('ba_city');
            $table->string('ba_address');
            $table->string('ba_postalcode',64);
            $table->string('ba_phone');
            $table->string('google2fa_secret');
            $table->string('suite_number');
            $table->string('customer_number');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
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
