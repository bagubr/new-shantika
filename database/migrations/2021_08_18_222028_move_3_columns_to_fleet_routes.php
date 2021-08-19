<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Move3ColumnsToFleetRoutes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fleet_routes', function (Blueprint $table) {
            $table->time('departure_at')->nullable();
            $table->time('arrived_at')->nullable();
            $table->integer('price');
        });
        Schema::table('routes', function(Blueprint $table) {
            $table->dropColumn('departure_at');
            $table->dropColumn('arrived_at');
            $table->dropColumn('price');
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
            $table->time('departure_at')->nullable();
            $table->time('arrived_at')->nullable();
            $table->integer('price');
        });
        Schema::table('fleet_routes', function(Blueprint $table) {
            $table->dropColumn('departure_at');
            $table->dropColumn('arrived_at');
            $table->dropColumn('price');
        });
    }
}
