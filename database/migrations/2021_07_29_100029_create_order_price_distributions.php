<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderPriceDistributions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_price_distributions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id');
            $table->integer('for_food')->default(0);
            $table->integer('for_travel')->default(0);
            $table->integer('for_member')->default(0);
            $table->integer('for_agent')->default(0);
            $table->datetime('deposited_at')->nullable();
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
        Schema::dropIfExists('order_price_distributions');
    }
}
