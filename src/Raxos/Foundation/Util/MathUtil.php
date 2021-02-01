<?php
declare(strict_types=1);

namespace Raxos\Foundation\Util;

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
    public static function roundStep(float|int $value, float|int $step = 1): float|int
    {
        return round($value / $step) * $step;
    }

}
