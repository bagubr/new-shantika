<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('layout_chair_id');
            $table->string('code_ticket');
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('is_feed');
            $table->string('is_travel');
            $table->string('is_member');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('order_id')
            ->references('id')
            ->on('orders')
            ->onUpdate('CASCADE')
            ->onDelete('RESTRICT');

            $table->foreign('layout_chair_id')
            ->references('id')
            ->on('layout_chairs')
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
        Schema::dropIfExists('order_details');
    }
}
