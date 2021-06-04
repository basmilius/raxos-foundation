<?php
declare(strict_types=1);

namespace Raxos\Foundation\Util;

use JetBrains\PhpStorm\NoReturn;
use Raxos\Foundation\Environment;
use function count;
use function headers_list;
use function memory_get_peak_usage;
use function memory_get_usage;
use function sprintf;
use function str_contains;
use function strtolower;

/**
 * Class Debug
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Util
 * @since 1.0.0
 */
final class Debug
{

    /**
     * Dumps the given data.
     *
     * @param mixed ...$data
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function dump(mixed ...$data): void
    {
        self::rawPrint('var_dump', ...$data);
    }

    /**
     * Dumps the given data and stops executing.
     *
     * @param mixed ...$data
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    #[NoReturn]
    public static function dumpDie(mixed ...$data): void
    {
        self::rawPrint('var_dump', ...$data);
        die(1);
    }

    /**
     * Prints the given data.
     *
     * @param mixed ...$data
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function print(mixed ...$data): void
    {
        self::rawPrint('print_r', ...$data);
    }

    /**
     * Prints the given data and stops executing.
     *
     * @param mixed ...$data
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    #[NoReturn]
    public static function printDie(mixed ...$data): void
    {
        self::rawPrint('print_r', ...$data);
        die(1);
    }

    /**
     * Returns the ram usage as an array of strings.
     *
     * @return array
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function ramUsage(): array
    {
        return [
            sprintf('RAM Usage: %.5f MiB', memory_get_usage() / 1024 / 1024),
            sprintf('RAM Peak:  %.5f MiB', memory_get_peak_usage() / 1024 / 1024)
        ];
    }

    /**
     * Runs the given function and stops executing after.
     *
     * @param callable $fn
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    #[NoReturn]
    public static function runDie(callable $fn): void
    {
        $fn();

        die;
    }

    /**
     * Calls the given function with the given data.
     *
     * @param callable $fn
     * @param mixed ...$data
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    private static function rawPrint(callable $fn, mixed ...$data): void
    {
        $isPlaintext = Environment::isCommandLineInterface();

        if (!$isPlaintext) {
            $headers = headers_list();

            foreach ($headers as $header) {
                $header = strtolower($header);

                if (!str_contains($header, 'content-type')) {
                    continue;
                }

                if (!str_contains($header, 'text/plain')) {
                    continue;
                }

                $isPlaintext = true;
            }
        }

        if (count($data) === 1) {
            $data = $data[0];
        }

        if (!$isPlaintext) {
            echo '<pre>';
            $fn($data);
            echo '</pre>';
        } else {
            $fn($data);
        }
    }

}
