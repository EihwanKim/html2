<?php

namespace App\Http\Controllers;

use App\CoinMaster;
use App\Library\MyBithumb;
use App\Library\MyCoincheck;
use App\Library\Utils;
use App\Trail;
use Illuminate\Http\Request;
use App\Library\CoincheckCrawler;
use Goutte\Client as CrawlerClient;
use ccxt;

class TestController extends Controller
{
    public $coincheck;
    //
    public function index(Request $request) {

        try {
            $this->coincheck = new MyCoincheck([
                'apiKey' => env('API_KEY_COINCHECK'),
                'secret' => env('API_SECRET_COINCHECK'),
            ]);

            $coin_master = CoinMaster::all()->where('enable', true);

            foreach ($coin_master as $coin) {

            }




        } catch (\Exception $e) {
            $desc = $this->coincheck->describe();
            $json_exception = str_replace($desc['id'], '',  $e->getMessage());
            $response = json_decode($json_exception);
            Utils::send_line(__CLASS__ . "\n\n" . "{$response->message}");
        }
    }


}
