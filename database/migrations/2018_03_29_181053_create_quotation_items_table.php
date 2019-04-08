<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotation_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quotation_id')->nullable();
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
        Schema::dropIfExists('quotation_items');
    }
}
