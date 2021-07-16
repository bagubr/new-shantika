<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFleets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fleets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('fleet_class_id');
            $table->unsignedBigInteger('layout_id');
            $table->string('image')->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            $table->foreign('fleet_class_id')
            ->references('id')
            ->on('fleet_classes')
            ->onUpdate('CASCADE')
            ->onDelete('RESTRICT');

            $table->foreign('layout_id')
            ->references('id')
            ->on('layouts')
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
        Schema::dropIfExists('fleets');
    }
}
