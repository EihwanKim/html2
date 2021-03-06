<?php

use Illuminate\Database\Seeder;

class CoinMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('coin_master')->truncate();
        DB::table('coin_master')->insert([
            'coin_type' => 'BTC',
            'enable' => true,
            'buy_flag' => true,
            'buy_market_type' => 'EXCHANGE',
            'sell_market_type' => 'EXCHANGE',
            'track_amount' => 0.1,
            'decimal_number' => 3,
            'buy_minimum_amount' => 0.01,
            'send_minimum_amount' => 0.1,
            'sell_minimum_amount' => 0.01,
            'buy_fee_rate' => 0.15,
            'sell_fee_rate' => 0.15,
            'send_fee' => 0.001,
            'color' => 'red',
        ]);
        DB::table('coin_master')->insert([
            'coin_type' => 'XRP',
            'enable' => true,
            'buy_flag' => false,
            'buy_market_type' => 'STORE',
            'sell_market_type' => 'EXCHANGE',
            'track_amount' => 1000,
            'decimal_number' => 0,
            'buy_minimum_amount' => 5,
            'send_minimum_amount' => 500,
            'sell_minimum_amount' => 10,
            'buy_fee_rate' => 0.15,
            'sell_fee_rate' => 0.15,
            'send_fee' => 0.15,
            'color' => 'blue',
        ]);
        DB::table('coin_master')->insert([
            'coin_type' => 'ETH',
            'enable' => true,
            'buy_flag' => false,
            'buy_market_type' => 'STORE',
            'sell_market_type' => 'EXCHANGE',
            'track_amount' => 1,
            'decimal_number' => 2,
            'buy_minimum_amount' => 0.01,
            'send_minimum_amount' => 0.1,
            'sell_minimum_amount' => 0.01,
            'buy_fee_rate' => 0.15,
            'sell_fee_rate' => 0.15,
            'send_fee' => 0.01,
            'color' => 'green',
        ]);
        DB::table('coin_master')->insert([
            'coin_type' => 'XMR',
            'enable' => true,
            'buy_flag' => false,
            'buy_market_type' => 'STORE',
            'sell_market_type' => 'EXCHANGE',
            'track_amount' => 10,
            'decimal_number' => 1,
            'buy_minimum_amount' => 0.1,
            'send_minimum_amount' => 0.1,
            'sell_minimum_amount' => 0.1,
            'buy_fee_rate' => 0.15,
            'sell_fee_rate' => 0.15,
            'send_fee' => 0.05,
            'color' => 'orange',
        ]);
        DB::table('coin_master')->insert([
            'coin_type' => 'LTC',
            'enable' => true,
            'buy_flag' => false,
            'buy_market_type' => 'STORE',
            'sell_market_type' => 'EXCHANGE',
            'track_amount' => 10,
            'decimal_number' => 1,
            'buy_minimum_amount' => 1,
            'send_minimum_amount' => 1,
            'sell_minimum_amount' => 1,
            'buy_fee_rate' => 0.15,
            'sell_fee_rate' => 0.15,
            'send_fee' => 0.001,
            'color' => 'black',
        ]);
        DB::table('coin_master')->insert([
            'coin_type' => 'DASH',
            'enable' => true,
            'buy_flag' => false,
            'buy_market_type' => 'STORE',
            'sell_market_type' => 'EXCHANGE',
            'track_amount' => 1,
            'decimal_number' => 1,
            'buy_minimum_amount' => 1,
            'send_minimum_amount' => 1,
            'sell_minimum_amount' => 1,
            'buy_fee_rate' => 0.15,
            'sell_fee_rate' => 0.15,
            'send_fee' => 0.003,
            'color' => 'purple',
        ]);
        DB::table('coin_master')->insert([
            'coin_type' => 'BCH',
            'enable' => true,
            'buy_flag' => false,
            'buy_market_type' => 'STORE',
            'sell_market_type' => 'EXCHANGE',
            'track_amount' => 0.5,
            'decimal_number' => 1,
            'buy_minimum_amount' => 0.5,
            'send_minimum_amount' => 0.5,
            'sell_minimum_amount' => 0.5,
            'buy_fee_rate' => 0.15,
            'sell_fee_rate' => 0.15,
            'send_fee' => 0.001,
            'color' => 'pink',
        ]);
        DB::table('coin_master')->insert([
            'coin_type' => 'ZEC',
            'enable' => true,
            'buy_flag' => false,
            'buy_market_type' => 'STORE',
            'sell_market_type' => 'EXCHANGE',
            'track_amount' => 1,
            'decimal_number' => 0,
            'buy_minimum_amount' => 1,
            'send_minimum_amount' => 1,
            'sell_minimum_amount' => 1,
            'buy_fee_rate' => 0.15,
            'sell_fee_rate' => 0.15,
            'send_fee' => 0.001,
            'color' => 'yellow',
        ]);
        DB::table('coin_master')->insert([
            'coin_type' => 'ETC',
            'enable' => true,
            'buy_flag' => false,
            'buy_market_type' => 'STORE',
            'sell_market_type' => 'EXCHANGE',
            'track_amount' => 20,
            'decimal_number' => 0,
            'buy_minimum_amount' => 20,
            'send_minimum_amount' => 20,
            'sell_minimum_amount' => 20,
            'buy_fee_rate' => 0.15,
            'sell_fee_rate' => 0.15,
            'send_fee' => 0.01,
            'color' => 'grey',
        ]);


    }
}
