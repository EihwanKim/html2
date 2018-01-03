<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoinmaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('coin_master', function (Blueprint $table) {
            $table->increments('id');
            $table->string('coin_type');    //BTC,XRP...
            $table->boolean('enable');
            $table->integer('decimal_number');      //桁数
            $table->decimal('track_amount', 20, 8);
            $table->string('buy_market_type');  //exchange or store
            $table->string('sell_market_type');  //exchange or store
            $table->decimal('buy_minimum_amount', 20, 8);
            $table->decimal('sell_minimum_amount', 20, 8);
            $table->decimal('buy_fee_rate', 20, 8);
            $table->decimal('sell_fee_rate', 20, 8);
            $table->decimal('send_fee', 20, 8);
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
        Schema::dropIfExists('coin_master');
    }
}
