<?php
/**
 * Created by IntelliJ IDEA.
 * User: eihwan
 * Date: 2018/01/03
 * Time: 20:18
 */

namespace App\Library;

use ccxt\bithumb;

class MyBithumb extends bithumb
{

    public function __construct(array $options = array())
    {
        if (!$options) {
            parent::__construct([
                'apiKey' => env('API_KEY_BITHUMB'),
                'secret' => env('API_SECRET_BITHUMB'),
            ]);
        } else {
            parent::__construct($options);
        }
    }

    public function create_order ($symbol, $type, $side, $amount, $price = null, $params = array ()) {

        if (!$symbol)
            throw new ExchangeError ($this->id . ' create_order requires a $symbol parameter (ex BTC/KRW)');
        if (!$type)
            throw new ExchangeError ($this->id . ' create_order requires a $type parameter (limit or market)');
        if (!$side)
            throw new ExchangeError ($this->id . ' create_order requires a $side parameter (sell or buy)');

        $this->load_markets();
        $market = $this->market ($symbol);

        $type = strtolower($type);
        $side = strtolower($side);

        if ($type == 'limit') {
            $order = array (
                'order_currency' => $market['id'],
                'Payment_currency' => $market['quote'],
                'units' => $amount,
                'price' => $price,
            );
            if ($side == 'buy') {
                $order['type'] = 'bid';
                return $this->privatePostTradePlace (array_merge ($order, $params));
            }

            if ($side == 'sell') {
                $order['type'] = 'ask';
                return  $this->privatePostTradePlace (array_merge ($order, $params));
            }

        } else if ($type == 'market') {
            $order = array (
                'currency' => $market['id'],
                'units' => $amount, // min = 10
            );
            if ($side == 'buy')
                return $this->privatePostTradeMarketBuy (array_merge ($order, $params));

            if ($side == 'sell')
                return $this->privatePostTradeMarketSell (array_merge ($order, $params));
        }
    }

    public function fetch_orders ($symbol = null, $since = null, $limit = null, $params = array ()) {

        $this->load_markets();
        $market = $this->market ($symbol);

        $order = [
            'currency' => $market['id']
        ];
        return $this->privatePostInfoOrders(array_merge ($order, $params));
    }

    public function withdraw_krw($back_code, $bank_account, $amount) {
        $order = [
            'bank' => $back_code,
            'account' => $bank_account,
            'price' => $amount
        ];
        return $this->privatePostTradeKrwWithdrawal ($order);
    }

    public function get_wallet_info ($coin_type, $params = array ()) {
        $order = [
            'currency' => $coin_type
        ];
        return $this->privatePostInfoWalletAddress(array_merge ($order, $params));
    }
}