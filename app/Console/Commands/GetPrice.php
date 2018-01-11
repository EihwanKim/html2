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
            logger('GetPrice '. date('Y-m-d H:i:s'));

            $coin_master = CoinMaster::all()->where('enable', true);

            $crawlerClient = new CrawlerClient();
            $crawler = $crawlerClient->request('GET', env('REAL_CURRENCY_CONVERTER_URL'));
            $cash_rate = $crawler->filter('.newest')->text();
            $cash_rate = floatval(substr($cash_rate, 0, 8));

            foreach ($coin_master as $coin) {
                $coin_type = $coin->coin_type;

                $coincheck = new MyCoincheck();
                $coincheck_res = $coincheck->get_rate("{$coin_type}_JPY");
                $jp_price = $coincheck_res['close'];

                $bithumb = new MyBithumb();
                $bithumb_res = $bithumb->fetch_ticker("{$coin_type}/KRW");
                $kr_price = $bithumb_res['close'];

                $data = Utils::get_simulation_result($coin_type, $jp_price, $kr_price, $cash_rate, $coin->track_amount);

                $trail = new Trail();
                $trail->coin_type = $data['coin_type'];
                $trail->jp_price = $data['jp_price'];
                $trail->kr_price = $data['kr_price'];
                $trail->cash_rate = $data['cash_rate'];
                $trail->rate = $data['rate'];
                $trail->save();

            }
        } catch (\Exception $e) {
            Utils::send_line(__CLASS__ , $e);
        }
    }
}