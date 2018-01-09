<?php

namespace App\Http\Controllers;

use App\CoinMaster;
use App\Configs;
use App\Library\Utils;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Goutte\Client as CrawlerClient;
use GuzzleHttp\Client as GuzzleClient;
use App\Mail\MailgunMailer;
use Illuminate\Support\Facades\Mail;
use App\Trail;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SimulationController extends Controller
{

    const BTC_JP_API_URL = 'https://coincheck.com/api/rate/';
    const BTC_KR_API_URL = 'https://api.bithumb.com/public/ticker/';
    const REAL_CURRENCY_CONVERTER = 'http://www.xe.com/currencyconverter/convert/?Amount=1&From=JPY&To=KRW';



    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $coin_type)
    {

        if (!$coin_type){
            throw new NotFoundHttpException('Page Not Fount');
        }

        $amount = $request->input('amount');
        if (!$amount) {
            $master = CoinMaster::whereCoinType($coin_type)->first();
            $amount = $master->send_minimum_amount;
        }

        $trail = Trail::whereCoinType($coin_type)->orderBy('id', 'desc')->first();
        $data = Utils::get_simulation_result($trail->coin_type, $trail->jp_price, $trail->kr_price, $trail->cash_rate, $amount);
        return view('simulation', compact('data'));
    }

}