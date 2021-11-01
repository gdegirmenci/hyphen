<?php

namespace Tests\Unit\Strategies\Discount\Strategies;

use App\Entities\Order;
use App\Strategies\Discount\Strategies\CategoryBasedDiscountStrategy;
use App\Strategies\Discount\Strategies\DiscountStrategyInterface;
use App\ValueObjects\Item;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

/**
 * Class CategoryBasedDiscountStrategyTest
 * @package Tests\Unit\Strategies\Discount\Strategies
 * @coversDefaultClass \App\Strategies\Discount\Strategies\CategoryBasedDiscountStrategy
 */
class CategoryBasedDiscountStrategyTest extends TestCase
{
    use WithFaker;

    /**
     * @var Order|MockObject
     */
    private $order;

    /**
     * @var CategoryBasedDiscountStrategy|DiscountStrategyInterface|MockObject
     */
    private $strategy;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->order = $this->createMock(Order::class);
        $this->strategy = new CategoryBasedDiscountStrategy($this->order);
        parent::setUp();
    }

    /**
     * @param array $methods
     * @return void
     */
    public function setMockStrategy(array $methods = []): void
    {
        $this->strategy = $this->getMockBuilder(CategoryBasedDiscountStrategy::class)
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
        $this->setMockStrategy(['getDiscountedItems']);
        $productId = (string)random_int(1, 10);
        $quantity = (string)random_int(1, 10);
        $unitPrice = (string)random_int(1, 10);
        $totalPrice = (string)random_int(1, 10);
        $item = new Item($productId, $quantity, $unitPrice, $totalPrice);
        $discountedItems = collect([$item]);

        $this->strategy->expects($this->once())->method('getDiscountedItems')->willReturn($discountedItems);
        $this->order->expects($this->once())->method('setItems')->with($discountedItems->toArray());

        $this->assertEquals($this->order, $this->strategy->calculateDiscount());
    }

    /**
     * @test
     * @covers ::getDiscountThreshold
     */
    function it_should_return_discount_threshold()
    {
        $this->assertEquals(config('discount.category_based.threshold'), $this->strategy->getDiscountThreshold());
    }

    /**
     * @test
     * @covers ::isEligibleToDiscount
     */
    function it_should_return_true_when_eligible_items_are_not_empty()
    {
        $this->setMockStrategy(['getEligibleItems']);

        $this->strategy
            ->expects($this->once())
            ->method('getEligibleItems')
            ->willReturn(collect([$this->faker->word => $this->faker->word]));

        $this->assertTrue($this->strategy->isEligibleToDiscount());
    }

    /**
     * @test
     * @covers ::isEligibleToDiscount
     */
    function it_should_return_false_when_eligible_items_are_empty()
    {
        $this->setMockStrategy(['getEligibleItems']);

        $this->strategy->expects($this->once())->method('getEligibleItems')->willReturn(collect());

        $this->assertFalse($this->strategy->isEligibleToDiscount());
    }

    /**
     * @test
     * @covers ::getCategoryId
     */
    function it_should_return_category_id()
    {
        $this->assertEquals(
            config('discount.category_based.category_id'),
            $this->invokeMethod($this->strategy, 'getCategoryId')
        );
    }

    /**
     * @test
     * @covers ::getDiscountedItems
     */
    function it_should_return_discounted_items()
    {
        $this->setMockStrategy(['getEligibleItems']);
        $productId = $this->faker->name;
        $quantity = (string)random_int(1, 10);
        $unitPrice = (string)random_int(1, 10);
        $totalPrice = (string)random_int(1, 10);
        $item = new Item($productId, $quantity, $unitPrice, $totalPrice);
        $eligibleItems = collect([$item]);

        $this->strategy
            ->expects($this->once())
            ->method('getEligibleItems')
            ->willReturn($eligibleItems);

        /** @var Collection $discountedItems */
        $discountedItems = $this->invokeMethod($this->strategy, 'getDiscountedItems');

        $this->assertEquals((int)$quantity + 1, $discountedItems->first()->getQuantity());
    }

    /**
     * @test
     * @covers ::getEligibleItems
     */
    function it_should_return_eligible_items()
    {
        $productId = $this->faker->name;
        $quantity = (string)random_int(1, 10);
        $unitPrice = (string)random_int(1, 10);
        $totalPrice = (string)random_int(1, 10);
        $categoryId = config('discount.category_based.category_id');
        $item1 = new Item(
            $productId,
            config('discount.category_based.threshold'),
            $unitPrice,
            $totalPrice
        );
        $item2 = new Item($productId, $quantity, $unitPrice, $totalPrice);
        $item1->setCategoryId($categoryId);
        $item2->setCategoryId(random_int(10, 15));
        $items = collect([$item1, $item2]);

        $this->order->expects($this->once())->method('getItems')->willReturn($items);

        $this->assertEquals(collect([$item1]), $this->invokeMethod($this->strategy, 'getEligibleItems'));
    }
}
