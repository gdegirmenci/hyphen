<?php

namespace Tests\Unit\Strategies\Discount\Strategies;

use App\Entities\Order;
use App\Strategies\Discount\Strategies\DiscountStrategyInterface;
use App\Strategies\Discount\Strategies\TotalPriceBasedDiscountStrategy;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

/**
 * Class TotalPriceBasedDiscountStrategyTest
 * @package Tests\Unit\Strategies\Discount\Strategies
 * @coversDefaultClass \App\Strategies\Discount\Strategies\TotalPriceBasedDiscountStrategy
 */
class TotalPriceBasedDiscountStrategyTest extends TestCase
{
    use WithFaker;

    /**
     * @var Order|MockObject
     */
    private $order;

    /**
     * @var TotalPriceBasedDiscountStrategy|DiscountStrategyInterface|MockObject
     */
    private $strategy;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->order = $this->createMock(Order::class);
        $this->strategy = new TotalPriceBasedDiscountStrategy($this->order);
        parent::setUp();
    }

    /**
     * @param array $methods
     * @return void
     */
    public function setMockStrategy(array $methods = []): void
    {
        $this->strategy = $this->getMockBuilder(TotalPriceBasedDiscountStrategy::class)
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
        $this->setMockStrategy(['applyDiscount', 'getDiscount']);
        $totalPrice = (float)random_int(1, 10);
        $discountRatio = random_int(1, 10);
        $discountedPrice = (float)random_int(1, 10);

        $this->order->expects($this->once())->method('getTotalPrice')->willReturn($totalPrice);
        $this->strategy->expects($this->once())->method('getDiscount')->willReturn($discountRatio);
        $this->strategy
            ->expects($this->once())
            ->method('applyDiscount')
            ->with($totalPrice, $discountRatio)
            ->willReturn($discountedPrice);

        $this->assertEquals($this->order, $this->strategy->calculateDiscount());
    }

    /**
     * @test
     * @covers ::isEligibleToDiscount
     */
    function it_should_return_true_when_total_price_is_greater_than_threshold()
    {
        $this->setMockStrategy(['getDiscountThreshold']);
        $totalPrice = (float)random_int(11, 20);
        $discountThreshold = random_int(1, 10);

        $this->order->expects($this->once())->method('getTotalPrice')->willReturn($totalPrice);
        $this->strategy->expects($this->once())->method('getDiscountThreshold')->willReturn($discountThreshold);

        $this->assertTrue($this->strategy->isEligibleToDiscount());
    }

    /**
     * @test
     * @covers ::isEligibleToDiscount
     */
    function it_should_return_false_when_total_price_is_not_greater_than_threshold()
    {
        $this->setMockStrategy(['getDiscountThreshold']);
        $totalPrice = (float)random_int(1, 10);
        $discountThreshold = random_int(11, 20);

        $this->order->expects($this->once())->method('getTotalPrice')->willReturn($totalPrice);
        $this->strategy->expects($this->once())->method('getDiscountThreshold')->willReturn($discountThreshold);

        $this->assertFalse($this->strategy->isEligibleToDiscount());
    }

    /**
     * @test
     * @covers ::getDiscountThreshold
     */
    function it_should_return_discount_threshold()
    {
        $this->assertEquals(config('discount.total_price_based.threshold'), $this->strategy->getDiscountThreshold());
    }

    /**
     * @test
     * @covers ::getDiscount
     */
    function it_should_return_discount()
    {
        $this->assertEquals(config('discount.total_price_based.discount'), $this->strategy->getDiscount());
    }
}
