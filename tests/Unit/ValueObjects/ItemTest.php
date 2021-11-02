<?php

namespace Tests\Unit\ValueObjects;

use App\ValueObjects\Item;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * Class ItemTest
 * @package Tests\Unit\ValueObjects
 * @coversDefaultClass \App\ValueObjects\Item
 */
class ItemTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     * @covers ::__construct
     * @covers ::getProductId
     */
    function it_should_return_product_id()
    {
        $productId = $this->faker->name;
        $item = new Item($productId, (string)random_int(1, 10), (string)random_int(1, 10), (string)random_int(1, 10));

        $this->assertEquals($productId, $item->getProductId());
    }

    /**
     * @test
     * @covers ::getQuantity
     */
    function it_should_return_quantity()
    {
        $quantity = (string)random_int(1, 10);
        $item = new Item($this->faker->name, $quantity, (string)random_int(1, 10), (string)random_int(1, 10));

        $this->assertEquals((int)$quantity, $item->getQuantity());
    }

    /**
     * @test
     * @covers ::getUnitPrice
     */
    function it_should_return_unit_price()
    {
        $unitPrice = (string)random_int(1, 10);
        $item = new Item($this->faker->name, (string)random_int(1, 10), $unitPrice, (string)random_int(1, 10));

        $this->assertEquals((float)$unitPrice, $item->getUnitPrice());
    }

    /**
     * @test
     * @covers ::getTotalPrice
     */
    function it_should_return_total_price()
    {
        $totalPrice = (string)random_int(1, 10);
        $item = new Item($this->faker->name, (string)random_int(1, 10), (string)random_int(1, 10), $totalPrice);

        $this->assertEquals((float)$totalPrice, $item->getTotalPrice());
    }

    /**
     * @test
     * @covers ::setTotalPrice
     */
    function it_should_set_total_price()
    {
        $oldTotalPrice = random_int(1, 10);
        $newTotalPrice = random_int(1, 10);
        $item = new Item($this->faker->name, (string)random_int(1, 10), (string)random_int(1, 10), (string)$oldTotalPrice);
        $item->setTotalPrice($newTotalPrice);

        $this->assertEquals((float)$newTotalPrice, $item->getTotalPrice());
    }

    /**
     * @test
     * @covers ::setCategoryId
     */
    function it_should_set_category_id()
    {
        $categoryId = random_int(1, 10);
        $item = new Item($this->faker->name, (string)random_int(1, 10), (string)random_int(1, 10), (string)random_int(1, 10));
        $item->setCategoryId($categoryId);

        $this->assertEquals($categoryId, $this->getPrivateProperty($item, 'categoryId'));
    }

    /**
     * @test
     * @covers ::getCategoryId
     */
    function it_should_return_category_id()
    {
        $categoryId = random_int(1, 10);
        $item = new Item($this->faker->name, (string)random_int(1, 10), (string)random_int(1, 10), (string)random_int(1, 10));
        $item->setCategoryId($categoryId);

        $this->assertEquals($categoryId, $item->getCategoryId());
    }

    /**
     * @test
     * @covers ::increaseQuantity
     */
    function it_should_increase_quantity()
    {
        $quantity = (string)random_int(1, 10);
        $expectedQuantity = (int)$quantity + 1;
        $item = new Item($this->faker->name, $quantity, (string)random_int(1, 10), (string)random_int(1, 10));

        $item->increaseQuantity();

        $this->assertEquals($expectedQuantity, $this->getPrivateProperty($item, 'quantity'));
    }

    /**
     * @test
     * @covers ::toArray
     */
    function it_should_return_to_array()
    {
        $productId = $this->faker->name;
        $quantity = (string)random_int(1, 10);
        $unitPrice = (string)random_int(1, 10);
        $totalPrice = (string)random_int(1, 10);
        $item = new Item($productId, $quantity, $unitPrice, $totalPrice);

        $this->assertEquals(
            [
                'product-id' => $productId,
                'quantity' => (int)$quantity,
                'unit-price' => (float)$unitPrice,
                'total' => (float)$totalPrice,
            ],
            $item->toArray()
        );
    }
}
