<?php
declare(strict_types=1);

namespace Raxos\Foundation\Error;

use JsonSerializable;
use function base_convert;
use function debug_backtrace;
use function hash;
use const DEBUG_BACKTRACE_IGNORE_ARGS;

/**
 * Class ExceptionId
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Error
 * @since 1.0.17
 */
final readonly class ExceptionId implements JsonSerializable
{

    /**
     * ExceptionId constructor.
     *
     * @param int $value
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.17
     */
    public function __construct(
        public int $value
    ) {}

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.17
     */
    public function jsonSerialize(): int
    {
        return $this->value;
    }

    /**
     * Returns a unique exception id for the given method.
     *
     * @param string $methodName
     *
     * @return self
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.17
     */
    public static function for(string $methodName): self
    {
        $id = (int)base_convert(hash('crc32', $methodName), 16, 10);

        return new self($id);
    }

    /**
     * Returns a unique exception id based on the backtrace.
     *
     * @return self
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public static function guess(): self
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $entry = $backtrace[1] ?? $backtrace[0];

        if (isset($entry['class'])) {
            $fn = $entry['class'] . $entry['type'] . $entry['function'];
        } else {
            $fn = $entry['function'];
        }

        return self::for($fn);
    }

}
