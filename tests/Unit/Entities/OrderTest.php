<?php

namespace Tests\Unit\Entities;


use App\Entities\Order;
use App\ValueObjects\Item;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

/**
 * Class OrderTest
 * @package Tests\Unit\Entities
 * @coversDefaultClass \App\Entities\Order
 */
class OrderTest extends TestCase
{
    use WithFaker;

    /**
     * @param array $methods
     * @return Order|MockObject
     */
    public function getMockEntity(array $methods = []): MockObject
    {
        return $this->getMockBuilder(Order::class)
            ->disableOriginalConstructor()
            ->onlyMethods($methods)
            ->getMock();
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getOrderId
     */
    function it_should_return_order_id()
    {
        $orderId = (string)random_int(1, 10);
        $order = new Order($orderId, (string)random_int(1, 10), [], (string)random_int(1, 10));

        $this->assertEquals($orderId, $order->getOrderId());
    }

    /**
     * @test
     * @covers ::getCustomerId
     */
    function it_should_return_customer_id()
    {
        $customerId = (string)random_int(1, 10);
        $order = new Order((string)random_int(1, 10), $customerId, [], (string)random_int(1, 10));

        $this->assertEquals($customerId, $order->getCustomerId());
    }

    /**
     * @test
     * @covers ::getItems
     */
    function it_should_return_items()
    {
        $items = [$this->faker->word => $this->faker->word];
        $order = new Order((string)random_int(1, 10), (string)random_int(1, 10), $items, (string)random_int(1, 10));

        $this->assertEquals(collect($items), $order->getItems());
    }

    /**
     * @test
     * @covers ::setItems
     */
    function it_should_set_items()
    {
        $items = [$this->faker->word => $this->faker->word];
        $order = new Order((string)random_int(1, 10), (string)random_int(1, 10), [], (string)random_int(1, 10));

        $order->setItems($items);

        $this->assertEquals(collect($items), $order->getItems());
    }

    /**
     * @test
     * @covers ::getTotalPrice
     */
    function it_should_return_total_price()
    {
        $totalPrice = (string)random_int(1, 10);
        $order = new Order((string)random_int(1, 10), (string)random_int(1, 10), [], $totalPrice);

        $this->assertEquals($totalPrice, $order->getTotalPrice());
    }

    /**
     * @test
     * @covers ::setTotalPrice
     */
    function it_should_set_total_price()
    {
        $oldTotalPrice = (string)random_int(1, 10);
        $newTotalPrice = (string)random_int(11, 20);
        $order = new Order((string)random_int(1, 10), (string)random_int(1, 10), [], $oldTotalPrice);

        $order->setTotalPrice($newTotalPrice);

        $this->assertEquals($newTotalPrice, $order->getTotalPrice());
    }


    /**
     * @test
     * @covers ::updateTotalPrice
     */
    function it_should_update_total_price()
    {
        $entityMock = $this->getMockEntity(['getItems', 'setTotalPrice']);
        $firstItemPrice = random_int(1, 10);
        $secondItemPrice = random_int(1, 10);
        $firstItem = new Item(
            (string)random_int(1, 10),
            (string)random_int(1, 10),
            (string)random_int(1, 10),
            (string)$firstItemPrice,
        );
        $secondItem = new Item(
            (string)random_int(1, 10),
            (string)random_int(1, 10),
            (string)random_int(1, 10),
            (string)$secondItemPrice,
        );
        $expectedTotalPrice = (float)$firstItemPrice + (float)$secondItemPrice;
        $items = collect([$firstItem, $secondItem]);

        $entityMock->expects($this->once())->method('getItems')->willReturn($items);
        $entityMock->expects($this->once())->method('setTotalPrice')->with($expectedTotalPrice);

        $this->invokeMethod($entityMock, 'updateTotalPrice');
    }

    /**
     * @test
     * @covers ::toArray
     */
    function it_should_return_to_array()
    {
        $orderId = (string)random_int(1, 10);
        $customerId = (string)random_int(1, 10);
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
        $items = [$firstItem, $secondItem];
        $totalPrice = (string)random_int(1, 10);
        $order = new Order($orderId, $customerId, $items, $totalPrice);
        $expectedResult = [
            'id' => $order->getOrderId(),
            'customer-id' => $order->getCustomerId(),
            'items' => $order->getItems()->transform(function (Item $item) {
                return $item->toArray();
            }),
            'total' => $order->getTotalPrice(),
        ];

        $this->assertEquals($expectedResult, $order->toArray());
    }
}
