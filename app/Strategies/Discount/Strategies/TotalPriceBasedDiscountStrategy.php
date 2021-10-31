<?php

namespace App\Strategies\Discount\Strategies;

use App\Entities\Order;
use App\Traits\ApplyDiscount;

/**
 * Class TotalPriceBasedDiscountStrategy
 * @package App\Strategies\Discount\Strategies
 */
class TotalPriceBasedDiscountStrategy implements DiscountStrategyInterface
{
    use ApplyDiscount;

    private Order $order;

    /**
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * @return Order
     */
    public function calculateDiscount(): Order
    {
        $totalPrice = $this->order->getTotalPrice();
        $discountedPrice = $this->applyDiscount($totalPrice, $this->getDiscount());
        $this->order->setTotalPrice($discountedPrice);

        return $this->order;
    }

    /**
     * @return bool
     */
    public function isEligibleToDiscount(): bool
    {
        return $this->order->getTotalPrice() > $this->getDiscountThreshold();
    }

    /**
     * @return int
     */
    public function getDiscountThreshold(): int
    {
        return config('discount.total_price_based.threshold');
    }

    /**
     * @return int
     */
    public function getDiscount(): int
    {
        return config('discount.total_price_based.discount');
    }
}
