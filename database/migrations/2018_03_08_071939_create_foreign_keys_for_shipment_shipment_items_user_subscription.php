<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForeignKeysForShipmentShipmentItemsUserSubscription extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_subscriptions', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
        Schema::table('user_subscriptions', function (Blueprint $table) {
            $table->foreign('subscription_id')->references('id')->on('subscriptions')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
        Schema::table('shipments', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
        Schema::table('shipment_items', function (Blueprint $table) {
            $table->foreign('shipment_id')->references('id')->on('shipments')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_subscriptions', function (Blueprint $table) {
            $table->dropForeign('user_subscription_user_id_foreign');
        });
        Schema::table('user_subscriptions', function (Blueprint $table) {
            $table->dropForeign('user_subscription_subscription_id_foreign');
        });
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropForeign('shipment_user_id_foreign');
        });
        Schema::table('shipment_items', function (Blueprint $table) {
            $table->dropForeign('shipment_item_shipment_id_foreign');
        });
    }
}
