<?php
declare(strict_types=1);

namespace Raxos\Foundation\Option;

use Raxos\Foundation\Contract\{DebuggableInterface, OptionInterface};
use Throwable;
use function is_callable;

/**
 * Class None
 *
 * @template TValue
 * @extends Option<TValue>
 * @implements OptionInterface<TValue>
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
    public function filter(callable $predicate): OptionInterface
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
    public function map(callable $map): OptionInterface
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function orElse(OptionInterface|callable $fallback): OptionInterface
    {
        if ($fallback instanceof OptionInterface) {
            return $fallback;
        }

        $option = $fallback();

        if ($option instanceof OptionInterface) {
            return $option;
        }

        throw OptionException::notAnOption();
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function orThrow(Throwable|callable $err): OptionInterface
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
    public function accept(mixed $value): OptionInterface
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function reject(mixed $value): OptionInterface
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.6.0
     */
    public function __debugInfo(): array
    {
        return [
            'type' => self::class,
            'value' => null
        ];
    }

}
