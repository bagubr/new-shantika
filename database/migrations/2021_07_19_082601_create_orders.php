<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code_order');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('route_id');
            $table->string('status');
            $table->integer('price');
            $table->datetime('expired_at');
            $table->datetime('reserve_at');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onUpdate('CASCADE')
            ->onDelete('RESTRICT');

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
        Schema::dropIfExists('orders');
    }
}
