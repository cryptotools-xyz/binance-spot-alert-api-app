<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Console\Command;
use App\Notifications\PriceReachedNotification; 
use Illuminate\Support\Facades\Notification;
use App\Models\TickerPriceSymbol;
use Http;

class ETH_BUSDTPriceCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eth_busd:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check ETH_BUSD price and notify the slack channel';

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
        /**
         * 1. Get ticker price symbol and log it
         */
        $response = Http::get('https://api.binance.com/api/v3/ticker/price?symbol=ETHBUSD');
        $data = $response->json();
        Log::channel('daily')->info($data);

        $tickerPriceSymbol = new TickerPriceSymbol($data['symbol'], floatval($data['price']));

        /**
         * 2. Notify & handle conditionals
         * - notify if price is near round up or round down by 100
         * - only notify if previous start is different then next
         */

        $contents = Storage::disk('local')->get('ETHBUSD.txt'); 

        if($tickerPriceSymbol->getPrice() - $tickerPriceSymbol->getPriceRoundDownHundred() <= 10 || $tickerPriceSymbol->getPriceRoundUpHundred() - $tickerPriceSymbol->getPrice() <= 10) {

            if(substr($contents, 0, 2) != substr($tickerPriceSymbol->getPrice(), 0, 2)) {

                Notification::route('slack', env('SLACK_WEBHOOK_ETH_BUSD'))
                ->notify(new PriceReachedNotification('ETH_BUSD', $tickerPriceSymbol->getPrice()));

                Storage::disk('local')->put('ETHBUSD.txt', $tickerPriceSymbol->getPrice());
            }
        }

        return 0;
    }
}
