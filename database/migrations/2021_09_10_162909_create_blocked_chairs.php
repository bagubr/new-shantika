<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlockedChairs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blocked_chairs', function (Blueprint $table) {
            $table->id();
            $table->integer('layout_chair_id');
            $table->foreign('layout_chair_id')->references('id')->on('layout_chairs');
            $table->integer('fleet_route_id');
            $table->foreign('fleet_route_id')->references('id')->on('fleet_routes');
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
        Schema::dropIfExists('blocked_chairs');
    }
}
