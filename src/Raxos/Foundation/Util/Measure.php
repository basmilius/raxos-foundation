<?php
declare(strict_types=1);

namespace Raxos\Foundation\Util;

use function array_column;
use function array_map;
use function array_merge;
use function array_sum;
use function implode;

/**
 * Class Measure
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Util
 * @since 1.0.0
 */
class Measure
{

    protected int $current = -1;
    protected array $ram;
    protected array $results = [];

    /**
     * Measure constructor.
     *
     * @param callable $fn
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    protected function __construct(callable $fn)
    {
        $this->ram = Debug::ramUsage();

        $fn($this);
    }

    /**
     * Measures the given function.
     *
     * @param string $description
     * @param callable $fn
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function do(string $description, callable $fn): void
    {
        $this->results[++$this->current] = [
            'description' => $description,
            'time' => null,
            'logs' => []
        ];

        Stopwatch::measure($time, $fn);

        $this->results[$this->current]['time'] = $time;
    }

    /**
     * Logs the given data to the current job.
     *
     * @param mixed $data
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function log(mixed $data): void
    {
        $this->results[$this->current]['logs'][] = $data;
    }

    /**
     * Prints the results.
     *
     * @param array $results
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function printReport(array $results = []): void
    {
        Debug::print(array_merge([
            'time' => $this->formatTime(array_sum(array_column($this->results, 'time'))),
            'ram' => Debug::ramUsage(),
            'ram_before' => $this->ram,
            'results' => array_map(fn(array $result) => array_merge($result, [
                'time' => $this->formatTime($result['time'])
            ]), $this->results)
        ], $results));
    }

    /**
     * Formats the given time in nanoseconds to a string representation.
     *
     * @param float $time
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    private function formatTime(float $time): string
    {
        return implode(' | ', [
            $time . 'ns',
            ($time / 1e3) . 'μs',
            ($time / 1e6) . 'ms',
            ($time / 1e9) . 's'
        ]);
    }

    /**
     * Measures the given function.
     *
     * @param callable $fn
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function run(callable $fn): void
    {
        new static($fn);
    }

}
