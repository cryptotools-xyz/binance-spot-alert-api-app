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
    public function getPriceRoundUpThousand() {
        return ceil($this->price / 1000) * 1000; 
    }

    public function getPriceRoundDownThousand() {
        return floor($this->price / 1000) * 1000; 
    }

    public function getPriceRoundUpHundred() {
        return ceil($this->price / 100) * 100; 
    }

    public function getPriceRoundDownHundred() {
        return floor($this->price / 100) * 100; 
    }
}
