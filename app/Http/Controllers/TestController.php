<?php

namespace App\Http\Controllers;

use App\Library\Utils;
use Illuminate\Http\Request;
use App\Library\CoincheckCrawler;

class TestController extends Controller
{
    //
    public function index(Request $request) {

        $data = Utils::getExchangeSimulation('btc');
        var_dump($data);
        $data = Utils::getMarketSimulation('xrp');
        var_dump($data);

//        $crawler = new CoincheckCrawler();
//        echo $crawler->getPrice('xrp');
//        return view('empty');
    }
}
