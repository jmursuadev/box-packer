<?php

namespace Jmursuadev\BoxPacker\Helpers;


class Formula
{
    public static function getVolume($length, $width, $height): int
    {
        return $width * $height * $length;
    }

    public static function getVolumeByQuantity($length, $width, $height, $quantity): int
    {
        return self::getVolume($length, $width, $height) * $quantity;
    }

    public static function getWeightByQuantity($weight, $quantity): int
    {
        return $weight * $quantity;
    }

    public static function itemCanFitInTheBox($itemLength, $itemWidth, $itemHeight, $boxLength, $boxWidth, $boxHeight): bool
    {
        $orientations = [
            [$itemLength, $itemWidth, $itemHeight],
            [$itemLength, $itemHeight, $itemWidth],
            [$itemWidth, $itemLength, $itemHeight],
            [$itemWidth, $itemHeight, $itemLength],
            [$itemHeight, $itemLength, $itemWidth],
            [$itemHeight, $itemWidth, $itemLength],
        ];

        foreach ($orientations as $orientation) {
            if (
                $orientation[0] <= $boxLength &&
                $orientation[1] <= $boxWidth &&
                $orientation[2] <= $boxHeight
            ) {
                return true;
            }
        }

        return false;
    }
}
