<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSouvenirRedeemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('souvenir_redeems', function (Blueprint $table) {
            $table->id();
            $table->string('quantity');
            $table->string('point_used');
            $table->string('status');
            $table->unsignedBigInteger('membership_id');
            $table->unsignedBigInteger('souvenir_id');
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
        Schema::dropIfExists('souvenir_redeems');
    }
}
