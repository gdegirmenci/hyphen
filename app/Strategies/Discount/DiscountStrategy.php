<?php

namespace App\Strategies\Discount;

use App\Entities\Order;
use App\Strategies\Discount\Strategies\CategoryBasedDiscountStrategy;
use App\Strategies\Discount\Strategies\DiscountStrategyInterface;
use App\Strategies\Discount\Strategies\ProductCountBasedDiscountStrategy;
use App\Strategies\Discount\Strategies\TotalPriceBasedDiscountStrategy;
use Illuminate\Support\Collection;

/**
 * Class DiscountStrategy
 * @package App\Strategies\Discount
 */
class DiscountStrategy
{
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
        $this->getStrategies()
            ->transform(function (string $strategy) {
                $strategy = $this->getStrategy($strategy);

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

    /**
     * @return Collection
     */
    protected function getStrategies(): Collection
    {
        return collect([
            ProductCountBasedDiscountStrategy::class,
            CategoryBasedDiscountStrategy::class,
            TotalPriceBasedDiscountStrategy::class,
        ]);
    }

    /**
     * @param string $strategyClass
     * @return DiscountStrategyInterface
     */
    protected function getStrategy(string $strategyClass): DiscountStrategyInterface
    {
        return new $strategyClass($this->order);
    }
}
