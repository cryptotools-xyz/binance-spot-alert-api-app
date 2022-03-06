<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Notifications\BTC_BUSDPriceReachedNotification; 
use Illuminate\Support\Facades\Notification;
use App\Models\TickerPriceSymbol;
use Http;

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
        $response = Http::get('https://api.binance.com/api/v3/ticker/price?symbol=BTCBUSD');

        $data = $response->json();

        $tickerPriceSymbol = new TickerPriceSymbol($data['symbol'], floatval($data['price']));

        if($tickerPriceSymbol->getPrice() - $tickerPriceSymbol->getPriceRoundDown() <= 100 || $tickerPriceSymbol->getPriceRoundUp() - $tickerPriceSymbol->getPrice() <= 100) {
            Notification::route('slack', env('SLACK_WEBHOOK_BTC_BUSD'))
                ->notify(new BTC_BUSDPriceReachedNotification($tickerPriceSymbol->getPrice()));
        }

        return 0;
    }
}
