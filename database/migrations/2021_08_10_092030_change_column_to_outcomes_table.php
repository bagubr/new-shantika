<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnToOutcomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('outcomes', function (Blueprint $table) {
            $table->unsignedBigInteger('route_id')->nullable()->change();
            $table->unsignedBigInteger('outcome_type_id')->nullable();
            
            $table->foreign('outcome_type_id')
            ->references('id')
            ->on('outcome_types')
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
        Schema::table('outcomes', function (Blueprint $table) {
            //
        });
    }
}
