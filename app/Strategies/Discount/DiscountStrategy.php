<?php

namespace App\Strategies\Discount;

use App\Entities\Order;
use App\Strategies\Discount\Strategies\CategoryBasedDiscountStrategy;
use App\Strategies\Discount\Strategies\DiscountStrategyInterface;
use App\Strategies\Discount\Strategies\ProductCountBasedDiscountStrategy;
use App\Strategies\Discount\Strategies\TotalPriceBasedDiscountStrategy;

/**
 * Class DiscountStrategy
 * @package App\Strategies\Discount
 */
class DiscountStrategy
{
    const STRATEGIES = [
        TotalPriceBasedDiscountStrategy::class,
        ProductCountBasedDiscountStrategy::class,
        CategoryBasedDiscountStrategy::class,
    ];

    private Order $order;

    /**
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * @return void
     */
    public function calculateDiscount(): self
    {
        collect(self::STRATEGIES)
            ->transform(function (string $strategy) {
                /** @var DiscountStrategyInterface $strategy */
                $strategy = new $strategy($this->order);

                if ($strategy->isEligibleToDiscount()) {
                    $this->setOrder($strategy->calculateDiscount());
                }
            });

        return $this;
    }

    /**
     * @return Order
     */
    public function getOrder(): Order
    {
        return $this->order;
    }

    /**
     * @param Order $order
     * @return void
     */
    public function setOrder(Order $order): void
    {
        $this->order = $order;
    }
}
