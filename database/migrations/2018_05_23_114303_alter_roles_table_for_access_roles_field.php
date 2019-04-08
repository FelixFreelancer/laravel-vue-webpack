<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRolesTableForAccessRolesField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('roles', function (Blueprint $table) {
			$table->string('access_roles')->nullable()->after('id');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn(['access_roles']);
        });
    }
}
