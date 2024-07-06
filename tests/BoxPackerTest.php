<?php

namespace Jmursuadev\BoxPacker\Tests;

use Jmursuadev\BoxPacker\BoxPacker;
use Jmursuadev\BoxPacker\Product;
use Jmursuadev\BoxPacker\BoxPackerServiceProvider;
use Orchestra\Testbench\TestCase;
use Throwable;

class BoxPackerTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [BoxPackerServiceProvider::class];
    }

    public function testProductsCanFitAll()
    {
        $products = collect([
            new Product(10, 10, 10, 5, 1),
            new Product(15, 10, 20, 5, 1),
            new Product(50, 50, 10, 50, 5),
        ]);

        $boxpacker = boxpacker()->setProducts($products)->pack();
        $boxes = $boxpacker->getAllocatedBoxes();

        $this->assertCount(7, $boxes);
    }

    public function testProductsQuantityIsEqualsToAllocatedBox()
    {
        $products = collect([
            new Product(10, 10, 10, 5, 1),
            new Product(15, 10, 20, 5, 1),
            new Product(50, 50, 10, 50, 5),
        ]);

        $productQuantityCount = $products->sum(function ($product) {
            return $product->getQuantity();
        });

        $boxpacker = boxpacker()->setProducts($products)->pack();
        $boxes = $boxpacker->getAllocatedBoxes();
        $productQuantityCountInBoxes = $boxes->sum(function ($box) {
            return $box->getProductsQuantity();
        });

        $this->assertEquals($productQuantityCount, $productQuantityCountInBoxes);
    }

    public function testProductsIsOverInDimension()
    {

        /**
         * Over in dimension
         */
        $this->assertThrows(function () {
            $products = collect([
                new Product(150, 1, 1, 10, 1)
            ]);
            boxpacker()->setProducts($products)->pack();
        }, Throwable::class, BoxPacker::ERROR_MESSAGE);
    }

    public function testProductsIsOverInWeight()
    {
        /**
         * Over in weight
         */
        $this->assertThrows(function () {
            $products = collect([
                new Product(10, 10, 10, 52, 10)
            ]);
            boxpacker()->setProducts($products)->pack();
        }, Throwable::class, BoxPacker::ERROR_MESSAGE);
    }
}
