<?php
declare(strict_types=1);

namespace Raxos\Foundation\Util;

use JetBrains\PhpStorm\ExpectedValues;
use function hrtime;

/**
 * Class Stopwatch
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Util
 * @since 1.0.0
 */
final class Stopwatch
{

    public const NANOSECONDS = 1;
    public const MICROSECONDS = 2;
    public const MILLISECONDS = 4;
    public const SECONDS = 8;

    private static array $registry = [];

    /**
     * Measures the given function.
     *
     * @param float|null $time
     * @param callable|null $fn
     * @param int $unit
     *
     * @return mixed
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function measure(float &$time = null, callable $fn = null, #[ExpectedValues(valuesFromClass: self::class)] int $unit = self::NANOSECONDS): mixed
    {
        self::start('measure');
        $result = $fn();
        self::stop('measure', $time, $unit);

        return $result;
    }

    /**
     * Starts a stopwatch with the given id.
     *
     * @param string $id
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function start(string $id): void
    {
        self::$registry[$id] = hrtime(true);
    }

    /**
     * Stops the stopwatch with the given id.
     *
     * @param string $id
     * @param float|null $time
     * @param int $unit
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function stop(string $id, float &$time = null, #[ExpectedValues(valuesFromClass: self::class)] int $unit = self::NANOSECONDS): void
    {
        $startTime = self::$registry[$id] ?? 0.0;
        $stopTime = hrtime(true);
        $time = $stopTime - $startTime;

        if ($unit === self::NANOSECONDS) {
            return;
        }

        match ($unit) {
            self::MICROSECONDS => $time *= 1e-3,
            self::MILLISECONDS => $time *= 1e-6,
            self::SECONDS => $time *= 1e-9
        };
    }

}
