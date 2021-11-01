<?php

namespace Tests\Unit\Strategies\Discount\Strategies;

use App\Entities\Order;
use App\Strategies\Discount\Strategies\DiscountStrategyInterface;
use App\Strategies\Discount\Strategies\ProductCountBasedDiscountStrategy;
use App\ValueObjects\Item;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

/**
 * Class ProductCountBasedDiscountStrategyTest
 * @package Tests\Unit\Strategies\Discount\Strategies
 * @coversDefaultClass \App\Strategies\Discount\Strategies\ProductCountBasedDiscountStrategy
 */
class ProductCountBasedDiscountStrategyTest extends TestCase
{
    use WithFaker;

    /**
     * @var Order|MockObject
     */
    private $order;

    /**
     * @var ProductCountBasedDiscountStrategy|DiscountStrategyInterface|MockObject
     */
    private $strategy;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->order = $this->createMock(Order::class);
        $this->strategy = new ProductCountBasedDiscountStrategy($this->order);
        parent::setUp();
    }

    /**
     * @param array $methods
     * @return void
     */
    public function setMockStrategy(array $methods = []): void
    {
        $this->strategy = $this->getMockBuilder(ProductCountBasedDiscountStrategy::class)
            ->setConstructorArgs([$this->order])
            ->onlyMethods($methods)
            ->getMock();
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::calculateDiscount
     */
    function it_should_return_order_and_calculate_discount()
    {
        $this->setMockStrategy(['getDiscountedItems', 'updateTotalPrice']);
        $productId = (string)random_int(1, 10);
        $quantity = (string)random_int(1, 10);
        $unitPrice = (string)random_int(1, 10);
        $totalPrice = (string)random_int(1, 10);
        $item = new Item($productId, $quantity, $unitPrice, $totalPrice);
        $discountedItems = collect([$item]);

        $this->strategy->expects($this->once())->method('getDiscountedItems')->willReturn($discountedItems);
        $this->order->expects($this->once())->method('setItems')->with($discountedItems->toArray());
        $this->strategy->expects($this->once())->method('updateTotalPrice');

        $this->assertEquals($this->order, $this->strategy->calculateDiscount());
    }

    /**
     * @test
     * @covers ::getDiscountThreshold
     */
    function it_should_return_discount_threshold()
    {
        $this->assertEquals(config('discount.product_count_based.threshold'), $this->strategy->getDiscountThreshold());
    }

    /**
     * @test
     * @covers ::getCategoryId
     */
    function it_should_return_category_id()
    {
        $this->assertEquals(
            config('discount.product_count_based.category_id'),
            $this->invokeMethod($this->strategy, 'getCategoryId')
        );
    }

    /**
     * @test
     * @covers ::getDiscount
     */
    function it_should_return_discount()
    {
        $this->assertEquals(
            config('discount.product_count_based.discount'),
            $this->invokeMethod($this->strategy, 'getDiscount')
        );
    }
}
