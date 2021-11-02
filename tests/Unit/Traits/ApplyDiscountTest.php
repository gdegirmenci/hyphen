<?php

namespace Tests\Unit\Traits;

use App\Traits\ApplyDiscount;
use Tests\TestCase;

/**
 * Class ApplyDiscountTest
 * @package Tests\Unit\Traits
 * @coversDefaultClass \App\Traits\ApplyDiscount
 */
class ApplyDiscountTest extends TestCase
{
    use ApplyDiscount;

    /**
     * @test
     * @covers ::applyDiscount
     */
    function it_should_return_percentage_of_total_price()
    {
        $totalPrice = random_int(50, 100);
        $discountPercentage = random_int(1, 10);
        $expectedPrice = $totalPrice - ($totalPrice * $discountPercentage / 100);

        $this->assertEquals($expectedPrice, $this->applyDiscount($totalPrice, $discountPercentage));
    }
}
