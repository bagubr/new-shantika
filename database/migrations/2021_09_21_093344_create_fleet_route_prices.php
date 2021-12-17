<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFleetRoutePrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fleet_route_prices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('fleet_route_id');
            $table->foreign('fleet_route_id')->on('fleet_routes')->references('id');
            $table->date('start_at');
            $table->date('end_at')->nullable();
            $table->integer('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fleet_route_prices');
    }
}
