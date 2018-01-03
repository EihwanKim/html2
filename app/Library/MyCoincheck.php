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

    public function get_rate ($symbol, $params = array ()) {
//        $this->load_markets();
//        $market = $this->market ($symbol);
        $ticker = $this->publicGetRatePair (array_merge (array (
            'pair' => $symbol,
        ), $params));
//        $timestamp = $ticker['timestamp'] * 1000;
        return array (
            'symbol' => $symbol,
//            'timestamp' => $timestamp,
//            'datetime' => $this->iso8601 ($timestamp),
//            'high' => floatval ($ticker['high']),
//            'low' => floatval ($ticker['low']),
//            'bid' => floatval ($ticker['bid']),
//            'ask' => floatval ($ticker['ask']),
//            'vwap' => null,
//            'open' => null,
            'close' => $ticker['rate'],
//            'first' => null,
//            'last' => floatval ($ticker['last']),
//            'change' => null,
//            'percentage' => null,
//            'average' => null,
//            'baseVolume' => floatval ($ticker['volume']),
//            'quoteVolume' => null,
//            'info' => $ticker,
        );
    }
}