<?php

namespace Tests\Unit\Services;

use App\Entities\Order;
use App\Models\Product;
use App\Repositories\Product\ProductRepository;
use App\Services\DiscountService;
use App\Strategies\Discount\DiscountStrategy;
use App\ValueObjects\Item;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\Suites\ServiceTestSuite;

/**
 * Class DiscountServiceTest
 * @package Tests\Unit\Services
 * @coversDefaultClass \App\Services\DiscountService
 */
class DiscountServiceTest extends ServiceTestSuite
{
    use WithFaker;

    /**
     * @var ProductRepository|MockObject
     */
    private $productRepository;

    /**
     * @var DiscountService|MockObject
     */
    private $service;

    /**
     * @return void
     */
    public function setService(): void
    {
        $this->productRepository = $this->createMock(ProductRepository::class);
        $this->service = new DiscountService($this->productRepository);
    }

    /**
     * @param array $methods
     * @return void
     */
    public function setServiceMock(array $methods = []): void
    {
        $this->service = $this->getMockBuilder(DiscountService::class)
            ->setConstructorArgs([$this->productRepository])
            ->onlyMethods($methods)
            ->getMock();
    }

    /**
     * @test
     * @covers ::getDiscount
     */
    function it_should_return_discount()
    {
        $this->setServiceMock(['getDiscountStrategy']);
        /** @var Product|Mockery $product */
        $product = Mockery::mock(Product::class)->makePartial();
        $product->category_id = random_int(1, 10);
        /** @var DiscountStrategy|MockObject $discountStrategy */
        $discountStrategy = $this->createMock(DiscountStrategy::class);
        /** @var Order|MockObject $order */
        $order = $this->createMock(Order::class);
        $productId = $this->faker->name;
        $quantity = (string)random_int(1, 10);
        $unitPrice = (string)random_int(1, 10);
        $totalPrice = (string)random_int(1, 10);
        $item = new Item($productId, $quantity, $unitPrice, $totalPrice);
        $items = collect([$item]);
        $discount = [$this->faker->word => $this->faker->word];

        $order->expects($this->once())->method('getItems')->willReturn($items);
        $this->productRepository
            ->expects($this->once())
            ->method('getProductById')
            ->with($productId)
            ->willReturn($product);
        $this->service
            ->expects($this->once())
            ->method('getDiscountStrategy')
            ->with($order)
            ->willReturn($discountStrategy);
        $discountStrategy->expects($this->once())
            ->method('calculateDiscount')
            ->willReturnSelf();
        $discountStrategy->expects($this->once())
            ->method('getOrder')
            ->willReturn($order);
        $order->expects($this->once())
            ->method('toArray')
            ->willReturn($discount);

        $this->assertEquals($discount, $this->service->getDiscount($order));
    }

    /**
     * @test
     * @covers ::getDiscountStrategy
     */
    function it_should_return_discount_strategy()
    {
        /** @var Order|MockObject $order */
        $order = $this->createMock(Order::class);

        $this->assertEquals(
            new DiscountStrategy($order),
            $this->invokeMethod($this->service, 'getDiscountStrategy', [$order])
        );
    }
}
