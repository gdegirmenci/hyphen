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
     * @return array
     */
    public function isEligibleToDiscountDataProvider(): array
    {
        return [
            [2, '1', '1', true],
            [4, '3', '2', true],
            [5, '3', '1', false],
        ];
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
        $this->order->expects($this->once())->method('updateTotalPrice');

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
     * @covers ::isEligibleToDiscount
     * @dataProvider isEligibleToDiscountDataProvider
     * @param int $threshold
     * @param string $firstItemQuantity
     * @param string $secondItemQuantity
     * @param bool $result
     */
    function it_should_return_true_or_false_depends_on_eligibility_to_discount(
        int $threshold,
        string $firstItemQuantity,
        string $secondItemQuantity,
        bool $result
    ) {
        $this->setMockStrategy(['getEligibleItems', 'getDiscountThreshold']);
        $firstItem = new Item(
            (string)random_int(1, 10),
            $firstItemQuantity,
            (string)random_int(1, 10),
            (string)random_int(1, 10)
        );
        $secondItem = new Item(
            (string)random_int(1, 10),
            $secondItemQuantity,
            (string)random_int(1, 10),
            (string)random_int(1, 10)
        );
        $eligibleItems = collect([$firstItem, $secondItem]);

        $this->strategy->expects($this->once())->method('getEligibleItems')->willReturn($eligibleItems);
        $this->strategy->expects($this->once())->method('getDiscountThreshold')->willReturn($threshold);

        $this->assertEquals($result, $this->strategy->isEligibleToDiscount());
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

    /**
     * @test
     * @covers ::getDiscountedItems
     */
    function it_should_return_discounted_items()
    {
        $this->setMockStrategy(['getCheapestItem', 'getEligibleItems', 'applyDiscount', 'getDiscount']);
        $firstItem = new Item(
            (string)random_int(1, 10),
            (string)random_int(1, 10),
            (string)random_int(1, 10),
            (string)random_int(1, 10)
        );
        $secondItem = new Item(
            (string)random_int(1, 10),
            (string)random_int(1, 10),
            (string)random_int(1, 10),
            (string)random_int(1, 10)
        );
        $eligibleItems = collect([$firstItem, $secondItem]);
        $discount = random_int(1, 10);
        $discountedPrice = (float)random_int(1, 10);

        $this->strategy->expects($this->once())->method('getCheapestItem')->willReturn($firstItem);
        $this->strategy->expects($this->once())->method('getEligibleItems')->willReturn($eligibleItems);
        $this->strategy->expects($this->once())->method('getDiscount')->willReturn($discount);
        $this->strategy
            ->expects($this->once())
            ->method('applyDiscount')
            ->with($firstItem->getTotalPrice(), $discount)
            ->willReturn($discountedPrice);

        $discountedItems = $this->invokeMethod($this->strategy, 'getDiscountedItems');

        // Set total price as discounted
        $firstItem->setTotalPrice($discountedPrice);
        // Prepare expected data
        $expectedDiscountedItems = collect([$firstItem, $secondItem]);

        $this->assertEquals($expectedDiscountedItems, $discountedItems);
    }

    /**
     * @test
     * @covers ::getCheapestItem
     */
    function it_should_return_cheapest_item()
    {
        $this->setMockStrategy(['getEligibleItems']);
        $cheapestItem = new Item(
            (string)random_int(1, 10),
            (string)random_int(1, 10),
            (string)random_int(1, 10),
            (string)random_int(1, 10)
        );
        $secondItem = new Item(
            (string)random_int(1, 10),
            (string)random_int(1, 10),
            (string)random_int(20, 30),
            (string)random_int(1, 10)
        );
        $eligibleItems = collect([$cheapestItem, $secondItem]);

        $this->strategy->expects($this->once())->method('getEligibleItems')->willReturn($eligibleItems);

        $this->assertEquals($cheapestItem, $this->invokeMethod($this->strategy, 'getCheapestItem'));
    }

    /**
     * @test
     * @covers ::getEligibleItems
     */
    function it_should_return_eligible_items()
    {
        $this->setMockStrategy(['getCategoryId']);
        $categoryId = random_int(1, 10);
        $firstItem = new Item(
            (string)random_int(1, 10),
            (string)random_int(1, 10),
            (string)random_int(1, 10),
            (string)random_int(1, 10)
        );
        $firstItem->setCategoryId($categoryId);
        $secondItem = new Item(
            (string)random_int(1, 10),
            (string)random_int(1, 10),
            (string)random_int(1, 10),
            (string)random_int(1, 10)
        );
        $secondItem->setCategoryId(random_int(15, 20));
        $items = collect([$firstItem, $secondItem]);

        $this->strategy->expects($this->exactly(2))->method('getCategoryId')->willReturn($categoryId);
        $this->order->expects($this->once())->method('getItems')->willReturn($items);

        $this->assertEquals(collect([$firstItem]), $this->invokeMethod($this->strategy, 'getEligibleItems'));
    }
}
