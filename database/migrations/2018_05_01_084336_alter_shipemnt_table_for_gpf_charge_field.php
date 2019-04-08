<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterShipemntTableForGpfChargeField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('shipments', function (Blueprint $table) {
          $table->float('gpf_charge')->nullable()->after('shipping_out_amount');
          $table->float('total')->nullable()->after('gpf_charge');
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
          $table->dropColumn(['gpf_charge']);
          $table->dropColumn(['total']);
      });
    }
}
