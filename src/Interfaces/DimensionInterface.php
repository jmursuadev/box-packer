<?php

namespace Jmursuadev\BoxPacker\Interfaces;

interface DimensionInterface
{
    public function setLength($length): self;

    public function setWidth($width): self;

    public function setHeight($height): self;

    public function setWeight($weight): self;

    public function getLength(): float;

    public function getWidth(): float;

    public function getHeight(): float;

    public function getWeight(): float;
}
