<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersTableForGenderAndOtherDefaultChanges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('gender')->nullable()->change();
            $table->string('company_name')->nullable()->change();
            $table->integer('cd_country')->nullable()->change();
            $table->integer('cd_country_code')->nullable()->change();
            $table->string('cd_city')->nullable()->change();
            $table->integer('cd_postalcode')->nullable()->change();
            $table->string('cd_phone')->nullable()->change();
            $table->integer('ba_country')->nullable()->change();
            $table->integer('ba_country_code')->nullable()->change();
            $table->string('ba_city')->nullable()->change();
            $table->integer('ba_postalcode')->nullable()->change();
            $table->string('ba_phone')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('gender')->change();
            $table->string('company_name')->change();
            $table->integer('cd_country')->change();
            $table->integer('cd_country_code')->change();
            $table->string('cd_city')->change();
            $table->integer('cd_postalcode')->change();
            $table->string('cd_phone')->change();
            $table->integer('ba_country')->change();
            $table->integer('ba_country_code')->change();
            $table->string('ba_city')->change();
            $table->integer('ba_postalcode')->change();
            $table->string('ba_phone')->change();
        });
    }
}
