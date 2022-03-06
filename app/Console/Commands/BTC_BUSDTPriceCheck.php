<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Notifications\BTC_BUSDPriceReached; 
use Illuminate\Support\Facades\Notification;

class BTC_BUSDTPriceCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'btc_busd:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check BTC_BUSD price and notify the slack channel';

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
     * @return int
     */
    public function handle()
    {
        Notification::route('slack', 'https://hooks.slack.com/services/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')->notify(new BTC_BUSDPriceReached(30000));
            
        return 0;
    }
}
