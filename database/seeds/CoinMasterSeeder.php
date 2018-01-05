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
            'buy_market_type' => 'EXCHANGE',
            'sell_market_type' => 'EXCHANGE',
            'track_amount' => 0.1,
            'decimal_number' => 3,
            'buy_minimum_amount' => 0.01,
            'send_minimum_amount' => 0.1,
            'sell_minimum_amount' => 0.01,
            'buy_fee_rate' => 0.15,
            'sell_fee_rate' => 0.15,
            'send_fee' => 0.002,
            'color' => 'red',
        ]);
        DB::table('coin_master')->insert([
            'coin_type' => 'XRP',
            'enable' => false,
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
            'enable' => false,
            'buy_market_type' => 'STORE',
            'sell_market_type' => 'EXCHANGE',
            'track_amount' => 0.1,
            'decimal_number' => 2,
            'buy_minimum_amount' => 0.01,
            'send_minimum_amount' => 0.1,
            'sell_minimum_amount' => 0.01,
            'buy_fee_rate' => 0.15,
            'sell_fee_rate' => 0.15,
            'send_fee' => 0.15,
            'color' => 'green',
        ]);

    }
}
