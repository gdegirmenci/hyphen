<?php

namespace App\Traits;

/**
 * Trait Operations
 * @package App\Traits
 */
trait ApplyDiscount
{
    /**
     * @param float $price
     * @param int $discountPercentage
     * @return float
     */
    public function applyDiscount(float $price, int $discountPercentage): float
    {
        return $price - ($price * $discountPercentage / 100);
    }
}
