<?php

namespace App\Strategies\Discount\Strategies;

use App\Entities\Order;
use App\Traits\ApplyDiscount;
use App\ValueObjects\Item;
use Illuminate\Support\Collection;

/**
 * Class ProductCountBasedDiscountStrategy
 * @package App\Strategies\Discount\Strategies
 */
class ProductCountBasedDiscountStrategy implements DiscountStrategyInterface
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
        $this->order->setItems($this->getDiscountedItems());
        $this->updateTotalPrice();

        return $this->order;
    }

    /**
     * @return int
     */
    public function getDiscountThreshold(): int
    {
        return config('discount.product_count_based.threshold');
    }

    /**
     * @return bool
     */
    public function isEligibleToDiscount(): bool
    {
        $items = $this->getEligibleItems()
            ->sum(function (Item $item) {
                return $item->getQuantity();
            });

        return $items >= $this->getDiscountThreshold();
    }

    /**
     * @return int
     */
    protected function getCategoryId(): int
    {
        return config('discount.product_count_based.category_id');
    }

    /**
     * @return int
     */
    protected function getDiscount(): int
    {
        return config('discount.product_count_based.discount');
    }

    /**
     * @return array
     */
    protected function getDiscountedItems(): array
    {
        $cheapestItem = $this->getCheapestItem();

        return $this->getEligibleItems()
            ->transform(function (Item $item) use ($cheapestItem) {
                if ($item->getProductId() === $cheapestItem->getProductId()) {
                    $discountedPrice = $this->applyDiscount($item->getTotalPrice(), $this->getDiscount());
                    $item->setTotalPrice($discountedPrice);

                    return $item;
                }

                return $item;
            })
            ->toArray();
    }

    /**
     * @return Item
     */
    protected function getCheapestItem(): Item
    {
        $prices = collect();
        $this->getEligibleItems()
            ->transform(function (Item $item) use ($prices) {
                return $prices->push($item->toArray());
            });
        $cheapestItem = $prices->where('unit-price', $prices->min('unit-price'))->first();

        return new Item(
            $cheapestItem['product-id'],
            $cheapestItem['quantity'],
            $cheapestItem['unit-price'],
            $cheapestItem['total']
        );
    }

    /**
     * @return Collection
     */
    protected function getEligibleItems(): Collection
    {
        return $this->order
            ->getItems()
            ->filter(function (Item $item) {
                 return $item->getCategoryId() === $this->getCategoryId();
            });
    }

    /**
     * @return void
     */
    protected function updateTotalPrice(): void
    {
        $totalPrice = $this->order
            ->getItems()
            ->sum(function (Item $item) {
                return $item->getTotalPrice();
            });
        $this->order->setTotalPrice($totalPrice);
    }
}
