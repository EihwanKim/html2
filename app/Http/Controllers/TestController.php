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

class TestController extends Controller
{
    public $coincheck;
    //
    public function index(Request $request) {

        $coin_master = CoinMaster::all()->where('coin_type', 'XRP');

        $data = [];
        foreach ($coin_master as $coin) {
            $trails = DB::table('trails')->where('coin_type', $coin->coin_type)->orderBy('id', 'desc')->limit(10)->get();
            $time = [];
            $margin = [];

            foreach ($trails as $val) {
                array_push($time, Carbon::createFromFormat('Y-m-d H:i:s', $val->created_at)->timestamp * 1000);
//                array_push($time, $val->created_at);
                array_push($margin, floatval($val->rate));
            }
            $data['times'] = json_encode($time);
            $data['margins'][$coin->coin_type] = json_encode($margin);
            $data['color'][$coin->coin_type] = $coin->color;
        }
var_export($data);
        return view('test', $data);
    }


}
