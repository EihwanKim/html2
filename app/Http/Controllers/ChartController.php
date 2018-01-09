<?php

namespace App\Http\Controllers;

use App\CoinMaster;
use App\Library\Utils;
use Illuminate\Http\Request;
use App\Trail;
use Carbon\Carbon;

class ChartController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    //
    public function index(Request $request, $coin_type = null) {


        if ($coin_type) {
            $coin_master = CoinMaster::all()->where('coin_type', $coin_type);
            $data = [];
            foreach ($coin_master as $coin) {
                $trails = Trail::all()->where('coin_type', $coin->coin_type);
                $time = [];
                $margin = [];
                foreach ($trails as $val) {
                    array_push($time, Carbon::createFromFormat('Y-m-d H:i:s', $val->created_at)->timestamp * 1000);
                    array_push($margin, floatval($val->rate));
                }
                $data['times'] = json_encode($time);
                $data['margins'][$coin->coin_type] = json_encode($margin);
                $data['color'][$coin->coin_type] = $coin->color;
            }
        } else {

            $coin_master = CoinMaster::all()->where('enable', true);
            $min_count = 0;
            foreach ($coin_master as $coin) {
                $count = Trail::where('coin_type', $coin->coin_type)->count();
                if ($min_count === 0) {
                    $min_count = $count;
                } else if ($count < $min_count) {
                    $min_count = $count;
                }
            }

            foreach ($coin_master as $coin) {
                $trails = Trail::where('coin_type', $coin->coin_type)->orderBy('ID', 'DESC')->limit($min_count)->get();
                $time = [];
                $margin = [];
                foreach ($trails as $val) {
                    array_push($time, Carbon::createFromFormat('Y-m-d H:i:s', $val->created_at)->timestamp * 1000);
                    array_push($margin, floatval($val->rate));
                }
                $data['times'] = json_encode($time);
                $data['margins'][$coin->coin_type] = json_encode($margin);
                $data['color'][$coin->coin_type] = $coin->color;
            }
        }

        return view('chart', $data);
    }
}