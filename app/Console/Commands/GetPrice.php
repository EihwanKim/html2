<?php

namespace App\Console\Commands;

use App\CoinMaster;
use App\Library\MyBithumb;
use App\Library\MyCoincheck;
use App\Library\Utils;
use App\Trail;
use Goutte\Client as CrawlerClient;
use Illuminate\Console\Command;

class GetPrice extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:get_price';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'command to get COIN price';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle() {
        try {
            logger('GetPrice START'. date('Y-m-d H:i:s'));

            $coin_master = CoinMaster::all()->where('enable', true);

            $crawlerClient = new CrawlerClient();
            $crawler = $crawlerClient->request('GET', env('REAL_CURRENCY_CONVERTER_URL'));
            $cash_rate = $crawler->filter('.uccResultAmount')->text();
            $cash_rate = floatval($cash_rate);

            foreach ($coin_master as $coin) {
                $coin_type = $coin->coin_type;

                $coincheck = new MyCoincheck([
                    'apiKey' => env('API_KEY_COINCHECK'),
                    'secret' => env('API_SECRET_COINCHECK'),
                ]);
                $coincheck_res = $coincheck->get_rate("{$coin_type}_JPY");
                $jp_price = $coincheck_res['close'];

                $bithumb = new MyBithumb([
                    'apiKey' => env('API_KEY_BITHUMB'),
                    'secret' => env('API_SECRET_BITHUMB'),
                ]);
                $bithumb_res = $bithumb->fetch_ticker("{$coin_type}/KRW");
                $kr_price = $bithumb_res['close'];

                $coin_rate = floatval($kr_price / $jp_price);
                $rate_gap = $coin_rate - $cash_rate;

                $data = $this->get_simulation_result ($coin_type, $jp_price, $kr_price, $cash_rate);

                $trail = new Trail();
                $trail->coin_type = $data['coin_type'];
                $trail->jp_price = $data['jp_price'];
                $trail->kr_price = $data['kr_price'];
                $trail->cash_rate = $data['cash_rate'];
                $trail->buy_amount = $data['buy_amount'];
                $trail->send_amount = $data['send_amount'];
                $trail->sell_amount = $data['sell_amount'];
                $trail->return_krw = $data['return_krw'];
                $trail->return_jpy_no_fee = $data['return_jpy_no_fee'];
                $trail->input_jp = $data['input_jp'];
                $trail->return_jpy = $data['return_jpy'];
                $trail->gap = $data['gap'];
                $trail->rate = $data['rate'];
                $trail->save();

            }
        } catch (\Exception $e) {
            Utils::send_line(__CLASS__ , $e);
        }
    }

    public function get_simulation_result ($coin_type, $jp_price, $kr_price, $cash_rate) {
        $coin = CoinMaster::whereCoinType($coin_type)->first();
        $buy_amount = $coin->track_amount;

        if ($coin->buy_market_type == 'STORE') {
            $jp_price = $jp_price + ($jp_price * 0.03);   //TODO できれば実際のスプレッドを取得したい。
        } else {
            $buy_amount = $buy_amount - ($buy_amount * ($coin->buy_fee_rate / 100));
        }
        $send_amount = $buy_amount - $coin->send_fee;
        $sell_amount = $send_amount;

        if ($coin->sell_market_type == 'STORE') {
            $kr_price = $kr_price - ($kr_price * 0.03);   //TODO できれば実際のスプレッドを取得したい。
        } else {
            $sell_amount = $sell_amount - ($sell_amount * ($coin->sell_fee_rate / 100));
        }
        $return_krw = $sell_amount * $kr_price;
        $return_jpy_no_fee = $return_krw / $cash_rate;
        $input_jp = $jp_price * $buy_amount;
        $return_jpy = $return_jpy_no_fee - (8000 / $cash_rate) - 2480;
        $gap = $return_jpy - $input_jp;
        $rate = $gap / $return_jpy * 100;

        $data['coin_type'] = $coin_type;       //STR
        $data['jp_price'] = $jp_price;          //float
        $data['kr_price'] = $kr_price;          //float
        $data['cash_rate'] = $cash_rate;        //float
        $data['buy_amount'] = $buy_amount;      //float
        $data['send_amount'] = $send_amount;    //float
        $data['sell_amount'] = $sell_amount;    //float
        $data['return_krw'] = $return_krw;      //float
        $data['return_jpy_no_fee'] = $return_jpy_no_fee;    //float
        $data['input_jp'] = $input_jp;          //float
        $data['return_jpy'] = $return_jpy;      //float
        $data['gap'] = $gap;                    //float
        $data['rate'] = $rate;                  //float
        return $data;
    }

}