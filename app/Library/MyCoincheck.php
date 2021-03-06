<?php
/**
 * Created by IntelliJ IDEA.
 * User: eihwan
 * Date: 2018/01/03
 * Time: 20:21
 */

namespace App\Library;

use ccxt\coincheck;

class MyCoincheck extends coincheck
{

    public function __construct(array $options = array())
    {
        if (!$options) {
            parent::__construct([
                'apiKey' => env('API_KEY_COINCHECK'),
                'secret' => env('API_SECRET_COINCHECK'),
            ]);
        } else {
            parent::__construct($options);
        }
    }

    public function get_rate ($symbol, $params = array ()) {

        $ticker = $this->publicGetRatePair (array_merge (array (
            'pair' => $symbol,
        ), $params));

//        $timestamp = $ticker['timestamp'] * 1000;
        return array (
            'symbol' => $symbol,
            'close' => $ticker['rate'],
            'info' => $ticker,
        );
    }

    public function get_orders() {
        return $this->privateGetExchangeOrdersOpens();
    }

    public function send_coin ($address, $amount, $params  = array ()) {
        $order = [
            'address' => $address,
            'amount' => $amount ,
        ];
        return $this->privatePostSendMoney(array_merge($order, $params));
    }

    public function fetch_balance($params = array())
    {
        sleep(1);
        return parent::fetch_balance($params);
    }
}