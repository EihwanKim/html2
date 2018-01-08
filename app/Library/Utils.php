<?php
// app/Library/BaseClass.php
namespace App\Library;

use App\CoinMaster;
use Carbon\Carbon;

class Utils
{

    public static function get_simulation_result ($coin_type, $jp_price, $kr_price, $cash_rate, $buy_amount = null) {

        $coin = CoinMaster::whereCoinType($coin_type)->first();

        if (!$buy_amount) {

        } else {

        }

        if ($coin->buy_market_type == 'STORE') {
            $jp_price = $jp_price + ($jp_price * 0.03);   //TODO できれば実際のスプレッドを取得したい。
        } else {
            $buy_amount = $buy_amount - ($buy_amount * ($coin->buy_fee_rate / 100));
        }
        $send_amount = $buy_amount - $coin->send_fee;
        $sell_amount = $send_amount;

        if ($coin->sell_market_type == 'STORE') {
            $kr_price = $kr_price - ($kr_price * 0.03);   //TODO できれば実際のスプレッドを取得したい。
        } else {
            $sell_amount = $sell_amount - ($sell_amount * ($coin->sell_fee_rate / 100));
        }
        $return_krw = $sell_amount * $kr_price;
        $return_jpy_no_fee = $return_krw / $cash_rate;
        $input_jp = $jp_price * $buy_amount;
        $return_jpy = $return_jpy_no_fee - (8000 / $cash_rate) - 2480;
        $gap = $return_jpy - $input_jp;
        $rate = $gap / $return_jpy * 100;

        $data['coin_type'] = $coin_type;       //STR
        $data['jp_price'] = $jp_price;          //float
        $data['kr_price'] = $kr_price;          //float
        $data['cash_rate'] = $cash_rate;        //float
        $data['buy_amount'] = $buy_amount;      //float
        $data['send_amount'] = $send_amount;    //float
        $data['sell_amount'] = $sell_amount;    //float
        $data['return_krw'] = $return_krw;      //float
        $data['return_jpy_no_fee'] = $return_jpy_no_fee;    //float
        $data['input_jp'] = $input_jp;          //float
        $data['return_jpy'] = $return_jpy;      //float
        $data['gap'] = $gap;                    //float
        $data['rate'] = $rate;                  //float
        return $data;
    }


    public static function send_line($text, $e = null) {

        $date = Carbon::now();


        if ($e != null) {
            $text = $text . "\n{$e->getFile()} ({$e->getLine()}) \n{$e->getMessage()}" ;
        }
        $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(env('API_KEY_LINE'));
        $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => env('API_SECRET_LINE')]);
        $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($text);

        $no_notify_from = Configs::whereName('no_notify_from')->first()->value;
        $no_notify_to = Configs::whereName('no_notify_to')->first()->value;

        if (!($date->hour > $no_notify_from || $date->hour < $no_notify_to)) {
            $response = $bot->pushMessage(env('MY_TOKEN_LINE'), $textMessageBuilder);
        }

        unset($httpClient, $bot, $textMessageBuilder);
        return $response;
    }

    public static function floor ($val, $precision) {
        $mult = pow(10, $precision);
        return floor($val * $mult) / $mult;
    }

    public static function get_amount($coin_type, $amount) {
        $n = env('FLOOR_'.$coin_type);
        return Utils::floor($amount, $n);
    }

    public static function get_min_sell_amount($coin_type) {
        return env('MINIMUM_SELLABLE_AMOUNT_'.$coin_type);
    }
}