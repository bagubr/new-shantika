<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('description')->default('');
            $table->string('image')->default('');
            $table->tinyInteger('percentage_discount');
            $table->integer('maximum_discount');
            $table->date('start_at')->nullable();
            $table->date('end_at')->nullable();
            $table->bigInteger('quota')->nullable();
            $table->unsignedBigInteger('route_id')->nullable();
            $table->boolean('is_public')->default(false);
            $table->boolean('is_quotaless')->default(false);
            $table->unsignedBigInteger('user_id')->nullable();
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
        Schema::dropIfExists('promos');
    }
}
