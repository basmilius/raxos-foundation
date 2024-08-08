<?php
declare(strict_types=1);

namespace Raxos\Foundation\Util;

use JetBrains\PhpStorm\Pure;
use function abs;
use function ceil;
use function floor;
use function max;
use function min;
use function round;

/**
 * Class MathUtil
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Util
 * @since 1.0.0
 */
final class MathUtil
{

    /**
     * Clamps the given value between the given min and max values.
     *
     * @param float|int $value
     * @param float|int $min
     * @param float|int $max
     *
     * @return float|int
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    #[Pure]
    public static function clamp(float|int $value, float|int $min, float|int $max): float|int
    {
        return max($min, min($max, $value));
    }

    /**
     * Rounds the given value up to the nearest multiple of the given step.
     *
     * @param float|int $value
     * @param float|int $step
     *
     * @return float|int
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    #[Pure]
    public static function ceilStep(float|int $value, float|int $step = 1): float|int
    {
        return ceil($value / $step) * $step;
    }

    /**
     * Rounds the given value down to the nearest multiple of the given step.
     *
     * @param float|int $value
     * @param float|int $step
     *
     * @return float|int
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    #[Pure]
    public static function floorStep(float|int $value, float|int $step = 1): float|int
    {
        return floor($value / $step) * $step;
    }

    /**
     * Rounds the given value to the nearest multiple of the given step.
     *
     * @param float|int $value
     * @param float|int $step
     *
     * @return float|int
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    #[Pure]
    public static function roundStep(float|int $value, float|int $step = 1): float|int
    {
        return round($value / $step) * $step;
    }

    /**
     * Calculates the greatest common divisor of the given integers.
     *
     * @param int $a
     * @param int $b
     *
     * @return int
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    #[Pure]
    public static function greatestCommonDivisor(int $a, int $b): int
    {
        $a = (int)abs($a);
        $b = (int)abs($b);

        if ($a < $b) {
            [$b, $a] = [$a, $b];
        }

        if ($b === 0) {
            return $a;
        }

        $r = $a % $b;

        while ($r > 0) {
            $a = $b;
            $b = $r;
            $r = $a % $b;
        }

        return $b;
    }

    /**
     * Simplifies the given fraction.
     *
     * @param int $n
     * @param int $d
     *
     * @return int[]
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    #[Pure]
    public static function simplifyFraction(int $n, int $d): array
    {
        $gcd = self::greatestCommonDivisor($n, $d);

        return [
            $n / $gcd,
            $d / $gcd
        ];
    }

}
