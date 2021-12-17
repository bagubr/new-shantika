<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFleetClassPrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fleet_class_prices', function (Blueprint $table) {
            $table->id();
            $table->integer('area_id');
            $table->foreign('area_id')->references('id')->on('areas');
            $table->integer('fleet_class_id');
            $table->foreign('fleet_class_id')->references('id')->on('fleet_classes');
            $table->date('start_at');
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
        Schema::dropIfExists('fleet_class_prices');
    }
}
