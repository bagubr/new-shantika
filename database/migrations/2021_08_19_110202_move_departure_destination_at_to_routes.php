<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MoveDepartureDestinationAtToRoutes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('routes', function (Blueprint $table) {
            $table->time('departure_at')->nullable();
            $table->time('arrived_at')->nullable();
        });
        Schema::table('fleet_routes', function(Blueprint $table) {
            $table->dropColumn('departure_at');
            $table->dropColumn('arrived_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('routes', function (Blueprint $table) {
            $table->dropColumn('departure_at');
            $table->dropColumn('arrived_at');
        });
        Schema::table('fleet_routes', function(Blueprint $table) {
            $table->time('departure_at')->nullable();
            $table->time('arrived_at')->nullable();
        });
    }
}
