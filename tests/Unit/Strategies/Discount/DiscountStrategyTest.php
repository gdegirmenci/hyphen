<?php

namespace Tests\Unit\Strategies\Discount;

use App\Entities\Order;
use App\Strategies\Discount\DiscountStrategy;
use App\Strategies\Discount\Strategies\CategoryBasedDiscountStrategy;
use App\Strategies\Discount\Strategies\ProductCountBasedDiscountStrategy;
use App\Strategies\Discount\Strategies\TotalPriceBasedDiscountStrategy;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

/**
 * Class DiscountStrategyTest
 * @package Tests\Unit\Strategies\Discount
 * @coversDefaultClass \App\Strategies\Discount\DiscountStrategy
 */
class DiscountStrategyTest extends TestCase
{
    const STRATEGIES = [
        ProductCountBasedDiscountStrategy::class,
        CategoryBasedDiscountStrategy::class,
        TotalPriceBasedDiscountStrategy::class,
    ];

    /**
     * @var Order|MockObject
     */
    private $order;

    /**
     * @var DiscountStrategy|MockObject
     */
    private $strategy;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->order = $this->createMock(Order::class);
        $this->strategy = new DiscountStrategy($this->order);
    }

    /**
     * @param array $methods
     * @return void
     */
    public function setMockStrategy(array $methods = []): void
    {
        $this->strategy = $this->getMockBuilder(DiscountStrategy::class)
            ->setConstructorArgs([$this->order])
            ->onlyMethods($methods)
            ->getMock();
    }

    /**
     * @return array
     */
    public function strategyDataProvider(): array
    {
        return [
            [TotalPriceBasedDiscountStrategy::class],
            [ProductCountBasedDiscountStrategy::class],
            [CategoryBasedDiscountStrategy::class],
        ];
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::calculateDiscount
     */
    function it_should_return_self_and_calculate_discount()
    {
        /** @var TotalPriceBasedDiscountStrategy|MockObject $totalPriceBasedDiscountStrategy */
        $totalPriceBasedDiscountStrategy = $this->createMock(TotalPriceBasedDiscountStrategy::class);
        /** @var Order|MockObject $order */
        $order = $this->createMock(Order::class);
        $strategies = collect([TotalPriceBasedDiscountStrategy::class]);
        $this->setMockStrategy(['getStrategies', 'getStrategy', 'setOrder']);

        $this->strategy
            ->expects($this->once())
            ->method('getStrategies')
            ->willReturn($strategies);
        $this->strategy
            ->expects($this->once())
            ->method('getStrategy')
            ->with(TotalPriceBasedDiscountStrategy::class)
            ->willReturn($totalPriceBasedDiscountStrategy);
        $totalPriceBasedDiscountStrategy->expects($this->once())->method('isEligibleToDiscount')->willReturn(true);
        $totalPriceBasedDiscountStrategy->expects($this->once())->method('calculateDiscount')->willReturn($order);
        $this->strategy->expects($this->once())->method('setOrder')->with($order);

        $this->assertEquals($this->strategy, $this->strategy->calculateDiscount());
    }

    /**
     * @test
     * @covers ::getOrder
     */
    function it_should_return_order()
    {
        /** @var Order|MockObject $order */
        $order = $this->createMock(Order::class);

        $this->strategy->setOrder($order);

        $this->assertEquals($order, $this->strategy->getOrder());
    }

    /**
     * @test
     * @covers ::setOrder
     */
    function it_should_set_order()
    {
        /** @var Order|MockObject $order */
        $order = $this->createMock(Order::class);

        $this->strategy->setOrder($order);

        $this->assertEquals($order, $this->strategy->getOrder());
    }

    /**
     * @test
     * @covers ::getStrategies
     */
    function it_should_return_strategies()
    {
        $this->assertEquals(collect(self::STRATEGIES), $this->invokeMethod($this->strategy, 'getStrategies'));
    }

    /**
     * @test
     * @param string $strategyClass
     * @dataProvider strategyDataProvider
     */
    function it_should_return_strategy(string $strategyClass)
    {
        $this->assertEquals(
            new $strategyClass($this->order),
            $this->invokeMethod($this->strategy, 'getStrategy', [$strategyClass])
        );
    }
}
