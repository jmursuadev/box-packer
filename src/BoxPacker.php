<?php

namespace Jmursuadev\BoxPacker;

use Illuminate\Support\Collection;
use Jmursuadev\BoxPacker\Helpers\Formula;

class BoxPacker
{
    const ERROR_MESSAGE = 'No box can fit this product';

    protected $boxes;
    protected $products;
    protected $allocatedBoxes;

    public function __construct(Collection $boxes)
    {
        $this->boxes = $boxes->sortBy(function (Box $a) {
            return $a->getVolume();
        });
        $this->products = collect([]);
        $this->allocatedBoxes = collect([]);
    }

    public function getBoxes(): Collection
    {
        return $this->boxes;
    }

    public function canFitProducts(Box $box, Collection $products): bool
    {
        $productsVolume = $products->sum(function (Product $product) {
            return $product->getVolumeByQuantity();
        });
        $productsWeight = $products->sum(function (Product $product) {
            return $product->getWeightByQuantity();
        });
        $productsMaxLength = $products->max(function (Product $product) {
            return $product->getLength();
        });
        $productsMaxWidth = $products->max(function (Product $product) {
            return $product->getWidth();
        });
        $productsMaxHeight = $products->max(function (Product $product) {
            return $product->getHeight();
        });

        $boxMinDimension = min($box->getLength(), $box->getWidth(), $box->getHeight());
        $productMinDimension = $products->min(function (Product $product) {
            return min($product->getLength(), $product->getWidth(), $product->getHeight()) * $product->getQuantity();
        });

        return $box->getVolume() >= $productsVolume &&
            $box->getWeight() >= $productsWeight &&
            $boxMinDimension >= $productMinDimension &&
            Formula::itemCanFitInTheBox(
                $productsMaxLength,
                $productsMaxWidth,
                $productsMaxHeight,
                $box->getLength(),
                $box->getWidth(),
                $box->getHeight()
            );
    }



    public function getSmallestBoxForAllProducts(Collection $products): mixed
    {
        foreach ($this->boxes as $box) {
            if ($this->canFitProducts($box, $products)) {
                return $box;
            }
        }

        return null;
    }

    public function getSmallestBoxForProduct(Product $product): mixed
    {
        foreach ($this->boxes as $box) {
            if ($box->canAddProduct($product)) {
                return $box;
            }
        }

        self::throwCannotFitException();
    }

    public function allocateBox(Box $box, Collection $products): self
    {
        $this->allocatedBoxes->push(new AllocatedBox($box, $products));

        return $this;
    }

    public function getAllocatedBoxes(): Collection
    {
        return $this->allocatedBoxes;
    }

    public function generateBoxes()
    {
        return $this->boxes;
    }

    private function allocateBoxes(Collection $products)
    {
        $allocatedBox = null;

        foreach ($products as $key => $product) {
            $allocatedBox = $allocatedBox ?? new AllocatedBox($this->getSmallestBoxForProduct($product), collect([]));
            if ($allocatedBox && ($maxQuantity = $allocatedBox->getPossibleQuantity($product)) > 0) {
                // Add the product to the allocated box
                $allocatedBox->addProduct($product, $maxQuantity);

                // Decrease the quantity of the product
                $product->decreaseQuantity($maxQuantity);

                // Remove the product if quantity is 0
                if ($product->getQuantity() == 0) {
                    $products->forget($key);
                }
            }
        }

        // Allocate the box if there are products
        if ($allocatedBox) {
            $this->allocateBox($allocatedBox->getBox(), $allocatedBox->getProducts());
        }

        // If there are still products, run the function again
        if ($products->count() > 0) {
            return $this->allocateBoxes($products);
        }
    }

    public function pack(): self
    {
        if ($box = $this->getSmallestBoxForAllProducts($this->products)) {
            $this->allocateBox($box, $this->products);
            return $this;
        }

        // transform product to avoid mutation on original products
        $this->allocateBoxes($this->products->map(function ($product) {
            return new Product(
                $product->getLength(),
                $product->getWidth(),
                $product->getHeight(),
                $product->getWeight(),
                $product->getQuantity()
            );
        }));

        return $this;
    }

    public function getData(): array
    {
        return $this->allocatedBoxes->map(function (AllocatedBox $allocatedBox) {
            return $allocatedBox->toArray();
        })->toArray();
    }

    public static function throwCannotFitException()
    {
        throw new \Exception(self::ERROR_MESSAGE);
    }

    public function setBoxes(Collection $boxes): self
    {
        $this->boxes = $boxes;

        return $this;
    }

    public function setProducts(Collection $products): self
    {
        $this->products = $products->sortByDesc(function ($a) {
            return $a->getVolumeByQuantity();
        })->values();

        return $this;
    }
}
