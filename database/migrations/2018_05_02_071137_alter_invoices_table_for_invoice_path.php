<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterInvoicesTableForInvoicePath extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('invoices', function (Blueprint $table) {
          $table->dropColumn(['shipment_id']);
          $table->dropColumn(['title']);
          $table->dropColumn(['price']);
          $table->dropColumn(['payment_status']);
          $table->dropColumn(['recurring_id']);
          $table->integer('user_id')->nullable()->after('id');
          $table->integer('invoice_number')->nullable()->after('user_id');
          $table->integer('entity_type')->nullable()->after('invoice_number');
          $table->integer('entity_id')->nullable()->after('entity_type');
          $table->string('invoice_name')->nullable()->after('entity_id');
          $table->string('invoice_path')->nullable()->after('invoice_name');
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
          $table->dropColumn(['user_id']);
          $table->dropColumn(['entity_type']);
          $table->dropColumn(['entity_id']);
          $table->dropColumn(['invoice_name']);
          $table->dropColumn(['invoice_path']);
      });
    }
}
