<?php

namespace Tests\Unit\Repositories\Product;

use App\Models\Product;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\ProductRepositoryInterface;
use Mockery;
use Mockery\Mock;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * Class ProductRepositoryTest
 * @package Tests\Unit\Repositories\Product
 * @coversDefaultClass \App\Repositories\Product\ProductRepository
 */
class ProductRepositoryTest extends TestCase
{
    use WithFaker;

    /** @var Product|Mock */
    private $product;
    /** @var ProductRepositoryInterface */
    private $productRepository;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->product = Mockery::mock(Product::class)->makePartial();
        $this->productRepository = new ProductRepository($this->product);
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getProductById
     */
    function it_should_return_product_by_id()
    {
        $id = random_int(1, 10);

        $this->product
            ->shouldReceive('where')
            ->once()
            ->with(compact('id'))
            ->andReturnSelf();
        $this->product
            ->shouldReceive('first')
            ->once()
            ->andReturnSelf();

        $this->assertEquals($this->product, $this->productRepository->getProductById($id));
    }

    /**
     * @test
     * @covers ::getProductById
     */
    function it_should_return_null_when_there_is_no_product_by_product_by_id()
    {
        $id = random_int(1, 10);

        $this->product
            ->shouldReceive('where')
            ->once()
            ->with(compact('id'))
            ->andReturnSelf();
        $this->product
            ->shouldReceive('first')
            ->once()
            ->andReturn(null);

        $this->assertNull($this->productRepository->getProductById($id));
    }
}
