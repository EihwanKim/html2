<?php
// app/Library/BaseClass.php
namespace App\Library;

use Carbon\Carbon;

class Utils
{


    public static function getMasterCoins() {
        return explode(',', env('TARGET_COINS'));
    }

    public static function send_line($text) {

        $date = Carbon::now();
        //TODO 通知しない時間を避けて通知

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