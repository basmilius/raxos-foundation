<?php
declare(strict_types=1);

namespace Raxos\Foundation\Util;

use Raxos\Foundation\Collection\Arrayable;
use Raxos\Foundation\Collection\ArrayList;
use Traversable;
use function array_filter;
use function array_flip;
use function array_intersect_key;
use function array_key_first;
use function array_keys;
use function array_reverse;
use function array_values;
use function count;
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
     * @template T
     *
     * @param ArrayList<T>|Arrayable<T>|Traversable<T>|array<T> $items
     *
     * @return array<T>
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function ensureArray(ArrayList|Arrayable|Traversable|array $items): array
    {
        if ($items instanceof ArrayList) {
            return $items->all();
        }

        if ($items instanceof Arrayable) {
            return $items->toArray();
        }

        if ($items instanceof Traversable) {
            $items = iterator_to_array($items);
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
     * Returns TRUE if the given array is associative.
     *
     * @param array $arr
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function isAssociative(array $arr): bool
    {
        return count(array_filter(array_keys($arr), 'is_string')) === count($arr);
    }

    /**
     * Returns TRUE if the given array is sequential.
     *
     * @param array $arr
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function isSequential(array $arr): bool
    {
        return count(array_filter(array_keys($arr), 'is_int')) === count($arr);
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
     * Returns a subset of the given array.
     *
     * @param array $arr
     * @param array $keys
     *
     * @return array
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
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
    public static function last(array $items, callable $predicate = null, mixed $defaultValue = null): mixed
    {
        $items = array_reverse($items);

        return self::first($items, $predicate, $defaultValue);
    }

}
