<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Library\CoincheckCrawler;

class TestController extends Controller
{
    //
    public function index(Request $request) {



        $crawler = new CoincheckCrawler();
        dd($crawler->getPrice('xrp'));
    }
}
