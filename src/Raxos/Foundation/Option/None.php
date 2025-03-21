<?php
declare(strict_types=1);

namespace Raxos\Foundation\Option;

use Raxos\Foundation\Contract\DebuggableInterface;
use Throwable;
use function is_callable;

/**
 * Class None
 *
 * @template TValue
 * @extends Option<TValue>
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Option
 * @since 1.1.0
 */
final readonly class None extends Option implements DebuggableInterface
{

    /**
     * None constructor.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function __construct()
    {
        parent::__construct(
            isEmpty: true
        );
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function filter(callable $predicate): Option
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function get(): mixed
    {
        throw OptionException::noValue();
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function getOrElse(mixed $fallback): mixed
    {
        return $fallback;
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function getOrInvoke(callable $fn): mixed
    {
        return $fn();
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function getOrThrow(Throwable|callable $err): mixed
    {
        if (is_callable($err)) {
            throw $err();
        }

        throw $err;
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function map(callable $map): Option
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function orElse(callable|Option $fallback): Option
    {
        if ($fallback instanceof Option) {
            return $fallback;
        }

        $option = $fallback();

        if ($option instanceof Option) {
            return $option;
        }

        throw OptionException::notAnOption();
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function orThrow(callable|Throwable $err): Option
    {
        if (is_callable($err)) {
            throw $err();
        }

        throw $err;
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function accept(mixed $value): Option
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function reject(mixed $value): Option
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.6.0
     */
    public function __debugInfo(): ?array
    {
        return null;
    }

}
