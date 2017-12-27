<?php

namespace App\Http\Controllers;

use http\Exception\InvalidArgumentException;
use Illuminate\Http\Request;
use ccxt\bithumb;

class BithumbController extends Controller
{

    const CURRENCY = 'KRW';
    public $price_list = [];
    public $bithumb;

    public function index() {

        $this->bithumb = new bithumb([
            'apiKey' => env('API_KEY_BITHUMB'),
            'secret' => env('API_SECRET_BITHUMB'),
        ]);

        $balances = $this->bithumb->fetch_balance();

        //価格抽出
        foreach ($balances as $coin_type => $value) {
            if (isset($balances ['info']['data']['xcoin_last_'.strtolower($coin_type)])) {
                $this->price_list[$coin_type] = $balances ['info']['data']['xcoin_last_'.strtolower($coin_type)];
            }
        }

        dd($balances);

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
                    //TODO 注文キャンセル後すぐに新しい注文を出すと Please try again になる。
                    $this->create_sell_order($coin_type, $amount);

                }
            }
        }

        //販売していないコインがある場合は販売注文実施
        foreach ($balances["free"] as $coin_type => $amount) {
            if ($coin_type != self::CURRENCY && $amount > $this->get_min_sell_amount($coin_type)) {
                $this->create_sell_order($coin_type, $amount);
            }
        }

        $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(env('API_KEY_LINE'));
        $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => env('API_SECRET_LINE')]);
        $messageData = [
            'type' => 'template',
            'altText' => '確認ダイアログ',
            'template' => [
                'type' => 'confirm',
                'text' => '元気ですかー？',
                'actions' => [
                    [
                        'type' => 'message',
                        'label' => '元気です',
                        'text' => '元気です'
                    ],
                    [
                        'type' => 'message',
                        'label' => 'まあまあです',
                        'text' => 'まあまあです'
                    ],
                ]
            ]
        ];

        $bot->pushMessage('cloz2me@gmail.com', $messageData);

        return view('bithumb', []);
    }

    private function create_sell_order ($coin_type, $amount) {
        $symbol = $this->get_symbol($coin_type);
        $price = $this->price_list[$coin_type];
        $price = $price + ($price * 0.1);
        $amount = $this->get_amount($coin_type, $amount);
        $this->bithumb->create_limit_sell_order($symbol, floor($amount), floor($price));

//                $bithumb->create_market_sell_order($symbol, $amount);
    }

    private function get_symbol ($coin_type) {
        $master_coins = explode(',', env('TARGET_COINS'));
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
}
