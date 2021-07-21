<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('route_id');
            $table->unsignedBigInteger('layout_chair_id');
            $table->unsignedBigInteger('user_id');
            $table->datetime('booking_at');
            $table->datetime('expired_at');
            $table->timestamps();

            $table->foreign('route_id')
            ->references('id')
            ->on('routes')
            ->onUpdate('CASCADE')
            ->onDelete('RESTRICT');

            $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onUpdate('CASCADE')
            ->onDelete('RESTRICT');

            $table->foreign('layout_chair_id')
            ->references('id')
            ->on('layout_chair')
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
        Schema::dropIfExists('bookings');
    }
}
