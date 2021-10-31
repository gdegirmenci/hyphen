<?php

namespace App\Entities;

use App\ValueObjects\Item;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

/**
 * Class Order
 * @package App\Entities
 */
class Order implements Arrayable
{
    private int $orderId;
    private int $customerId;
    private array $items;
    private float $totalPrice;

    /**
     * @param string $orderId
     * @param string $customerId
     * @param array $items
     */
    public function __construct(string $orderId, string $customerId, array $items, string $totalPrice)
    {
        $this->orderId = (int)$orderId;
        $this->customerId = (int)$customerId;
        $this->items = $items;
        $this->totalPrice = $totalPrice;
    }

    /**
     * @return int
     */
    public function getOrderId(): int
    {
        return $this->orderId;
    }

    /**
     * @return int
     */
    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    /**
     * @return Collection
     */
    public function getItems(): Collection
    {
        return collect($this->items);
    }

    /**
     * @param array $items
     * @return void
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    /**
     * @return float
     */
    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    /**
     * @param float $totalPrice
     * @return void
     */
    public function setTotalPrice(float $totalPrice): void
    {
        $this->totalPrice = $totalPrice;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getOrderId(),
            'customer-id' => $this->getCustomerId(),
            'items' => $this->getItems()->transform(function (Item $item) {
                return $item->toArray();
            }),
            'total' => $this->getTotalPrice(),
        ];
    }
}
