<?php

namespace App\Http\Controllers;

use App\Library\MyBitbank;
use App\Library\MyBithumb;
use Illuminate\Http\Request;

class TestController extends Controller
{
    //
    public function index(Request $request)
    {

        $bitbank = new MyBitbank();
//        $bitbank = new MyBithumb();
//        dd($bitbank->fetch_balance());
        dd($bitbank->create_order('','','',''));
    }
}
