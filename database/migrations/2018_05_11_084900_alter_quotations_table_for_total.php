<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterQuotationsTableForTotal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('quotations', function (Blueprint $table) {
          $table->float('total')->nullable()->after('status_by');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('quotations', function (Blueprint $table) {
          $table->dropColumn(['total']);
      });
    }
}
