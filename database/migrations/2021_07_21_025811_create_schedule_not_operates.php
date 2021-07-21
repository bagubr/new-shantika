<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScheduleNotOperates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule_not_operates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBiginteger('route_id');
            $table->string('note');
            $table->date('schedule_at');
            $table->timestamps();

            $table->foreign('route_id')
            ->references('id')
            ->on('routes')
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
        Schema::dropIfExists('order_schedules');
    }
}
