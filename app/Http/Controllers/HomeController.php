<?php

namespace App\Http\Controllers;

use App\CoinMaster;
use App\Library\Utils;
use App\Trail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $coin_master = CoinMaster::all()->where('enable', true);

        $data = [];
        foreach ($coin_master as $coin ) {
            $trail = Trail::whereCoinType($coin->coin_type)->orderBy('id', 'desc')->first();
            $data[$coin->coin_type] = Utils::get_simulation_result($trail->coin_type, $trail->jp_price, $trail->kr_price, $trail->cash_rate);
        }
        return view ('home', compact('data'));

    }
}
