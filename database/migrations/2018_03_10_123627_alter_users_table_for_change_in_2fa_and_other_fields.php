<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersTableForChangeIn2faAndOtherFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('google2fa_secret')->nullable()->change();
            $table->string('suite_number')->nullable()->change();
            $table->string('customer_number')->nullable()->change();
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
            $table->string('google2fa_secret')->change();
            $table->string('suite_number')->change();
            $table->string('customer_number')->change();
        });
    }
}
