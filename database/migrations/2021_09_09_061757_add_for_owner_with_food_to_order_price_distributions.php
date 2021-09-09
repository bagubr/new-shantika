<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForOwnerWithFoodToOrderPriceDistributions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_price_distributions', function (Blueprint $table) {
            $table->integer('for_owner_with_food');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_price_distributions', function (Blueprint $table) {
            $table->dropColumn('for_owner_with_food');
        });
    }
}
