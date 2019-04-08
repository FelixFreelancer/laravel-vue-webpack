<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterShipmentsTableForAddTrackingDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('shipments', function (Blueprint $table) {
          $table->timestamp('shipping_out_at')->nullable()->after('shipping_out_tracking');
          $table->date('delivered_at')->nullable()->after('shipping_out_at');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('shipments', function (Blueprint $table) {
          $table->dropColumn([
              'shipping_out_at',
              'delivered_at'
          ]);
      });
    }
}
