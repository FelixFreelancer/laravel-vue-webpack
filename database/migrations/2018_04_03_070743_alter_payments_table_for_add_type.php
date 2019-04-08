<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPaymentsTableForAddType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->text('payment_gateway_response')->nullable();
            $table->string('payment_gateway_status', 124)->nullable();
            $table->integer('entity_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'payment_gateway_response',
                'payment_gateway_status',
                'entity_id'
            ]);
        });
    }
}
