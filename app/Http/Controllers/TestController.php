<?php

namespace App\Http\Controllers;

use App\CoinMaster;
use App\Library\MyBithumb;
use App\Library\MyCoincheck;
use App\Library\Utils;
use App\Trail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Library\CoincheckCrawler;
use Goutte\Client as CrawlerClient;
use ccxt;
use Illuminate\Support\Facades\DB;
use App\Configs;

class TestController extends Controller
{

    //
    public function index(Request $request)
    {
        $bithumb = new MyBithumb([
            'apiKey' => env('API_KEY_BITHUMB'),
            'secret' => env('API_SECRET_BITHUMB'),
        ]);
        $res = $bithumb->get_wallet_info('BTC');

    }
}
