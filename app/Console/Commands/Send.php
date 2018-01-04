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
use App\Library\MyCoincheck;
use ccxt\BaseError;
use ccxt\ExchangeNotAvailable;
use Illuminate\Console\Command;
use App\Library\Utils;
use App\Trail;

class Send extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'command to send COIN';

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
    public $coincheck;

    public function handle() {

        logger('Send START'. date('Y-m-d H:i:s'));

        try  {

            $coin_master = CoinMaster::all()->where('enable', true);

            $this->coincheck = new MyCoincheck([
                'apiKey' => env('API_KEY_COINCHECK'),
                'secret' => env('API_SECRET_COINCHECK'),
            ]);

            $balances = $this->coincheck->fetch_balance();

            //販売注文実施
            foreach ($balances["free"] as $coin_type => $amount) {
                $coin = CoinMaster::whereCoinType($coin_type)->first();
                if ($coin_type != self::CURRENCY && isset($coin) && $amount > $coin->send_minimum_amount) {
                    $this->create_send_order($coin_type, env('BITCOIN_ADDRESS_BITHUMB'), $amount);
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

    private function create_send_order($coin_type, $to, $amount) {
        $coin = CoinMaster::whereCoinType($coin_type)->first();
        $fee = 0;
        if ($coin) {
            $fee = $coin->send_fee;
        }
        $amount = $amount - $fee;
        $order = $this->coincheck->send_coin($to, $amount);
        $text = \GuzzleHttp\json_encode($order);
        Utils::send_line(__CLASS__ . "\n" . $text);
    }
}