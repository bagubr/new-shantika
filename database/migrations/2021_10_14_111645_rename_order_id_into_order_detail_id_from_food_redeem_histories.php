<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameOrderIdIntoOrderDetailIdFromFoodRedeemHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('food_redeem_histories', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->renameColumn('order_id', 'order_detail_id');
            $table->foreign('order_detail_id')->on('order_details')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('food_redeem_histories', function (Blueprint $table) {
            $table->dropForeign(['order_detail_id']);
            $table->renameColumn('order_detail_id', 'order_id');
            $table->foreign('order_id')->on('orders')->references('id');
        });
    }
}
