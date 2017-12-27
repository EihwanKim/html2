<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Goutte\Client as CrawlerClient;
use GuzzleHttp\Client as GuzzleClient;
use App\Mail\MailgunMailer;
use Illuminate\Support\Facades\Mail;
use App\Trail;

class XrpController extends Controller
{
    //
    public function index (Request $request) {

        $send_btc_amount = $request->input('send_btc_amount');
        if (!isset($send_btc_amount)) {
            $send_btc_amount = 1000;
        }

        $res_kr_json = exec(' curl https://api.bithumb.com/public/ticker/XRP');
        $res_kr = json_decode($res_kr_json);
        $one_coin_price_kr = $res_kr->data->closing_price;

        $res_jp_json = exec(' curl https://coincheck.com/api/rate/XRP_JPY');
        $res_jp = json_decode($res_jp_json);
        $one_coin_price_jp = $res_jp->rate;

        $crawlerClient = new CrawlerClient();
        $crawler = $crawlerClient->request('GET', env('REAL_CURRENCY_CONVERTER_URL'));
        $one_jp_won_at_real = $crawler->filter('.uccResultAmount')->text();
        $one_jp_won_at_real = floatval($one_jp_won_at_real);

        $one_jpy_to_btc_to_krw = $one_coin_price_kr / $one_coin_price_jp ;
        $one_btc_jpy_to_krw_at_real = $one_coin_price_jp * $one_jp_won_at_real;
        $send_btc_amount = $send_btc_amount - ($send_btc_amount * (0.15 /100)); //BTC
        $btc_fee_jp_to_kr = floatval(env('COIN_SEND_FEE_COINCHECK_XRP'));
        $real_btc_send_jp_to_kr = $send_btc_amount - $btc_fee_jp_to_kr;
        $real_btc_send_jp_to_kr  = $real_btc_send_jp_to_kr  - ($real_btc_send_jp_to_kr  * (0.15 /100)); //BTC
        $estimated_krw = $real_btc_send_jp_to_kr * $one_coin_price_kr;
        $estimated_jpy = $estimated_krw / $one_jp_won_at_real;
        $bank_fee_kr_to_jp = $this->get_bank_sending_fee_kr_to_jp($estimated_krw);
        $recieve_jp_fee = 4000;
        $bank_fee_kr_to_jp_at_jpy = ($bank_fee_kr_to_jp / $one_jp_won_at_real) + $recieve_jp_fee; //1
        $final_jpy = ($estimated_krw / $one_jp_won_at_real) - $bank_fee_kr_to_jp_at_jpy;
        $send_btc_price = $one_coin_price_jp * $send_btc_amount;
        $gap = $final_jpy - ($send_btc_price);
        $rate = $gap / $final_jpy *100;
        $data['jp_price'] = $one_coin_price_jp;
        $data['kr_price'] = $one_coin_price_kr;
        $data['one_jpy_to_btc_to_krw'] = floatval($one_jpy_to_btc_to_krw);
        $data['one_jp_won_at_real'] = $one_jp_won_at_real;
        $data['one_btc_jpy_to_krw_at_real'] = $one_btc_jpy_to_krw_at_real;
        $data['send_btc_amount'] = $send_btc_amount;
        $data['send_btc_price'] = $send_btc_price;
        $data['btc_fee_jp_to_kr'] = $btc_fee_jp_to_kr;
        $data['real_btc_send_jp_to_kr'] = $real_btc_send_jp_to_kr;
        $data['estimated_krw'] = $estimated_krw;
        $data['estimated_jpy'] = $estimated_jpy;
        $data['bank_fee_kr_to_jp'] = $bank_fee_kr_to_jp;
        $data['recieve_jp_fee'] = $recieve_jp_fee;
        $data['bank_fee_kr_to_jp_at_jpy'] = $bank_fee_kr_to_jp_at_jpy;
        $data['final_jpy'] = $final_jpy;
        $data['gap'] = $gap;
        $data['rate'] = $rate;
        $trail = new Trail;
        $trail->jp_price=$data['jp_price'];
        $trail->kr_price=$data['kr_price'];
        $trail->one_jpy_to_btc_to_krw=$data['one_jpy_to_btc_to_krw'];
        $trail->one_jp_won_at_real=$data['one_jp_won_at_real'];
        $trail->one_btc_jpy_to_krw_at_real=$data['one_btc_jpy_to_krw_at_real'];
        $trail->send_btc_amount = $send_btc_amount;
        $trail->btc_fee_jp_to_kr=$data['btc_fee_jp_to_kr'];
        $trail->real_btc_send_jp_to_kr=$data['real_btc_send_jp_to_kr'];
        $trail->estimated_krw=$data['estimated_krw'];
        $trail->estimated_jpy=$data['estimated_jpy'];
        $trail->bank_fee_kr_to_jp=$data['bank_fee_kr_to_jp'];
        $trail->recieve_jp_fee=$data['recieve_jp_fee'];
        $trail->bank_fee_kr_to_jp_at_jpy=$data['bank_fee_kr_to_jp_at_jpy'];
        $trail->final_jpy=$data['final_jpy'];
        $trail->gap=$data['gap'];
        $trail->rate=$data['rate'];

//        $trail->save();
        return view('xrp', $data);
    }

    /**
     * 韓国から日本へ銀行送金時の手数料
     * @param $krw_amount
     * @return float|int
     */
    private function get_bank_sending_fee_kr_to_jp($krw_amount) {
        //TODO 具体的な手数料は調査して実装、戻り値は韓国W
        $fee = 8000;
//        if ($krw_amount > 20000000) {
//            $fee = $krw_amount * 0.0001;
//        } else if ($krw_amount > 10000000) {
//            $fee = $krw_amount * 0.0001;
//        } else {
//            $fee = $krw_amount * 0.0001;
//        }
        return $fee ;
    }
}
