<?php
declare(strict_types=1);

namespace Raxos\Foundation\Option;

use Raxos\Foundation\Contract\{DebuggableInterface, OptionInterface};
use Throwable;

/**
 * Class Some
 *
 * @template TValue
 * @extends Option<TValue>
 * @implements OptionInterface<TValue>
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Option
 * @since 1.1.0
 */
final readonly class Some extends Option implements DebuggableInterface
{

    /**
     * Some constructor.
     *
     * @param TValue $value
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function __construct(
        private mixed $value
    )
    {
        parent::__construct(
            isEmpty: false
        );
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function filter(callable $predicate): OptionInterface
    {
        if ($predicate($this->value) === true) {
            return $this;
        }

        return self::none();
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function get(): mixed
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function getOrElse(mixed $fallback): mixed
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function getOrInvoke(callable $fn): mixed
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function getOrThrow(Throwable|callable $err): mixed
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function map(callable $map): Option
    {
        return new self($map($this->value));
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function orElse(OptionInterface|callable $fallback): OptionInterface
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function orThrow(Throwable|callable $err): OptionInterface
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function accept(mixed $value): OptionInterface
    {
        if ($this->value === $value) {
            return $this;
        }

        return self::none();
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function reject(mixed $value): OptionInterface
    {
        if ($this->value !== $value) {
            return $this;
        }

        return self::none();
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.6.0
     */
    public function __debugInfo(): ?array
    {
        return [
            'value' => $this->value
        ];
    }

}
