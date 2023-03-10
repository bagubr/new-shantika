<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoutes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->unsignedBigInteger('fleet_id');
            $table->time('departure_at')->nullable();
            $table->time('arrived_at')->nullable();
            $table->integer('price');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('fleet_id')
            ->references('id')
            ->on('fleets')
            ->onUpdate('CASCADE')
            ->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('routes');
    }
}
