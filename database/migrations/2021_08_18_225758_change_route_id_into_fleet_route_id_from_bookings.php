<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeRouteIdIntoFleetRouteIdFromBookings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['route_id']);
            $table->renameColumn('route_id', 'fleet_route_id');
            $table->foreign('fleet_route_id')->references('id')->on('fleet_routes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['fleet_route_id']);
            $table->renameColumn('fleet_route_id', 'route_id');
            $table->foreign('route_id')->references('id')->on('routes');
        });
    }
}
