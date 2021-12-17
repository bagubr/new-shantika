<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTicketPriceToOrderPriceDistributions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_price_distributions', function (Blueprint $table) {
            $table->integer('ticket_price');
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
            $table->dropColumn('ticket_price');
        });
    }
}
