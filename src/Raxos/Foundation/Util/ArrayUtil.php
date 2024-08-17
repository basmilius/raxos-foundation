<?php
declare(strict_types=1);

namespace Raxos\Foundation\Util;

use JetBrains\PhpStorm\Pure;
use Raxos\Foundation\Collection\ArrayList;
use Raxos\Foundation\Contract\ArrayableInterface;
use Traversable;
use function array_flip;
use function array_intersect;
use function array_intersect_key;
use function array_key_first;
use function array_reverse;
use function array_values;
use function is_array;
use function is_null;
use function iterator_to_array;

/**
 * Class ArrayUtil
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Util
 * @since 1.0.0
 */
final class ArrayUtil
{

    /**
     * Ensures an array.
     *
     * @template TKey
     * @template TValue
     *
     * @param iterable|ArrayableInterface<TKey, TValue>|ArrayList<TKey, TValue> $items
     *
     * @return array<TKey, TValue>
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function ensureArray(ArrayList|ArrayableInterface|iterable $items): array
    {
        if ($items instanceof ArrayList) {
            return $items->all();
        }

        if ($items instanceof ArrayableInterface) {
            return $items->toArray();
        }

        if ($items instanceof Traversable) {
            $items = iterator_to_array($items, false);
        }

        return $items;
    }

    /**
     * Finds the index of the first array element that matches the predicate.
     *
     * @param array $arr
     * @param callable $predicate
     *
     * @return string|int|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    #[Pure]
    public static function findIndex(array $arr, callable $predicate): string|int|null
    {
        foreach ($arr as $index => $elm) {
            if ($predicate($elm)) {
                return $index;
            }
        }

        return null;
    }

    /**
     * Flattens the given array.
     *
     * @param array $arr
     * @param int $depth
     *
     * @return array
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function flatten(array $arr, int $depth = 25): array
    {
        $result = [];

        foreach ($arr as $item) {
            if (!is_array($item)) {
                $result[] = $item;
            } else {
                $values = $depth === 1 ? array_values($item) : self::flatten($item, --$depth);

                foreach ($values as $value) {
                    $result[] = $value;
                }
            }
        }

        return $result;
    }

    /**
     * Groups a multidimensional array by key.
     *
     * @param array $arr
     * @param float|int|string $key
     *
     * @return array
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    #[Pure]
    public static function groupBy(array $arr, float|int|string $key): array
    {
        $result = [];

        foreach ($arr as $item) {
            $result[$item[$key]] ??= [];
            $result[$item[$key]][] = $item;
        }

        return array_values($result);
    }

    /**
     * Returns TRUE if any or all of the given items is in the given array.
     *
     * @param array $arr
     * @param array $items
     * @param bool $all
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.17
     */
    #[Pure]
    public static function in(array $arr, array $items, bool $all = false): bool
    {
        if ($all) {
            return empty(array_intersect($items, $arr));
        }

        return !empty(array_intersect($items, $arr));
    }

    /**
     * Returns a subset of the given array.
     *
     * @param array $arr
     * @param array $keys
     *
     * @return array
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    #[Pure]
    public static function only(array $arr, array $keys): array
    {
        return array_intersect_key($arr, array_flip($keys));
    }

    /**
     * Returns the first element of the given array. If a predicate is provided,
     * it's used as a truth check.
     *
     * @template T
     *
     * @param array<T> $items
     * @param callable|null $predicate
     * @param T|mixed|null $defaultValue
     *
     * @return T
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    #[Pure]
    public static function first(array $items, callable $predicate = null, mixed $defaultValue = null): mixed
    {
        if (is_null($predicate)) {
            if (empty($items)) {
                return $defaultValue;
            }

            $key = array_key_first($items);

            if ($key === null) {
                return $defaultValue;
            }

            return $items[$key];
        }

        foreach ($items as $key => $value) {
            if ($predicate($value, $key)) {
                return $value;
            }
        }

        return $defaultValue;
    }

    /**
     * Returns the last element of the given array. If a predicate is provided,
     * it's used as a truth check.
     *
     * @template T
     *
     * @param array<T> $items
     * @param callable|null $predicate
     * @param T|mixed|null $defaultValue
     *
     * @return T
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    #[Pure]
    public static function last(array $items, callable $predicate = null, mixed $defaultValue = null): mixed
    {
        $items = array_reverse($items);

        return self::first($items, $predicate, $defaultValue);
    }

    /**
     * Returns TRUE if every item of the given $iterable match
     * the given predicate.
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param iterable<TKey, TValue> $iterable
     * @param callable(TValue, TKey):bool $predicate
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.16
     */
    #[Pure]
    public static function every(iterable $iterable, callable $predicate): bool
    {
        foreach ($iterable as $key => $value) {
            if (!$predicate($value, $key)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns TRUE if some of the items in the given $iterable match
     * the given predicate.
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param iterable<TKey, TValue> $iterable
     * @param callable(TValue, TKey):bool $predicate
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.16
     */
    #[Pure]
    public static function some(iterable $iterable, callable $predicate): bool
    {
        foreach ($iterable as $key => $value) {
            if ($predicate($value, $key)) {
                return true;
            }
        }

        return false;
    }

}
