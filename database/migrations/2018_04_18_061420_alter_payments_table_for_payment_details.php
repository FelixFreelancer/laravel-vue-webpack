<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPaymentsTableForPaymentDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('payments', function (Blueprint $table) {
          $table->string('transaction_id')->nullable()->after('payment_gateway_type');
          $table->string('seller_account_id')->nullable()->after('payment_gateway_type');
          $table->string('merchant_account_id')->nullable()->after('payment_gateway_type');
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
              'transaction_id',
              'seller_account_id',
              'merchant_account_id',
          ]);
      });
    }
}
