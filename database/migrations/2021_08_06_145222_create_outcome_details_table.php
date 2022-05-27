<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutcomeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outcome_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('outcome_id');
            $table->string('name');
            $table->integer('amount');
            $table->timestamps();

            $table->foreign('outcome_id')
            ->references('id')
            ->on('outcomes')
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
        Schema::dropIfExists('outcome_details');
    }
}
