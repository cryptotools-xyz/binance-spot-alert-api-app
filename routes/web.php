<?php

use Illuminate\Support\Facades\Route;
use App\Notifications\BTC_BUSDPriceReached; 
use Illuminate\Support\Facades\Notification;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/BTC_BUSD', function () {
    Notification::route('slack', 'https://hooks.slack.com/services/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')->notify(new BTC_BUSDPriceReached(30000));
});
