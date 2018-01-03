<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RecreateTrails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::dropIfExists('trails');
        Schema::create('trails', function (Blueprint $table) {
            $table->increments('id');
            $table->string('coin_type');
            $table->decimal('jp_price', 20, 10);
            $table->decimal('kr_price', 20, 10);
            $table->decimal('cash_rate', 20, 10);
            $table->decimal('buy_amount', 20, 10);
            $table->decimal('send_amount', 20, 10);
            $table->decimal('sell_amount', 20, 10);
            $table->decimal('return_krw', 20, 10);
            $table->decimal('return_jpy_no_fee', 20, 10);
            $table->decimal('input_jp', 20, 10);
            $table->decimal('return_jpy', 20, 10);
            $table->decimal('gap', 20, 10);
            $table->decimal('rate', 20, 10);
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
        //
        Schema::drop('trails');
    }
}
