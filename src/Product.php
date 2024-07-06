<?php

namespace Jmursuadev\BoxPacker;

class Product extends AbstractDimensionBase
{

    protected int $quantity;

    public function __construct($length, $width, $height, $weight, $quantity = 1)
    {
        $this->length = $length;
        $this->width = $width;
        $this->height = $height;
        $this->weight = $weight;
        $this->quantity = $quantity;
    }

    public static function fromArray($data): self
    {
        return new self(
            $data['length'],
            $data['width'],
            $data['height'],
            $data['weight'],
            $data['quantity'] ?? 1
        );
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function decreaseQuantity(int $int)
    {
        $this->setQuantity($this->quantity - $int);
    }

    public function getWeightByQuantity(): int
    {
        return $this->weight * $this->quantity;
    }

    public function getVolumeByQuantity(): int
    {
        return $this->getVolume() * $this->quantity;
    }

    public function toArray(): array
    {
        return [
            'width' => $this->width,
            'height' => $this->height,
            'length' => $this->length,
            'weight' => $this->weight,
            'quantity' => $this->quantity,
        ];
    }
}
