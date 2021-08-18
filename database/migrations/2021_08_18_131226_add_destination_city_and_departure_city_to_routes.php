<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDestinationCityAndDepartureCityToRoutes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('routes', function (Blueprint $table) {
            $table->unsignedBigInteger('departure_city_id')->nullable();
            $table->unsignedBigInteger('destination_city_id')->nullable();
            $table->foreign('departure_city_id')->references('id')->on('cities');
            $table->foreign('destination_city_id')->references('id')->on('cities');
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
            $table->dropColumn('departure_city_id');
            $table->dropColumn('destination_city_id');
            $table->dropForeign(['departure_city_id', 'destination_city_id']);
        });
    }
}
