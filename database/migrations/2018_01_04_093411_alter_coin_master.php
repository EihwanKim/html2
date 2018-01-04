<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCoinMaster extends Migration
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
            $table->decimal('send_minimum_amount', 20, 10)->after('buy_minimum_amount');
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
            $table->dropColumn('send_minimum_amount');
        });
    }
}
