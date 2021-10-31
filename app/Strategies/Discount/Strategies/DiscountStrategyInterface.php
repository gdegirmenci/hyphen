<?php

namespace App\Strategies\Discount\Strategies;

use App\Entities\Order;

/**
 * Interface DiscountStrategyInterface
 * @package App\Strategies\Discount\Strategies
 */
interface DiscountStrategyInterface
{
    /**
     * @return Order
     */
    public function calculateDiscount(): Order;

    /**
     * @return int
     */
    public function getDiscountThreshold(): int;

    /**
     * @return bool
     */
    public function isEligibleToDiscount(): bool;
}
