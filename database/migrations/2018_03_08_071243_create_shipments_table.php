<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('name');
            $table->string('parcel_number');
            $table->string('parcel_desc');
            $table->mediumInteger('dimension_length')->unsigned();
            $table->mediumInteger('dimension_width')->unsigned();
            $table->mediumInteger('dimension_height')->unsigned();
            $table->mediumInteger('parcel_weight')->unsigned();
            $table->string('postal_company');
            $table->string('shipping_company');
            $table->mediumInteger('shipping_amount')->unsigned();
            $table->string('shipment_tracking');
            $table->datetime('received_on');
            $table->tinyInteger('payment_status')->unsigned();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipments');
    }
}
