<?php

namespace Tests\Unit\Http\Requests\API;

use App\Entities\Order;
use App\Http\Requests\API\GetDiscountRequest;
use App\Http\Requests\Request;
use App\ValueObjects\Item;
use Tests\Suites\RequestTestSuite;

/**
 * Class GetDiscountRequestTest
 * @package Tests\Unit\Http\Requests\API
 * @coversDefaultClass \App\Http\Requests\API\GetDiscountRequest
 */
class GetDiscountRequestTest extends RequestTestSuite
{
    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return new GetDiscountRequest();
    }


    /**
     * @return array
     */
    public function rulesDataProvider(): array
    {
        return [
            ['id', 'required|string'],
            ['customer-id', 'required|string'],
            ['items', 'required|array'],
            ['total', 'required|string'],
        ];
    }

    /**
     * @test
     * @covers ::rules
     * @dataProvider rulesDataProvider
     * @param string $field
     * @param string $rule
     */
    function it_should_validate_rules(string $field, string $rule)
    {
        $this->assertSame($rule, $this->getRules()[$field]);
    }

    /**
     * @test
     * @covers ::rules
     */
    function it_should_assert_count_validation_rules()
    {
        $this->assertCount(count($this->rulesDataProvider()), $this->getRules());
    }

    /**
     * @test
     * @covers ::getOrder
     * @covers ::getItems
     */
    function it_should_return_order()
    {
        $itemPayload = [
            'product-id' => (string)random_int(1, 10),
            'quantity' => (string)random_int(1, 10),
            'unit-price' => (string)random_int(1, 10),
            'total' => (string)random_int(1, 10),
        ];
        $item = new Item(
            $itemPayload['product-id'],
            $itemPayload['quantity'],
            $itemPayload['unit-price'],
            $itemPayload['total']
        );
        $request = new GetDiscountRequest([
            'id' => random_int(1, 10),
            'customer-id' => random_int(1, 10),
            'items' => [$itemPayload],
            'total' => random_int(1, 10),
        ]);
        $order = new Order($request->get('id'), $request->get('customer-id'), [$item], (float)$request->get('total'));

        $this->assertEquals($order, $request->getOrder());
    }
}
