<?php
/**
 * Created by IntelliJ IDEA.
 * User: eihwan
 * Date: 2017/12/31
 * Time: 7:38
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use ccxt;

class Buy extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:buy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'command to buy XRP';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

//    public $coincheck;
//    public $bitflyer;
    public $market;

    public function handle() {


        try {
            $this->market = new \ccxt\coincheck([
                'apiKey' => env('API_KEY_COINCHECK'),
                'secret' => env('API_SECRET_COINCHECK'),
            ]);


        } catch (\ccxt\ExchangeError $e) {
            $desc = $this->market->describe();
            $json_exception = str_replace($desc['id'], '',  $e->getMessage());
            $response = json_decode($json_exception);
            Utils::send_line(__CLASS__ . "\n\n" . "{$response->message}");
        }
    }

}