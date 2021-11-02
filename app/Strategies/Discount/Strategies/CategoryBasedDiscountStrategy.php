<?php

namespace App\Strategies\Discount\Strategies;

use App\Entities\Order;
use App\ValueObjects\Item;
use Illuminate\Support\Collection;

/**
 * Class CategoryBasedDiscountStrategy
 * @package App\Strategies\Discount\Strategies
 */
class CategoryBasedDiscountStrategy implements DiscountStrategyInterface
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
     * @return Order
     */
    public function calculateDiscount(): Order
    {
        $this->order->setItems($this->getDiscountedItems()->toArray());

        return $this->order;
    }

    /**
     * @return int
     */
    public function getDiscountThreshold(): int
    {
        return config('discount.category_based.threshold');
    }

    /**
     * @return bool
     */
    public function isEligibleToDiscount(): bool
    {
        return $this->getEligibleItems()->isNotEmpty();
    }

    /**
     * @return int
     */
    protected function getCategoryId(): int
    {
        return config('discount.category_based.category_id');
    }

    /**
     * @return Collection
     */
    protected function getDiscountedItems(): Collection
    {
        return $this->getEligibleItems()
            ->transform(function (Item $item) {
                return $item->increaseQuantity();
            });
    }

    /**
     * @return Collection
     */
    protected function getEligibleItems(): Collection
    {
        return $this->order
            ->getItems()
            ->filter(function (Item $item) {
                return $item->getCategoryId() === $this->getCategoryId() &&
                    $item->getQuantity() === $this->getDiscountThreshold();
            });
    }
}
