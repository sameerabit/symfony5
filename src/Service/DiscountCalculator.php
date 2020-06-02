<?php

namespace App\Service;

class DiscountCalculator
{
    public function calculate($price)
    {
        return $price - ($price/100)*10;
    }
}