<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LineController extends Controller
{
    //
    public function index(Request $request) {
        $this->notice_by_line('こんにチワワ！！');
        return view('empty');
    }

    private function notice_by_line($text) {
        $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(env('API_KEY_LINE'));
        $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => env('API_SECRET_LINE')]);
        $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($text);
        $res = $bot->pushMessage(env('MY_TOKEN_LINE'), $textMessageBuilder);
        dd($res);
    }
}
