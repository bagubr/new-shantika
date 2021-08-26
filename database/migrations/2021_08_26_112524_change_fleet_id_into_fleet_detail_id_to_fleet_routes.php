<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFleetIdIntoFleetDetailIdToFleetRoutes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fleet_routes', function (Blueprint $table) {
            $table->dropForeign(['fleet_id']);
            $table->dropColumn('fleet_id');
            $table->integer('fleet_detail_id')->nullable();
            $table->foreign('fleet_detail_id')->on('fleet_details')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fleet_routes', function (Blueprint $table) {
            $table->dropForeign(['fleet_detail_id']);
            $table->integer('fleet_id');
            $table->foreign('fleet_id')->on('fleets')->references('id');
        });
    }
}
