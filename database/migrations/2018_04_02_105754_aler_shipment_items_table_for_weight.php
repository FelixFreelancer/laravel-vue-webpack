<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlerShipmentItemsTableForWeight extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipment_items', function (Blueprint $table) {
            $table->mediumInteger('weight')->nullable()->after('dimension_height');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipment_items', function (Blueprint $table) {
            $table->dropColumn(['weight']);
        });
    }
}
