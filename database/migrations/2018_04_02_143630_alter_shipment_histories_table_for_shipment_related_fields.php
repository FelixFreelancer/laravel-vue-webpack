<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterShipmentHistoriesTableForShipmentRelatedFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipment_histories', function (Blueprint $table) {
            $table->integer('user_id')->nullable()->after('id');
            $table->integer('status_id')->nullable()->after('user_id');
            $table->string('notes', 1000)->nullable()->after('status_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipment_histories', function (Blueprint $table) {
            $table->dropColumn([
                'user_id',
                'status_id',
                'notes',
            ]);
        });
    }
}
