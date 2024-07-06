<?php

namespace Jmursuadev\BoxPacker;

class Box extends BoxBase
{
    protected $name;

    public function __construct($name, $length, $width, $height, $weight)
    {
        $this->name = $name;
        $this->width = $width;
        $this->height = $height;
        $this->length = $length;
        $this->weight = $weight;
    }

    public function getPossibleQuantity(Product $product)
    {
        return parent::_getPossibleQuantity($product, $this->getVolume(), $this->getWeight());
    }

    public function canAddProduct(Product $product): bool
    {
        return $this->getPossibleQuantity($product) >= 1 && $this->productCanFit($product);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'width' => $this->width,
            'height' => $this->height,
            'length' => $this->length,
            'weight' => $this->weight,
        ];
    }
}
