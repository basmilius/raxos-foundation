<?php
declare(strict_types=1);

namespace Raxos\Foundation\Util;

use JetBrains\PhpStorm\Pure;
use function hrtime;

/**
 * Class Stopwatch
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Util
 * @since 1.0.16
 */
final class Stopwatch
{

    private StopwatchState $state = StopwatchState::IDLE;
    private float $startTime = 0.0;
    private float $stopTime = 0.0;

    /**
     * Stopwatch constructor.
     *
     * @param string $description
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.16
     */
    public function __construct(
        public readonly string $description = 'Stopwatch'
    )
    {
    }

    /**
     * Returns the running time in the given unit.
     *
     * @param StopwatchUnit $unit
     *
     * @return float|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.16
     */
    #[Pure]
    public function as(StopwatchUnit $unit): ?float
    {
        if ($this->state !== StopwatchState::STOPPED) {
            return null;
        }

        $time = $this->stopTime - $this->startTime;

        return match ($unit) {
            StopwatchUnit::NANOSECONDS => $time,
            StopwatchUnit::MICROSECONDS => $time * 1e-3,
            StopwatchUnit::MILLISECONDS => $time * 1e-6,
            StopwatchUnit::SECONDS => $time * 1e-9
        };
    }

    /**
     * Formats the running time in the given unit.
     *
     * @param StopwatchUnit $unit
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.16
     */
    #[Pure]
    public function format(StopwatchUnit $unit = StopwatchUnit::NANOSECONDS): string
    {
        $time = $this->as($unit);

        if ($time === null) {
            return '-';
        }

        return match ($unit) {
            StopwatchUnit::NANOSECONDS => "{$time}ns",
            StopwatchUnit::MICROSECONDS => "{$time}μs",
            StopwatchUnit::MILLISECONDS => "{$time}ms",
            StopwatchUnit::SECONDS => "{$time}s"
        };
    }

    /**
     * Runs the given function.
     *
     * @template TResult
     *
     * @param callable():TResult $fn
     *
     * @return TResult
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.16
     */
    public function run(callable $fn): mixed
    {
        $this->start();
        $result = $fn();
        $this->stop();

        return $result;
    }

    /**
     * Starts the stopwatch.
     *
     * @return void
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.16
     */
    public function start(): void
    {
        $this->state = StopwatchState::RUNNING;
        $this->startTime = hrtime(true);
    }

    /**
     * Stops the stopwatch.
     *
     * @return void
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.16
     */
    public function stop(): void
    {
        $this->state = StopwatchState::STOPPED;
        $this->stopTime = hrtime(true);
    }

    /**
     * Measures the given function.
     *
     * @template TResult
     *
     * @param float $runningTime
     * @param callable():TResult $fn
     * @param StopwatchUnit $unit
     * @param string|null $description
     *
     * @return TResult
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.16
     */
    public static function measure(float &$runningTime, callable $fn, StopwatchUnit $unit, ?string $description = null): mixed
    {
        $stopwatch = new self($description);
        $result = $stopwatch->run($fn);
        $runningTime = $stopwatch->as($unit) ?? 0.0;

        return $result;
    }

}
