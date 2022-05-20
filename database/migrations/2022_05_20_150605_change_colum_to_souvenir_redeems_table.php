<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumToSouvenirRedeemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('souvenir_redeems', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('souvenir_redeems', function (Blueprint $table) {
            $table->enum('status', ['DELIVERED', 'ON_PROCESS', 'WAITING', 'DECLINE'])->default('DELIVERED');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('souvenir_redeems', function (Blueprint $table) {
            //
        });
    }
}
