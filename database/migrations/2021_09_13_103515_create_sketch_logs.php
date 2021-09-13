<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSketchLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sketch_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('admin_id');
            $table->foreign('admin_id')->references('id')->on('admins');

            $table->bigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders');
            
            $table->datetime('from_date');
            $table->datetime('to_date');
            
            $table->integer('from_fleet_route_id');
            $table->integer('to_fleet_route_id');
            $table->foreign('from_fleet_route_id')->references('id')->on('fleet_routes');
            $table->foreign('to_fleet_route_id')->references('id')->on('fleet_routes');

            $table->integer('from_layout_chair_id');
            $table->integer('to_layout_chair_id');
            $table->foreign('from_layout_chair_id')->references('id')->on('layout_chairs');
            $table->foreign('to_layout_chair_id')->references('id')->on('layout_chairs');
            
            $table->integer('from_time_classification_id');
            $table->integer('to_time_classification_id');
            $table->foreign('from_time_classification_id')->references('id')->on('time_classifications');
            $table->foreign('to_time_classification_id')->references('id')->on('time_classifications');

            
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
        Schema::dropIfExists('sketch_logs');
    }
}
