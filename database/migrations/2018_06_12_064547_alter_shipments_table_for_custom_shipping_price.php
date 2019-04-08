<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterShipmentsTableForCustomShippingPrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('shipments', function (Blueprint $table) {
			$table->float('custom_shipping_price')->nullable()->after('total');
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
			$table->dropColumn(['custom_shipping_price']);
		});
    }
}
