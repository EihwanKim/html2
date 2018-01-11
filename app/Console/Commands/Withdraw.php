<?php

namespace App\Console\Commands;

use App\Configs;
use App\Library\MyBithumb;
use App\Library\Utils;
use Illuminate\Console\Command;

class Withdraw extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:withdraw';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command withdraw krw from bithumb';

    private $bithumb;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        logger('Withdraw ' . date('Y-m-d H:i:s'));

        try {

            $this->bithumb = new MyBithumb();

            $config_withdraw_amount = Configs::whereName('withdraw_amount')->first();

            $balances = $this->bithumb->fetch_balance();

            //出金API実行
            if ($balances["free"]['KRW'] > $config_withdraw_amount->value) {
                $this->creatr_withdraw_order($balances["free"]['KRW']);
            }
        } catch (\Exception $e) {
            Utils::send_line(__CLASS__ , $e);
            $desc = $this->bithumb->describe();
            $json_exception = str_replace($desc['id'], '',  $e->getMessage());
            $response = json_decode($json_exception);
            if ($response) {
                Utils::send_line(__CLASS__ . "\n\n" . "{$response->message}");
            }
        }
    }

    private function creatr_withdraw_order($amount) {

        $order = $this->bithumb->withdraw_krw(env('WITHDRAW_BANK_CODE_BITHUMB'), env('WITHDRAW_BANK_ACCOUNT_NO_BITHUMB'), $amount);
        $text = \GuzzleHttp\json_encode($order);
        Utils::send_line(__CLASS__ . "\n" . $text);
    }

}
