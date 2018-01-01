<?php
/**
 * Created by IntelliJ IDEA.
 * User: eihwan
 * Date: 2017/12/31
 * Time: 7:36
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Library\Utils;

class Sell extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sell';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'command to sell XRP';

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

    }
}