<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeRouteIdToFleetRouteIdFromScheduleNotOperates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedule_not_operates', function (Blueprint $table) {
            $table->dropForeign(['route_id']);
            $table->dropColumn('route_id');
            $table->integer('fleet_route_id');
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
        Schema::table('schedule_not_operates', function (Blueprint $table) {
            $table->dropForeign(['fleet_route_id']);
            $table->dropColumn('fleet_rote_id');
            $table->integer('route_id');
            $table->foreign('route_id')->references('id')->on('routes');
        });
    }
}
