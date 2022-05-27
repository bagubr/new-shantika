<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_type_id');
            $table->unsignedBigInteger('order_id');
            $table->text('secret_key');
            $table->string('status');
            $table->datetime('expired_at');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('payment_type_id')
            ->references('id')
            ->on('payment_types')
            ->onUpdate('CASCADE')
            ->onDelete('RESTRICT');

            $table->foreign('order_id')
            ->references('id')
            ->on('orders')
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
        Schema::dropIfExists('payments');
    }
}
