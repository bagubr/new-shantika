<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimeClassificationIdToAgencyDepartureTimes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agency_departure_times', function (Blueprint $table) {
            $table->integer('time_classification_id')->nullable();
            $table->foreign('time_classification_id')->references('id')->on('time_classifications');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agency_departure_times', function (Blueprint $table) {
            $table->dropForeign(['time_classification_id']);
            $table->dropColumn('time_classification_id');
        });
    }
}
