<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipmentItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipment_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shipment_id')->unsigned();
            $table->string('item_name');
            $table->mediumInteger('qty')->unsigned();
            $table->mediumInteger('amount')->unsigned();
            $table->string('desc')->nullable();
            $table->mediumInteger('dimension_length')->unsigned();
            $table->mediumInteger('dimension_width')->unsigned();
            $table->mediumInteger('dimension_height')->unsigned();
            $table->string('tracking_number');
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
        Schema::dropIfExists('shipment_items');
    }
}
