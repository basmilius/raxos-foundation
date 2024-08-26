<?php
declare(strict_types=1);

namespace Raxos\Foundation\Option;

use Throwable;

/**
 * Interface OptionInterface
 *
 * @template TValue
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Option
 * @since 1.1.0
 */
interface OptionInterface
{

    /**
     * Filters the option, if the predicate results in FALSE,
     * {@see None} is returned.
     *
     * @param callable(TValue):bool $predicate
     *
     * @return Option<TValue>
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function filter(callable $predicate): Option;

    /**
     * Returns the value of the option or throws an exception
     * if there is none.
     *
     * @return TValue
     * @throws OptionException
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function get(): mixed;

    /**
     * Returns the value of the option or returns the given
     * fallback value.
     *
     * @template TFallbackValue
     *
     * @param TFallbackValue $fallback
     *
     * @return TFallbackValue|TValue
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function getOrElse(mixed $fallback): mixed;

    /**
     * Returns the value of the option or invokes the given
     * callable and returns the result instead.
     *
     * @template TFallbackValue
     *
     * @param callable():TFallbackValue $fn
     *
     * @return TFallbackValue|TValue
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function getOrInvoke(callable $fn): mixed;

    /**
     * Returns the value of the option or throws the given
     * exception instead.
     *
     * @template TException
     *
     * @param TException|callable():TException $err
     *
     * @return TValue
     * @throws TException
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function getOrThrow(Throwable|callable $err): mixed;

    /**
     * Maps the option to another option.
     *
     * @template TNewValue
     *
     * @param callable(TValue):TNewValue $map
     *
     * @return Option<TNewValue>
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function map(callable $map): Option;

    /**
     * Returns the option or the fallback option.
     *
     * @template TFallbackValue
     *
     * @param Option<TFallbackValue>|callable():TFallbackValue $fallback
     *
     * @return Option<TFallbackValue|TValue>
     * @throws OptionException
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function orElse(Option|callable $fallback): Option;

    /**
     * Returns the option or throws the given exception.
     *
     * @template TException
     *
     * @param TException|callable():TException $err
     *
     * @return Option<TValue>
     * @throws TException
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function orThrow(Throwable|callable $err): Option;

    /**
     * Accepts only the given value.
     *
     * @param TValue $value
     *
     * @return Option<TValue>
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function accept(mixed $value): Option;

    /**
     * Rejects the given value.
     *
     * @param TValue $value
     *
     * @return Option<TValue>
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function reject(mixed $value): Option;

}
