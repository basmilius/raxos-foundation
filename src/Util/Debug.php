<?php
declare(strict_types=1);

namespace Raxos\Foundation\Util;

use JsonException;
use function count;
use function header;
use function headers_list;
use function headers_sent;
use function htmlspecialchars;
use function json_encode;
use function memory_get_peak_usage;
use function memory_get_usage;
use function Raxos\Foundation\isCommandLineInterface;
use function sprintf;
use function str_contains;
use function strtolower;
use const JSON_HEX_AMP;
use const JSON_HEX_APOS;
use const JSON_HEX_QUOT;
use const JSON_HEX_TAG;
use const JSON_THROW_ON_ERROR;

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
        self::rawPrint(var_dump(...), ...$data);
    }

    /**
     * Dumps the given data and stops executing.
     *
     * @param mixed ...$data
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function dumpDie(mixed ...$data): never
    {
        self::rawPrint(var_dump(...), ...$data);
        die(1);
    }

    /**
     * Prints the given data as JSON.
     *
     * @param mixed ...$data
     *
     * @return void
     * @author Bas Milius <bas@mili.us>
     * @since 1.7.0
     */
    public static function json(mixed ...$data): void
    {
        if (!headers_sent()) {
            header('Content-Type: application/json');
        }

        if (count($data) === 1) {
            $data = $data[0];
        }

        try {
            echo json_encode($data, JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_THROW_ON_ERROR);
        } catch (JsonException $err) {
            Debug::print($err);
        }
    }

    /**
     * Prints the given data as JSON and stops executing.
     *
     * @param mixed ...$data
     *
     * @return never
     * @author Bas Milius <bas@mili.us>
     * @since 1.7.0
     */
    public static function jsonDie(mixed ...$data): never
    {
        self::json(...$data);
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
        self::rawPrint(print_r(...), ...$data);
    }

    /**
     * Prints the given data and stops executing.
     *
     * @param mixed ...$data
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function printDie(mixed ...$data): never
    {
        self::rawPrint(print_r(...), ...$data);
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
    public static function runDie(callable $fn): never
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
        $isPlaintext = isCommandLineInterface();

        if (!$isPlaintext) {
            if (!headers_sent()) {
                header('access-control-allow-origin: *');
                header('content-type: text/plain');
            }

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
            echo htmlspecialchars($fn($data, true));
            echo '</pre>';
        } else {
            $fn($data);
        }
    }

}
