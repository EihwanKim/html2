<?php
/**
 * Created by IntelliJ IDEA.
 * User: eihwan
 * Date: 2017/12/31
 * Time: 7:36
 */

namespace App\Console\Commands;

use App\CoinMaster;
use App\Configs;
use App\Library\MyBithumb;
use ccxt\BaseError;
use ccxt\ExchangeNotAvailable;
use Illuminate\Console\Command;
use App\Library\Utils;
use App\Trail;

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
    protected $description = 'command to sell COIN';

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
    public $bithumb;

    public function handle() {

        logger('Sell     '. date('Y-m-d H:i:s'));

        try  {

            $coin_master = CoinMaster::all()->where('enable', true);

            $this->bithumb = new MyBithumb([
                'apiKey' => env('API_KEY_BITHUMB'),
                'secret' => env('API_SECRET_BITHUMB'),
            ]);

            $balances = $this->bithumb->fetch_balance();

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

                        $trail = Trail::whereCoinType($coin_type)->orderBy('id', 'desc')->first();
                        $price = $trail->kr_price;
                        $this->create_sell_order($coin_type, $amount, $price);

                    }
                }
            }

            //販売していないコインがある場合は販売注文実施
            foreach ($balances["free"] as $coin_type => $amount) {
                if ($coin_type != self::CURRENCY && $amount > $this->get_min_sell_amount($coin_type)) {
                    $trail = Trail::whereCoinType($coin_type)->orderBy('id', 'desc')->first();
                    $price = $trail->kr_price;
                    $this->create_sell_order($coin_type, $amount, $price);
                }
            }

        } catch (\Exception $e) {
            Utils::send_line(__CLASS__ , $e);
            $desc = $this->bithumb->describe();
            $json_exception = str_replace($desc['id'], '',  $e->getMessage());
            $response = json_decode($json_exception);
            if ($response) {
                Utils::send_line(__CLASS__ . "\n\n" . "{$response->message}");
            }
        }
    }

    private function create_sell_order ($coin_type, $amount, $price) {
        $amount = $this->get_amount($coin_type, $amount);
        $sell_price_rate = Configs::whereName('sell_price_rate')->first()->value;
        $price = $price * $sell_price_rate;
        $order = $this->bithumb->create_order($this->get_symbol($coin_type), 'limit', 'sell', $amount, $price);
        $text = \GuzzleHttp\json_encode($order);
        Utils::send_line(__CLASS__ . "\n" . $text);
    }

    private function get_symbol ($coin_type) {
        $coin_master = CoinMaster::all()->where('enable', true);
        $coin_master = $coin_master->toArray();
        if (!in_array($coin_type, $coin_master))
            throw new Exception('faild to get symbol. coin_type:'. $coin_type);
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
            return $n->sell_minimum_amount;
        }
        return 99999999;

    }
}