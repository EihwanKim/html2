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
            'jp_name' => '購入基準レート',
            'value' => '30',
            'validation' => 'required|numeric|min:20|max:99',
        ]);
        DB::table('configs')->insert([
            'name' => 'buy_price_rate',
            'jp_name' => '購入時調整レート(0.9~1.1)',
            'value' => '0.998',
            'validation' => 'required|numeric|min:0.9|max:1.1',
        ]);
        DB::table('configs')->insert([
            'name' => 'sell_price_rate',
            'jp_name' => '売却時調整レート(0.9~1.1)',
            'value' => '1',
            'validation' => 'required|numeric|min:0.9|max:1.1',
        ]);
        DB::table('configs')->insert([
            'name' => 'buy_when_jpy_is_over',
            'jp_name' => '購入基準円残高(Y)',
            'value' => '100000',
            'validation' => 'required|numeric|min:100000',
        ]);
        DB::table('configs')->insert([
            'name' => 'withdraw_amount',
            'jp_name' => 'Bithumb出金基準金額(W)',
            'value' => '3000000',
            'validation' => 'required|numeric|min:3000000',
        ]);
        DB::table('configs')->insert([
            'name' => 'no_notify_from',
            'jp_name' => '通知しない(from)',
            'value' => '23',
            'validation' => '',
        ]);
        DB::table('configs')->insert([
            'name' => 'no_notify_to',
            'jp_name' => '通知しない(to)',
            'value' => '07',
            'validation' => '',
        ]);

    }
}
