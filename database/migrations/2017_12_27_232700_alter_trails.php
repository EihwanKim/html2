<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTrails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('trails', function (Blueprint $table) {
            $table->decimal('send_btc_amount', 20, 10)->after('one_btc_jpy_to_krw_at_real');
            $table->decimal('rate', 10, 5)->after('gap');
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
        Schema::table('trails', function (Blueprint $table) {
            $table->dropColumn('send_btc_amount');
            $table->dropColumn('rate');
        });
    }
}
