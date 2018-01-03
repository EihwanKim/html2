<?php
/**
 * Created by IntelliJ IDEA.
 * User: eihwan
 * Date: 2017/12/31
 * Time: 7:36
 */

namespace App\Console\Commands;

use App\CoinMaster;
use App\Library\MyBithumb;
use ccxt\BaseError;
use ccxt\ExchangeNotAvailable;
use Illuminate\Console\Command;
use App\Library\Utils;

class Sell extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sell';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'command to sell XRP';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    const CURRENCY = 'KRW';
    public $price_list = [];
    public $bithumb;

    public function handle() {

        try  {

            $coin_master = CoinMaster::all()->where('enable', true);
            $target_coins = [];
            foreach ($coin_master as $coin) {
                $coin_type = $coin->coin_type;
                array_push($target_coins, $coin_type);
            }

            $this->bithumb = new MyBithumb([
                'apiKey' => env('API_KEY_BITHUMB'),
                'secret' => env('API_SECRET_BITHUMB'),
            ]);

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

        } catch (\Exception $e) {
            echo $e->getMessage();
            Utils::send_line($e->getMessage());
            $desc = $this->bithumb->describe();
            $json_exception = str_replace($desc['id'], '',  $e->getMessage());
            $response = json_decode($json_exception);
            Utils::send_line(__CLASS__ . "\n\n" . "{$response->message}");
        }
    }

    private function create_sell_order ($coin_type, $amount, $price) {

        $amount = $this->get_amount($coin_type, $amount);

        $order = $this->bithumb->create_order($this->get_symbol($coin_type), 'limit', 'sell', $amount, $price);
        $text = \GuzzleHttp\json_encode($order);
        Utils::send_line($text);
    }

    private function get_symbol ($coin_type) {
        $master_coins = Utils::getMasterCoins();
        if (!in_array($coin_type, $master_coins))
            return '';
        return $coin_type . '/' . self::CURRENCY;
    }

    private function get_amount($coin_type, $amount) {
        $coin_type = strtoupper($coin_type);
        $n = CoinMaster::whereCoinType($coin_type)->first();
        if ($n) {
            return Utils::floor($amount, $n->decimal_number);
        }
        return 0;
    }

    private function get_min_sell_amount($coin_type) {
        $coin_type = strtoupper($coin_type);
        $n = CoinMaster::whereCoinType($coin_type)->first();
        if ($n) {
            return $n->sell_minimun_amount;
        }
        return 99999999;

    }
}