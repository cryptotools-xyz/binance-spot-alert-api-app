<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Notifications\PriceReachedNotification; 
use Illuminate\Support\Facades\Notification;
use App\Models\TickerPriceSymbol;
use Illuminate\Support\Facades\Storage;
use Http;

class BTC_BUSDTPriceCheckTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'btc_busd_test:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check BTC_BUSD price and notify the slack channel (test)';

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
        $response = Http::get('https://api.binance.com/api/v3/ticker/price?symbol=BTCBUSD');
        $data = $response->json();
        Log::channel('daily')->info($data);

        $tickerPriceSymbol = new TickerPriceSymbol($data['symbol'], floatval($data['price']));

        /**
         * 2. Notify & handle conditionals
         * - notify if price is near round up or round down by 100
         * - only notify if previous start is different then next
         */

        $contents = Storage::disk('local')->get('BTCBUSDtest.txt'); 
        
        if($tickerPriceSymbol->getPriceRoundUpThousand() - $tickerPriceSymbol->getPrice() <= 100) {
            
            if(substr($contents, 0, 2) != substr($tickerPriceSymbol->getPrice(), 0, 2)) {

                Notification::route('slack', env('SLACK_WEBHOOK_BTC_BUSD_TEST'))
                ->notify(new PriceReachedNotification('BTC_BUSD', $tickerPriceSymbol->getPrice()));

                Storage::disk('local')->put('BTCBUSDtest.txt', $tickerPriceSymbol->getPrice());
            }
        }

        return 0;
    }
}
