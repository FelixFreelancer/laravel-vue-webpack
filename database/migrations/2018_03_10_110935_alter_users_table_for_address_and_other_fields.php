<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersTableForAddressAndOtherFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cd_address', 1000)->nullable()->change();
            $table->string('ba_address', 1000)->nullable()->change();
            $table->string('cd_state')->nullable()->change();
            $table->string('ba_state')->nullable()->change();
            $table->string('plan_type')->nullable()->after('customer_number');
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
            $table->string('cd_address')->change();
            $table->string('ba_address')->change();
            $table->smallInteger('cd_state')->nullable()->change();
            $table->smallInteger('ba_state')->nullable()->change();
            $table->dropColumn(['plan_type']);
        });
    }
}
