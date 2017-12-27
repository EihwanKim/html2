<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class Trails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('trails', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('jp_price', 30, 15);
            $table->decimal('kr_price', 30, 15);
            $table->decimal('one_jpy_to_btc_to_krw', 30, 15);
            $table->decimal('one_jp_won_at_real', 30, 15);
            $table->decimal('one_btc_jpy_to_krw_at_real', 30, 15);
            $table->decimal('btc_fee_jp_to_kr', 30, 15);
            $table->decimal('real_btc_send_jp_to_kr', 30, 15);
            $table->decimal('estimated_krw', 30, 15);
            $table->decimal('estimated_jpy', 30, 15);
            $table->decimal('bank_fee_kr_to_jp', 30, 15);
            $table->decimal('recieve_jp_fee', 30, 15);
            $table->decimal('bank_fee_kr_to_jp_at_jpy', 30, 15);
            $table->decimal('final_jpy', 30, 15);
            $table->decimal('gap', 30, 15);
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