<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBuyFlagToCoinMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('coin_master', function (Blueprint $table) {
            $table->boolean('buy_flag')->after('enable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('coin_master', function (Blueprint $table) {
            $table->dropColumn('buy_flag');
        });
    }
}
