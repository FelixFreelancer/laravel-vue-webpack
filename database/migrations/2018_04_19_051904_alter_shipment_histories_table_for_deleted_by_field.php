<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterShipmentHistoriesTableForDeletedByField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('shipment_histories', function (Blueprint $table) {
          $table->integer('deleted_by')->nullable()->after('shipment_id');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('shipment_histories', function (Blueprint $table) {
          $table->dropColumn('deleted_by');
      });
    }
}
