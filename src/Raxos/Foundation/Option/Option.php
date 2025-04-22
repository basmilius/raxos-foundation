<?php
declare(strict_types=1);

namespace Raxos\Foundation\Option;

use Raxos\Foundation\Contract\OptionInterface;
use Raxos\Foundation\Util\Singleton;

/**
 * Class Option
 *
 * @template TValue
 * @implements OptionInterface<TValue>
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Option
 * @since 1.1.0
 */
abstract readonly class Option implements OptionInterface
{

    /**
     * Option constructor.
     *
     * @param bool $isEmpty
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function __construct(
        public bool $isEmpty
    ) {}

    /**
     * Returns an option based on the given callable.
     *
     * @param callable():TValue $fn
     * @param mixed|null $none
     *
     * @return OptionInterface<TValue>
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public static function fromCallable(callable $fn, mixed $none = null): OptionInterface
    {
        return self::fromValue($fn(), $none);
    }

    /**
     * Returns an option from the given value.
     *
     * @param TValue $value
     * @param mixed $none
     *
     * @return OptionInterface<TValue>
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public static function fromValue(mixed $value, mixed $none = null): OptionInterface
    {
        if ($value instanceof self) {
            return $value;
        }

        if ($value === $none) {
            return self::none();
        }

        return self::some($value);
    }

    /**
     * Returns none.
     *
     * @return OptionInterface<TValue>
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public static function none(): OptionInterface
    {
        return Singleton::get(None::class);
    }

    /**
     * Returns a value.
     *
     * @param TValue $value
     *
     * @return OptionInterface<TValue>
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public static function some(mixed $value): OptionInterface
    {
        return new Some($value);
    }

}
