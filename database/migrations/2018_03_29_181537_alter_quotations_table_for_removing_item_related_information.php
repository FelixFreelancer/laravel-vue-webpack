<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterQuotationsTableForRemovingItemRelatedInformation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn([
                'store_name',
                'direct_link',
                'item_name',
                'user_price_currency',
                'user_price',
                'color',
                'quantity',
                'admin_price_currency',
                'admin_price',
                'admin_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->string('store_name')->nullable();
            $table->string('direct_link', 300)->nullable();
            $table->string('item_name')->nullable();
            $table->string('user_price_currency', 10)->nullable();
            $table->decimal('user_price')->nullable();
            $table->string('color')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('admin_price_currency', 10)->nullable();
            $table->decimal('admin_price')->nullable();
            $table->integer('admin_id')->nullable();
        });
    }
}
