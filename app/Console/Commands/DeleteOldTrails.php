<?php

namespace App\Console\Commands;

use App\Library\Utils;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteOldTrails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:delete_old_trails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete trails records older than 3 days';

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
        try {
            DB::delete('DELETE FROM trails WHERE CREATED_AT < DATE_SUB(CURRENT_DATE(), INTERVAL 3 DAY) AND MOD(MINUTE(created_at) , 10) > 0');
        } catch (\Exception $e) {
            Utils::send_line(__CLASS__ , $e);
        }

    }
}
