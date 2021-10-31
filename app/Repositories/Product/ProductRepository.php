<?php

namespace App\Repositories\Product;

use App\Models\Product;

/**
 * Class ProductRepository
 * @package App\Repositories\Product
 */
class ProductRepository implements ProductRepositoryInterface
{
    private Product $product;

    /**
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * @param string $id
     * @return Product|null
     */
    public function getProductById(string $id): ?Product
    {
        return $this->product->where(compact('id'))->first();
    }
}
