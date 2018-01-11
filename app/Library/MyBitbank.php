<?php
/**
 * Created by IntelliJ IDEA.
 * User: u-kimu
 * Date: 2018/01/04
 * Time: 12:32
 */

namespace App\Library;

use ccxt\Exchange;

class MyBitbank extends Exchange
{

    public function __construct(array $options = array())
    {

        if (!$options) {
            parent::__construct([
                'apiKey' => env('API_KEY_BITBANK'),
                'secret' => env('API_SECRET_BITBANK'),
            ]);
        } else {
            parent::__construct($options);
        }
    }

    public function describe () {
        return array_replace_recursive (parent::describe (), array (
            'id' => 'bitbank',
            'name' => 'bitbank',
            'countries' => 'JP',
            'version' => 'v1',
            'rateLimit' => 500,
            'hasCORS' => false,
            'hasWithdraw' => true,
            'urls' => array (
                'logo' => 'https://bitbank.cc/common-assets/icons/apple-icon-180x180.png',
                'api' => array (
                    'public' => 'https://public.bitbank.cc',
                    'private' => 'https://api.bitbank.cc/v1'
                ),
                'www' => 'https://bitbank.cc',
                'doc' => 'https://docs.bitbank.cc',
            ),
            'api' => array (
                'public' => array (
                    'get' => array (
                        '{pair}/ticker',        // last btc_jpy, xrp_jpy, ltc_btc, eth_btc, mona_jpy, mona_btc, bcc_jpy, bcc_btc
                        '{pair}/depth',
                        '{pair}/transactions',
                        '{pair}/transactions/{YYYYMMDD}',
                        '{pair}/candlestick/{candle-type}/{YYYY}',
                    ),
                ),
                'private' => array (
                    'get' => array (
                        'user/assets',
                        'user/spot/order',      //pair  //order_id
                        'user/spot/active_orders',
                        'user/spot/trade_history',
                        'user/withdrawal_account',
                    ),
                    'post' => array (
                        'user/spot/order',      //requestBody
                        'user/spot/cancel_order',
                        'user/spot/cancel_orders',
                        'user/spot/orders_info',
                        'user/request_withdrawal',
                    ),
                ),
            ),
            'fees' => array (
                'trading' => array (
                    'maker' => 0.15 / 100,
                    'taker' => 0.15 / 100,
                ),
            ),
        ));
    }

    public function sign ($path, $api = 'public', $method = 'GET', $params = array (), $headers = null, $body = null) {
        $endpoint = '/' . $this->implode_params($path, $params);
        $url = $this->urls['api'][$api] . $endpoint;
        $query = $this->omit ($params, $this->extract_params($path));

        $this->check_required_credentials();
        $nonce = (string) $this->nonce ();

        //- GETの場合: 「ACCESS-NONCE、リクエストのパス、クエリパラメータ」 を連結させたもの
        //- POSTの場合: 「ACCESS-NONCE、リクエストボディのJson文字列」 を連結させたもの
        if ($method === 'GET') {
            $url .= '?' . $this->urlencode ($query);
            $path = '/'.$this->version.'/'.$path;
            $message = $nonce . $path . $this->urlencode($query);
        } else {
            $message = $nonce . $this->json($body);
            $body = $params;
        }

        $signature = $this->hmac ($message, $this->secret);
        $headers = array (
            'ACCESS-KEY' => $this->apiKey,
            'ACCESS-NONCE' => $nonce,
            'ACCESS-SIGNATURE' => $signature,
        );

        return array ( 'url' => $url, 'method' => $method, 'body' => $body, 'headers' => $headers );
    }

    public function nonce() {
        return microtime(true) * 10000;
    }

    public function fetch_markets () {

        $markets = $this->publicGetPairTicker(array(
            'pair' => 'btc_jpy'
        ));
        $currencies = is_array ($markets['data']) ? array_keys ($markets['data']) : array ();
dd($currencies );
        $result = array ();
        for ($i = 0; $i < count ($currencies); $i++) {
            $id = $currencies[$i];
            if ($id != 'date') {
                $market = $markets['data'][$id];
                $base = $id;
                $quote = 'JPY';
                $symbol = $id . '/' . $quote;
                $result[] = array_merge ($this->fees['trading'], array (
                    'id' => $id,
                    'symbol' => $symbol,
                    'base' => $base,
                    'quote' => $quote,
                    'info' => $market,
                    'lot' => null,
                    'active' => true,
                    'precision' => array (
                        'amount' => null,
                        'price' => null,
                    ),
                    'limits' => array (
                        'amount' => array (
                            'min' => null,
                            'max' => null,
                        ),
                        'price' => array (
                            'min' => null,
                            'max' => null,
                        ),
                        'cost' => array (
                            'min' => null,
                            'max' => null,
                        ),
                    ),
                ));
            }
        }
        return $result;
    }

    public function fetch_balance ($params = array ()) {

        $response = $this->privateGetUserAssets (array_merge (array (), $params));
dd($response);
        $result = array ( 'info' => $response );
        $balances = $response['data'];
        $currencies = is_array ($this->currencies) ? array_keys ($this->currencies) : array ();

        for ($i = 0; $i < count ($currencies); $i++) {
            $currency = $currencies[$i];
            $account = $this->account ();
            $lowercase = strtolower ($currency);
            $account['total'] = $this->safe_float($balances, 'total_' . $lowercase);
            $account['used'] = $this->safe_float($balances, 'in_use_' . $lowercase);
            $account['free'] = $this->safe_float($balances, 'available_' . $lowercase);
            $result[$currency] = $account;
        }
        return $this->parse_balance($result);
    }

    public function create_order ($symbol, $type, $side, $amount, $price = null, $params = array ()) {

        $order = [
            'pair' => 'xrp_jpy',
            'amount' => '1',
            'price' => '20',
            'side' => 'buy',        // buy  sell
            'type' => 'limit',      // limit 指値、market 成行
        ];
        return $response = $this->privatePostUserSpotOrder (array_merge ($order, $params));
    }
}