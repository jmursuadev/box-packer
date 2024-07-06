<?php

namespace Jmursuadev\BoxPacker;

use Jmursuadev\BoxPacker\Helpers\Formula;
use Jmursuadev\BoxPacker\Interfaces\DimensionInterface;

abstract class AbstractDimensionBase implements DimensionInterface
{
    protected $length;
    protected $width;
    protected $height;
    protected $weight;

    public function getLength(): float
    {
        return $this->length;
    }

    public function getWidth(): float
    {
        return $this->width;
    }

    public function getHeight(): float
    {
        return $this->height;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function getVolume(): float
    {
        return Formula::getVolume($this->length, $this->width, $this->height);
    }

    public function setLength($length): self
    {
        $this->length = $length;

        return $this;
    }

    public function setWidth($width): self
    {
        $this->width = $width;

        return $this;
    }

    public function setHeight($height): self
    {
        $this->height = $height;

        return $this;
    }

    public function setWeight($weight): self
    {
        $this->weight = $weight;

        return $this;
    }
}
