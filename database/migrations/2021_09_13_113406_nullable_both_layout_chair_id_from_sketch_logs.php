<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NullableBothLayoutChairIdFromSketchLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sketch_logs', function (Blueprint $table) {
            $table->integer('from_layout_chair_id')->nullable()->change();
            $table->integer('to_layout_chair_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sketch_logs', function (Blueprint $table) {
            $table->integer('from_layout_chair_id')->change();
            $table->integer('to_layout_chair_id')->change();
        });
    }
}
