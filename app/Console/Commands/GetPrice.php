<?php

namespace App\Console\Commands;

use App\Library\Utils;
use Illuminate\Console\Command;
use App\Trail;
use App\Notified;
use GuzzleHttp\Client;
use Goutte\Client as CrawlerClient;
use GuzzleHttp\Client as GuzzleClient;
use App\Mail\MailgunMailer;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class GetPrice extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:get_price';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'command to get XRP price and calculate';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle() {


        $data =
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
        $trail->save();

        if ($data['rate'] > 20) {
            Utils::send_line('XRPの裁定取引チャンス：現在のレート：' . $data['rate'] . '%');
        }
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