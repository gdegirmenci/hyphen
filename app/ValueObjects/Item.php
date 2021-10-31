<?php

namespace App\ValueObjects;

/**
 * Class Item
 * @package App\ValueObjects
 */
final class Item
{
    private string $productId;
    private int $quantity;
    private float $unitPrice;
    private float $totalPrice;
    private int $categoryId;

    /**
     * @param string $productId
     * @param string $quantity
     * @param string $unitPrice
     * @param string $totalPrice
     */
    public function __construct(string $productId, string $quantity, string $unitPrice, string $totalPrice)
    {
        $this->productId = $productId;
        $this->quantity = (int)$quantity;
        $this->unitPrice = (float)$unitPrice;
        $this->totalPrice = (float)$totalPrice;
    }

    /**
     * @return string
     */
    public function getProductId(): string
    {
        return $this->productId;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @return float
     */
    public function getUnitPrice(): float
    {
        return $this->unitPrice;
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
     * @param int $categoryId
     */
    public function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    /**
     * @return int
     */
    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    /**
     * @return self
     */
    public function increaseQuantity(): self
    {
        $this->quantity++;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'product-id' => $this->getProductId(),
            'quantity' => $this->getQuantity(),
            'unit-price' => $this->getUnitPrice(),
            'total' => $this->getTotalPrice(),
        ];
    }
}
