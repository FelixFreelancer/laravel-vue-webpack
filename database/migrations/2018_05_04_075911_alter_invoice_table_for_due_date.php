<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterInvoiceTableForDueDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('invoices', function (Blueprint $table) {
          $table->date('due_date')->nullable()->after('invoice_path');
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
          $table->dropColumn(['due_date']);
      });
    }
}
