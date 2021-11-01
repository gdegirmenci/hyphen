<?php

namespace App\Services;

use App\Entities\Order;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Strategies\Discount\DiscountStrategy;
use App\ValueObjects\Item;

/**
 * Class DiscountService
 * @package App\Services
 */
class DiscountService
{
    private ProductRepositoryInterface $productRepository;

    /**
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @param Order $order
     * @return array
     */
    public function getDiscount(Order $order): array
    {
        $order->getItems()
            ->transform(function (Item $item) {
                $product = $this->productRepository->getProductById($item->getProductId());

                if ($product) {
                    $item->setCategoryId($product->category_id);
                }
            });

        return $this->getDiscountStrategy($order)->calculateDiscount()->getOrder()->toArray();
    }

    /**
     * @param Order $order
     * @return DiscountStrategy
     */
    protected function getDiscountStrategy(Order $order): DiscountStrategy
    {
        return new DiscountStrategy($order);
    }
}
