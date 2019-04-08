<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterShipmentsHistoriesTableForAddStatusByField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('shipment_histories', function (Blueprint $table) {
          $table->integer('shipment_id')->nullable()->after('user_id');
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
          $table->dropColumn(['shipment_id']);
      });
    }
}
