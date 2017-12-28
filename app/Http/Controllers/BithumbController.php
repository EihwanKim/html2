<?php

namespace App\Http\Controllers;

use App\Library\Utils;
use http\Exception\InvalidArgumentException;
use Illuminate\Http\Request;
use ccxt;

class BithumbController extends Controller
{

    const CURRENCY = 'KRW';
    public $price_list = [];
    public $bithumb;

    public function index() {

        try  {
            $this->bithumb = new \ccxt\bithumb([
                'apiKey' => env('API_KEY_BITHUMB'),
                'secret' => env('API_SECRET_BITHUMB'),
            ]);

            $target_coins = Utils::getMasterCoins();
            $balances = $this->bithumb->fetch_balance();

            //価格抽出
            foreach ($target_coins as $key => $coin_type) {
                if (isset($balances ['info']['data']['xcoin_last_'.strtolower($coin_type)])) {
                    $this->price_list[$coin_type] = $balances ['info']['data']['xcoin_last_'.strtolower($coin_type)];
                }
            }

            //販売注文実施
            foreach ($balances["used"] as $coin_type => $amount) {
                if ($coin_type != self::CURRENCY && $amount > 0) {
                    $symbol = $this->get_symbol($coin_type);
                    $orders = $this->bithumb->fetch_orders($symbol);

                    foreach ($orders as $order) {
                        //既存の注文をキャンセル
                        $this->bithumb->cancel_order ($order['order_id'], null, [
                            'side' => $order['type'],
                            'currency' => $order['order_currency'],
                        ]);

                        $price = $this->price_list[$coin_type];
                        $price = $price * floatval(env('SELL_PRICE_RATE'));
                        $this->create_sell_order($coin_type, $amount, $price);

                    }
                }
            }

            //販売していないコインがある場合は販売注文実施
            foreach ($balances["free"] as $coin_type => $amount) {
                if ($coin_type != self::CURRENCY && $amount > $this->get_min_sell_amount($coin_type)) {
                    $price = $this->price_list[$coin_type];
                    $price = $price * floatval(env('SELL_PRICE_RATE'));
                    $this->create_sell_order($coin_type, $amount, $price);
                }
            }

            return view( 'empty');

        } catch (\ccxt\ExchangeError $e) {
            $desc = $this->bithumb->describe();
            $json_exception = str_replace($desc['id'], '',  $e->getMessage());
            $response = json_decode($json_exception);
            $this->notice_by_line(__CLASS__ . "\n\n" . "{$response->message}");
        }
    }

    private function create_sell_order ($coin_type, $amount, $price) {
        $symbol = $this->get_symbol($coin_type);
        $amount = $this->get_amount($coin_type, $amount);
        $order = $this->bithumb->create_limit_sell_order($symbol, floor($amount), floor($price));
        $text = \GuzzleHttp\json_encode($order);
        $this->notice_by_line($text);
    }

    private function get_symbol ($coin_type) {
        $master_coins = Utils::getMasterCoins();
        if (!in_array($coin_type, $master_coins))
            return '';
        return $coin_type . '/' . self::CURRENCY;
    }

    private function get_amount($coin_type, $amount) {
        $n = env('FLOOR_'.$coin_type);
        return floor( $amount * pow( 10 , $n ) ) / pow( 10 , $n ) ;
    }

    private function get_min_sell_amount($coin_type) {
        return env('MINIMUM_SELLABLE_AMOUNT_'.$coin_type);
    }

    private function notice_by_line($text) {
        $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(env('API_KEY_LINE'));
        $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => env('API_SECRET_LINE')]);
        $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($text);
        $res = $bot->pushMessage(env('MY_TOKEN_LINE'), $textMessageBuilder);
        dd($res);
    }
}
