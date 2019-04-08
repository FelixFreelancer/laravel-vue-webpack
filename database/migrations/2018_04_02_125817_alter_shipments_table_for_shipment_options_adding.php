<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterShipmentsTableForShipmentOptionsAdding extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_company',
                'shipping_amount',
                'shipment_tracking',
            ]);
            $table->string('shipping_in_company')->nullable()->after('postal_company');
            $table->string('shipping_in_service')->nullable()->after('shipping_in_company');
            $table->decimal('shipping_in_amount', 22, 2)->nullable()->after('shipping_in_service');
            $table->string('shipping_in_tracking')->nullable()->after('shipping_in_amount');
            $table->string('shipping_out_company')->nullable()->after('shipping_in_tracking');
            $table->string('shipping_out_service')->nullable()->after('shipping_out_company');
            $table->decimal('shipping_out_amount', 22, 2)->nullable()->after('shipping_out_service');
            $table->string('shipping_out_tracking')->nullable()->after('shipping_out_amount');
            $table->string('shipping_out_logo', 500)->nullable()->after('shipping_out_tracking');
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
                'shipping_in_company',
                'shipping_in_service',
                'shipping_in_amount',
                'shipping_in_tracking',
                'shipping_out_company',
                'shipping_out_service',
                'shipping_out_amount',
                'shipping_out_tracking',
                'shipping_out_logo',
            ]);
            $table->string('shipping_company')->nullable()->after('postal_company');
            $table->decimal('shipping_amount', 22, 2)->nullable()->after('shipping_company');
            $table->string('shipment_tracking')->nullable()->after('shipping_amount');
        });
    }
}
