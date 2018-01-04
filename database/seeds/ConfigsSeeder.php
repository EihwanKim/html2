<?php

use Illuminate\Database\Seeder;

class ConfigsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('configs')->truncate();
        DB::table('configs')->insert([
            'name' => 'buy_rate',
            'value' => '10',
        ]);
        DB::table('configs')->insert([
            'name' => 'buy_price_rate',
            'value' => '0.7',
        ]);
        DB::table('configs')->insert([
            'name' => 'sell_price_rate',
            'value' => '1.3',
        ]);

    }
}
