<?php

namespace App\Http\Controllers;

use App\Library\Utils;
use Illuminate\Http\Request;

class LineController extends Controller
{
    const SAY = [
      'こんにチワワ！',
        'ご飯ください〜',
        '散歩行きましょう〜',
        'うんち片付けて〜',
    ];
    //
    public function index(Request $request) {

        for ($i = 0 ; $i < 20 ; $i++) {
            sleep(1);
            $res = Utils::send_line(self::SAY[array_rand(self::SAY)]);
        }


        return view('empty');
    }


}
