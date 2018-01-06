<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropTrailColumns extends Migration
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
            $table->dropColumn('buy_amount');
            $table->dropColumn('send_amount');
            $table->dropColumn('sell_amount');
            $table->dropColumn('return_krw');
            $table->dropColumn('return_jpy_no_fee');
            $table->dropColumn('input_jp');
            $table->dropColumn('return_jpy');
            $table->dropColumn('gap');
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
            $table->decimal('buy_amount');
            $table->decimal('send_amount');
            $table->decimal('sell_amount');
            $table->decimal('return_krw');
            $table->decimal('return_jpy_no_fee');
            $table->decimal('input_jp');
            $table->decimal('return_jpy');
            $table->decimal('gap');
        });
    }
}
