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
            'jp_name' => '購入基準レート(このレートを超えたら購入と判定)',
            'value' => '15',
        ]);
        DB::table('configs')->insert([
            'name' => 'buy_price_rate',
            'jp_name' => '購入時調整レート(調整しない:1)',
            'value' => '0.998',
        ]);
        DB::table('configs')->insert([
            'name' => 'sell_price_rate',
            'jp_name' => '売却時調整レート(調整しない:1)',
            'value' => '1',
        ]);
        DB::table('configs')->insert([
            'name' => 'buy_when_jpy_is_over',
            'jp_name' => '円残高がこの金額を超えたら購入実施',
            'value' => '100000',
        ]);

    }
}
