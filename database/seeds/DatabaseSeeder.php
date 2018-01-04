<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $this->call(UserSeeder::class);
//        $this->call(CoinMasterSeeder::class);
//        $this->call(Configs::class);

        //

        DB::table('coin_master')->truncate();
        DB::table('coin_master')->insert([
            'coin_type' => 'BTC',
            'enable' => true,
            'buy_market_type' => 'EXCHANGE',
            'sell_market_type' => 'EXCHANGE',
            'track_amount' => 0.1,
            'decimal_number' => 3,
            'buy_minimum_amount' => 0.001,
            'send_minimum_amount' => 0.1,
            'sell_minimum_amount' => 0.001,
            'buy_fee_rate' => 0.15,
            'sell_fee_rate' => 0.15,
            'send_fee' => 0.002,
        ]);
        DB::table('coin_master')->insert([
            'coin_type' => 'XRP',
            'enable' => true,
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
        ]);

        //
        DB::table('users')->truncate();
        DB::table('users')->insert([
            'name' => 'eihwan',
            'email' => 'cloz2me@gmail.com',
            'password' => bcrypt(env('USER_PASSWORD', 'secret')),
        ]);

        //
        DB::table('configs')->truncate();
        DB::table('configs')->insert([
            'name' => 'buy_rate',
            'value' => '10',
        ]);
        DB::table('configs')->insert([
            'name' => 'buy_price_rate',
            'value' => '0.5',
        ]);
        DB::table('configs')->insert([
            'name' => 'sell_price_rate',
            'value' => '2',
        ]);
    }
}
