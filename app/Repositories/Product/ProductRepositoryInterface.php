<?php

namespace App\Repositories\Product;

use App\Models\Product;

/**
 * Interface ProductRepositoryInterface
 * @package App\Repositories\Product
 */
interface ProductRepositoryInterface
{
    /**
     * @param string $id
     * @return Product|null
     */
    public function getProductById(string $id): ?Product;
}
