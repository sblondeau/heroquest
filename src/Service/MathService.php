<?php

namespace App\Service;

class MathService
{
    /**
     * Get angle beetwen two coordinates
     */
    public static function angle(float $fromX, float $fromY, float $toX, float $toY): float
    {
        $xDistance = $toX - $fromX;
        $yDistance = $fromY - $toY;
        $additionalAngle = 0;
        if ($xDistance == 0 && $yDistance < 0) {
            return 3 * M_PI_2;
        }
        if ($xDistance == 0 && $yDistance > 0) {
            return M_PI_2;
        }
        if ($xDistance == 0 && $yDistance == 0) {
            return 0;
        }
        if ($xDistance > 0 && $yDistance == 0) {
            return 0;
        }
        if ($xDistance < 0 && $yDistance == 0) {
            return M_PI;
        }
        if ($xDistance < 0 && $yDistance > 0) {
            $additionalAngle = 1;
        }
        if ($xDistance < 0 && $yDistance < 0) {
            $additionalAngle = 2;
        }
        if ($xDistance > 0 && $yDistance < 0) {
            $additionalAngle = 3;
        }
        // TODO VERIF ANGLES
        return atan(abs($yDistance / $xDistance))  + $additionalAngle * M_PI_2;
    }

    /**
     * Get distance beetween coordinates
     */
    public static function distance(float $fromX, float $fromY, float $toX, float $toY): float
    {
        $xDistance = ($fromX - $toX) ** 2;
        $yDistance = ($fromY - $toY) ** 2;

        return sqrt($xDistance + $yDistance);
    }
}
