<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LeaveOnlyNameColumnToRoutes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('routes', function (Blueprint $table) {
            // $table->dropForeign(['departure_city_id', 'destination_city_id']);

            $table->dropColumn('departure_city_id');
            $table->dropColumn('destination_city_id');
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
            $table->integer('departure_city_id');
            $table->integer('destination_city_id');
            $table->foreign('departure_city_id')->on('cities')->references('id');
            $table->foreign('destination_city_id')->on('cities')->references('id');
            $table->datetime('departure_at');
            $table->datetime('arrived_at');
        });
    }
}
