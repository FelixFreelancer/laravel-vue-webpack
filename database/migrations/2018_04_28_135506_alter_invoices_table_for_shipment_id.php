<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterInvoicesTableForShipmentId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('invoices', function (Blueprint $table) {
          $table->integer('shipment_id')->nullable()->after('id');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('invoices', function (Blueprint $table) {
          $table->dropColumn(['shipment_id']);
      });
    }
}
