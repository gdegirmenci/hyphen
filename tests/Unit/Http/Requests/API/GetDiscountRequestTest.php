<?php

namespace Tests\Unit\Http\Requests\API;

use App\Http\Requests\API\GetDiscountRequest;
use App\Http\Requests\Request;
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
     * @test
     * @covers ::rules
     * @dataProvider rulesProvider
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
        $this->assertCount(count($this->rulesProvider()), $this->getRules());
    }

    /**
     * @return array
     */
    public function rulesProvider(): array
    {
        return [
            ['id', 'required|string'],
            ['customer-id', 'required|string'],
            ['items', 'required|array'],
            ['total', 'required|string'],
        ];
    }
}
