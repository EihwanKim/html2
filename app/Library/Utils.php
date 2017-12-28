<?php
// app/Library/BaseClass.php
namespace App\Library;

class Utils
{


    public static function getMasterCoins() {
        return explode(',', env('TARGET_COINS'));
    }

    public static function send_line($text) {

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
}