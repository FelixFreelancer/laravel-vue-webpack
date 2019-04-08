<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->string('store_name')->nullable();
            $table->string('direct_link')->nullable();
            $table->string('item_name')->nullable();
            $table->integer('user_price_currency')->nullable();
            $table->decimal('user_price')->nullable();
            $table->string('color')->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('admin_price_currency')->nullable();
            $table->decimal('admin_price')->nullable();
            $table->integer('admin_id')->nullable();
            $table->boolean('status')->nullable();
            $table->integer('status_by')->nullable();
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
        Schema::dropIfExists('quotations');
    }
}
