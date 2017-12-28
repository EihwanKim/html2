<?php

namespace App\Http\Controllers;

use App\Library\Utils;
use Illuminate\Http\Request;

class LineController extends Controller
{
    //
    public function index(Request $request) {

        $res = Utils::floor(12345.6789, 2);
        dd($res);








        $res = Utils::send_line('こんにチワワ!!');
        return view('empty');
    }


}
