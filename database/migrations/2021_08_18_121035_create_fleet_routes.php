<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFleetRoutes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fleet_routes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fleet_id');
            $table->unsignedBigInteger('route_id');
            $table->foreign('fleet_id')->references('id')->on('fleets');
            $table->foreign('route_id')->references('id')->on('routes');
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('fleet_routes');
    }
}
