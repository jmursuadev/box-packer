<?php

namespace Jmursuadev\BoxPacker;

use Illuminate\Support\Collection;

class AllocatedBox extends BoxBase
{
    protected $box;
    protected $products;

    public function __construct($box, $products)
    {
        $this->box = $box;
        $this->products = $products;
    }

    public function getBox(): Box
    {
        return $this->box;
    }

    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function getProductsQuantity(): int
    {
        return $this->products->sum(function ($product) {
            return $product->getQuantity();
        });
    }

    public function getProductsWeight(): int
    {
        return $this->products->sum(function ($product) {
            return $product->getWeightByQuantity();
        });
    }

    public function getProductsVolume(): int
    {
        return $this->products->sum(function ($product) {
            return $product->getVolumeByQuantity();
        });
    }

    public function getRemainingVolume(): int
    {
        return $this->box->getVolume() - $this->getProductsVolume();
    }

    public function getRemainingWeight(): int
    {
        return $this->box->getWeight() - $this->getProductsWeight();
    }

    public function addProduct(Product $product, $quantity): self
    {
        $this->products->push(new Product(
            $product->getLength(),
            $product->getWidth(),
            $product->getHeight(),
            $product->getWeight(),
            $quantity
        ));

        return $this;
    }

    public function getPossibleQuantity(Product $product): int
    {
        return parent::_getPossibleQuantity($product, $this->getRemainingVolume(), $this->getRemainingWeight());
    }

    public function canAddProduct(Product $product): bool
    {
        return $this->getPossibleQuantity($product) >= 1 && $this->productCanFit($product);
    }

    public function toArray(): array
    {
        return [
            'box' => $this->box->toArray(),
            'products' => $this->products->map(function ($product) {
                return $product->toArray();
            })->toArray()
        ];
    }
}
