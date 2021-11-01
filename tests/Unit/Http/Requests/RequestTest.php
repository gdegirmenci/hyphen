<?php

namespace Tests\Unit\Http\Requests;

use App\Http\Requests\API\GetDiscountRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

/**
 * Class RequestTest
 * @package Tests\Unit\Http\Requests
 * @coversDefaultClass \App\Http\Requests\Request
 */
class RequestTest extends TestCase
{
    const HTTP_UNPROCESSABLE_ENTITY = 422;

    /**
     * @test
     * @covers ::authorize
     */
    function it_should_return_true()
    {
        $request = new GetDiscountRequest();

        $this->assertTrue($request->authorize());
    }

    /**
     * @test
     * @covers ::failedValidation
     */
    function it_should_throw_an_exception()
    {
        /** @var Validator|MockObject $validator */
        $validator = $this->createMock(Validator::class);
        $request = new GetDiscountRequest();

        $this->expectException(HttpResponseException::class);

        $validator->expects($this->once())->method('errors');

        $this->invokeMethod($request, 'failedValidation', [$validator]);
    }
}
