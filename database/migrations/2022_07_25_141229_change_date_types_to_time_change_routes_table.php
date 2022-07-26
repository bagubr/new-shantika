<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDateTypesToTimeChangeRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('time_change_routes', function (Blueprint $table) {
            $table->dropColumn('date');
        });
        Schema::table('time_change_routes', function (Blueprint $table) {
            $table->date('date')->default(date('Y-m-d'));
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('time_change_routes', function (Blueprint $table) {
            $table->dropColumn('date');
        });
        Schema::table('time_change_routes', function (Blueprint $table) {
            $table->string('date')->nullable();
        });
    }
}
