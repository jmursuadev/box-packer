<?php

namespace Jmursuadev\BoxPacker;

use Jmursuadev\BoxPacker\Helpers\Formula;

abstract class BoxBase extends AbstractDimensionBase
{
    public function productCanFit(Product $product)
    {
        return Formula::itemCanFitInTheBox(
            $product->getLength(),
            $product->getWidth(),
            $product->getHeight(),
            $this->getLength(),
            $this->getWidth(),
            $this->getHeight()
        );
    }

    public function _getPossibleQuantity(Product $product, $boxVolume = 0, $boxWeight = 0): int
    {
        return min(floor(min(
            $boxVolume / $product->getVolume(),
            $boxWeight / $product->getWeight()
        )), $product->getQuantity());
    }

    abstract public function getPossibleQuantity(Product $product);
}
