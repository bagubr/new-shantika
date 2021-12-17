<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimeClassificationIdToFleetDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fleet_details', function (Blueprint $table) {
            $table->bigInteger('time_classification_id')->nullable();
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
        Schema::table('fleet_details', function (Blueprint $table) {
            $table->dropForeign(['time_classification_id']);
            $table->dropColumn('time_classification_id');
        });
    }
}
