<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Trail;
use Carbon\Carbon;

class ChartController extends Controller
{

    //
    public function index() {

        $all = Trail::all();
        $time = [];
        $margin = [];
        foreach ($all as $val) {
            array_push($time, Carbon::createFromFormat('Y-m-d H:i:s', $val->created_at)->timestamp);
            array_push($margin, floatval($val->rate));
        }
        $data['times'] = json_encode($time);
        $data['margins'] = json_encode($margin);
        return view('chart', $data);
    }
}