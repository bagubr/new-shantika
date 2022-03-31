<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoutePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('route_prices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('agency_id');
            $table->foreign('agency_id')->references('id')->on('agencies');
            $table->bigInteger('price');
            $table->datetime('start_at');
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
        Schema::dropIfExists('route_prices');
    }
}
