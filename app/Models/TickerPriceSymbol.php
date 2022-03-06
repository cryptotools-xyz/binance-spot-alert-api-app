<?php

namespace App\Models;

class TickerPriceSymbol
{
    private $symbol;
    private $price;

    public function __construct($symbol, $price) {
        $this->symbol = $symbol;
        $this->price  = $price;
    }    

    public function getPrice() {
        return $this->price;
    }

    /**
     * source: https://stackoverflow.com/a/41365408
     */
    public function getPriceRoundUp() {
        return ceil($this->price / 1000) * 1000; 
    }

    public function getPriceRoundDown() {
        return floor($this->price / 1000) * 1000; 
    }
}
