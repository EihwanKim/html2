<?php
// app/Library/BaseClass.php
namespace App\Library;

use Carbon\Carbon;
use Goutte\Client as CrawlerClient;

class Utils
{



    public static function getMasterCoins() {
        return explode(',', env('TARGET_COINS'));
    }

    public static function getExchangeSimulation ($coin_type) {

        $coin_type = strtoupper($coin_type);

        $send_btc_amount = env('TRACKER_AMOUNT_' . $coin_type);

        $res_kr_json = exec(" curl https://api.bithumb.com/public/ticker/{$coin_type}");
        $res_kr = json_decode($res_kr_json);
        $one_coin_price_kr = $res_kr->data->closing_price;

        $res_jp_json = exec(" curl https://coincheck.com/api/rate/{$coin_type}_JPY");
        $res_jp = json_decode($res_jp_json);
        $one_coin_price_jp = $res_jp->rate;
//        $one_coin_price_jp = $one_coin_price_jp * 1.05;

        $crawlerClient = new CrawlerClient();
        $crawler = $crawlerClient->request('GET', env('REAL_CURRENCY_CONVERTER_URL'));
        $one_jp_won_at_real = $crawler->filter('.uccResultAmount')->text();
        $one_jp_won_at_real = floatval($one_jp_won_at_real);

        unset($crawlerClient, $crawler);

        $one_jpy_to_btc_to_krw = $one_coin_price_kr / $one_coin_price_jp ;
        $one_btc_jpy_to_krw_at_real = $one_coin_price_jp * $one_jp_won_at_real;
        $send_btc_amount = $send_btc_amount - ($send_btc_amount * (0.15 /100)); //BTC
        $btc_fee_jp_to_kr = floatval(env("COIN_SEND_FEE_COINCHECK_{$coin_type}"));
        $real_btc_send_jp_to_kr = $send_btc_amount - $btc_fee_jp_to_kr;
        $real_btc_send_jp_to_kr  = $real_btc_send_jp_to_kr  - ($real_btc_send_jp_to_kr  * (0.15 /100)); //BTC
        $estimated_krw = $real_btc_send_jp_to_kr * $one_coin_price_kr;
        $estimated_jpy = $estimated_krw / $one_jp_won_at_real;
        $bank_fee_kr_to_jp = 8000;
        $recieve_jp_fee = 4000;
        $bank_fee_kr_to_jp_at_jpy = ($bank_fee_kr_to_jp / $one_jp_won_at_real) + $recieve_jp_fee; //1
        $final_jpy = ($estimated_krw / $one_jp_won_at_real) - $bank_fee_kr_to_jp_at_jpy;
        $send_btc_price = $one_coin_price_jp * $send_btc_amount;
        $gap = $final_jpy - ($send_btc_price);
        $rate = $gap / $final_jpy *100;
        $data['coin_type'] = $coin_type;
        $data['jp_price'] = $one_coin_price_jp;
        $data['kr_price'] = $one_coin_price_kr;
        $data['one_jpy_to_btc_to_krw'] = floatval($one_jpy_to_btc_to_krw);
        $data['one_jp_won_at_real'] = $one_jp_won_at_real;
        $data['one_btc_jpy_to_krw_at_real'] = $one_btc_jpy_to_krw_at_real;
        $data['send_btc_amount'] = $send_btc_amount;
        $data['send_btc_price'] = $send_btc_price;
        $data['btc_fee_jp_to_kr'] = $btc_fee_jp_to_kr;
        $data['real_btc_send_jp_to_kr'] = $real_btc_send_jp_to_kr;
        $data['estimated_krw'] = $estimated_krw;
        $data['estimated_jpy'] = $estimated_jpy;
        $data['bank_fee_kr_to_jp'] = $bank_fee_kr_to_jp;
        $data['recieve_jp_fee'] = $recieve_jp_fee;
        $data['bank_fee_kr_to_jp_at_jpy'] = $bank_fee_kr_to_jp_at_jpy;
        $data['final_jpy'] = $final_jpy;
        $data['gap'] = $gap;
        $data['rate'] = $rate;

        return $data;
    }

    public static function getMarketSimulation ($coin_type) {

        $coin_type = strtoupper($coin_type);

        $send_btc_amount = env('TRACKER_AMOUNT_' . $coin_type);

        $res_kr_json = exec(" curl https://api.bithumb.com/public/ticker/{$coin_type}");
        $res_kr = json_decode($res_kr_json);
        $one_coin_price_kr = $res_kr->data->closing_price;

        $res_jp_json = exec(" curl https://coincheck.com/api/rate/{$coin_type}_JPY");
        $res_jp = json_decode($res_jp_json);
        $one_coin_price_jp = $res_jp->rate;
        $one_coin_price_jp = $one_coin_price_jp * 1.05;

        $crawlerClient = new CrawlerClient();
        $crawler = $crawlerClient->request('GET', env('REAL_CURRENCY_CONVERTER_URL'));
        $one_jp_won_at_real = $crawler->filter('.uccResultAmount')->text();
        $one_jp_won_at_real = floatval($one_jp_won_at_real);

        unset($crawlerClient, $crawler);

        $one_jpy_to_btc_to_krw = $one_coin_price_kr / $one_coin_price_jp ;
        $one_btc_jpy_to_krw_at_real = $one_coin_price_jp * $one_jp_won_at_real;
//        $send_btc_amount = $send_btc_amount - ($send_btc_amount * (0.15 /100)); //BTC
        $btc_fee_jp_to_kr = floatval(env("COIN_SEND_FEE_COINCHECK_{$coin_type}"));
        $real_btc_send_jp_to_kr = $send_btc_amount - $btc_fee_jp_to_kr;
        $real_btc_send_jp_to_kr  = $real_btc_send_jp_to_kr  - ($real_btc_send_jp_to_kr  * (0.15 /100)); //BTC
        $estimated_krw = $real_btc_send_jp_to_kr * $one_coin_price_kr;
        $estimated_jpy = $estimated_krw / $one_jp_won_at_real;
        $bank_fee_kr_to_jp = 8000;
        $recieve_jp_fee = 4000;
        $bank_fee_kr_to_jp_at_jpy = ($bank_fee_kr_to_jp / $one_jp_won_at_real) + $recieve_jp_fee; //1
        $final_jpy = ($estimated_krw / $one_jp_won_at_real) - $bank_fee_kr_to_jp_at_jpy;
        $send_btc_price = $one_coin_price_jp * $send_btc_amount;
        $gap = $final_jpy - ($send_btc_price);
        $rate = $gap / $final_jpy *100;
        $data['coin_type'] = $coin_type;
        $data['jp_price'] = $one_coin_price_jp;
        $data['kr_price'] = $one_coin_price_kr;
        $data['one_jpy_to_btc_to_krw'] = floatval($one_jpy_to_btc_to_krw);
        $data['one_jp_won_at_real'] = $one_jp_won_at_real;
        $data['one_btc_jpy_to_krw_at_real'] = $one_btc_jpy_to_krw_at_real;
        $data['send_btc_amount'] = $send_btc_amount;
        $data['send_btc_price'] = $send_btc_price;
        $data['btc_fee_jp_to_kr'] = $btc_fee_jp_to_kr;
        $data['real_btc_send_jp_to_kr'] = $real_btc_send_jp_to_kr;
        $data['estimated_krw'] = $estimated_krw;
        $data['estimated_jpy'] = $estimated_jpy;
        $data['bank_fee_kr_to_jp'] = $bank_fee_kr_to_jp;
        $data['recieve_jp_fee'] = $recieve_jp_fee;
        $data['bank_fee_kr_to_jp_at_jpy'] = $bank_fee_kr_to_jp_at_jpy;
        $data['final_jpy'] = $final_jpy;
        $data['gap'] = $gap;
        $data['rate'] = $rate;

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
        $response = $bot->pushMessage(env('MY_TOKEN_LINE'), $textMessageBuilder);
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