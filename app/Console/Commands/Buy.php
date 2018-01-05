<?php
/**
 * Created by IntelliJ IDEA.
 * User: eihwan
 * Date: 2017/12/31
 * Time: 7:38
 */

namespace App\Console\Commands;

use App\Configs;
use App\Library\MyBitflyer;
use App\Library\MyCoincheck;
use App\Trail;
use Illuminate\Console\Command;
use App\CoinMaster;
use App\Library\Utils;


class Buy extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:buy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'command to buy COIN';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

//    public $coincheck;
//    public $bitflyer;
    public $coincheck;
    public $bitflyer;

    public function handle() {

        logger('Buy      '. date('Y-m-d H:i:s'));

        try {
            $coin_master = CoinMaster::all()->where('writable', true);

            $this->coincheck = new MyCoincheck([
                'apiKey' => env('API_KEY_COINCHECK'),
                'secret' => env('API_SECRET_COINCHECK'),
            ]);

            //古い注文はすべて削除
            $data = $this->coincheck->get_orders();
            foreach ($data['orders'] as $order) {
                sleep(1);
                $this->coincheck->cancel_order($order['id']);
            }

            $balances = $this->coincheck->fetch_balance();

            if ($balances['free']['JPY'] > Configs::whereName('buy_when_jpy_is_over')->first()->value) {
                foreach ($coin_master as $coin) {
                    $trail = Trail::whereCoinType($coin->coin_type)->orderBy('id', 'desc')->first();
                    $balances['free'] = array_reverse($balances["free"]);
                    foreach ($balances["free"] as $coin_type => $amount) {
                        if ($coin_type != 'JPY' &&
                            floatval($trail->rate) > floatval(Configs::whereName('buy_rate')->first()->value) &&
                            true
                        ) {
                            $market_type = $coin->buy_market_type;
//                        $amount = $coin->track_amount;
                            $amount = floatval($coin->buy_minimum_amount);
                            $price = $trail->jp_price;
                            $this->create_buy_order($coin_type, $market_type, $amount, $price, $trail->rate);
                        }
                    }
                }
            }


        } catch (\Exception $e) {
            Utils::send_line(__CLASS__ , $e);
            $desc = $this->coincheck->describe();
            $json_exception = str_replace($desc['id'], '',  $e->getMessage());
            $response = json_decode($json_exception);
            if ($response) {
                Utils::send_line(__CLASS__ . "\n\n" . "{$response->message}");
            }
        }
    }

    private function create_buy_order ($coin_type, $market_type, $amount, $price, $trail_rate = null) {

        if ($market_type == 'STORE') {
            Utils::send_line(__CLASS__ . "\n" . "チャンス到来！\n現在のレートは{$trail_rate}です!!");
        } else {

            sleep(1);
            $buy_price_rate = Configs::whereName('buy_price_rate')->first()->value;
            $price = floor($price * $buy_price_rate);
            $order = $this->coincheck->create_order("{$coin_type}/JPY", "limit", "buy", $amount, $price);
            $text = \GuzzleHttp\json_encode($order);
            Utils::send_line(__CLASS__ . "\n" . $text);
            return $order;
        }
    }
}