<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenamePriceIntoDeviationPriceFromFleetRoutePrices extends Migration
{
    /**
     * Run the migrations.  
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fleet_route_prices', function (Blueprint $table) {
            $table->renameColumn('price', 'deviation_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fleet_route_prices', function (Blueprint $table) {
            $table->renameColumn('deviation_price', 'price'); 
        });
    }
}
