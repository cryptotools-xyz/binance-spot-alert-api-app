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
}
