<?php
declare(strict_types=1);

namespace Raxos\Foundation\Collection;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use Raxos\Foundation\Contract\ArrayableInterface;

/**
 * Interface ArrayListInterface
 *
 * @template TKey of array-key
 * @template TValue
 * @extends ArrayAccess<TKey, TValue>
 * @extends ArrayableInterface<TKey, TValue>
 * @extends IteratorAggregate<TKey, TValue>
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Collection
 * @since 1.1.0
 */
interface ArrayListInterface extends ArrayAccess, ArrayableInterface, Countable, IteratorAggregate
{

    /**
     * Chunks the array list in groups of the given size.
     *
     * @param int $size
     *
     * @return static<int, static<TKey, TValue>>
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function chunk(int $size): static;

    /**
     * Returns a clone of the array list.
     *
     * @return static<TKey, TValue>
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function clone(): static;

    /**
     * Collapses the array list.
     *
     * @return static<TKey, TValue>
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function collapse(): static;

    /**
     * Returns the given column(s) of each item in the array list.
     *
     * @param string|int ...$columns
     *
     * @return static<mixed, int>
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function column(string|int ...$columns): static;

    /**
     * Returns TRUE if the given item exists in the array list.
     *
     * @param TValue $item
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function contains(mixed $item): bool;

    /**
     * Converts the array list into another array list.
     *
     * @template TArrayList of ArrayListInterface
     *
     * @param class-string<TArrayList> $implementation
     *
     * @return TArrayList<TKey, TValue>
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function convertTo(string $implementation): self;

    /**
     * Returns the diff of the array list and the given items.
     *
     * @param iterable<TKey, TValue> $items
     *
     * @return static<TKey, TValue>
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function diff(iterable $items): static;

    /**
     * Runs the given function on each of the items in
     * the array list.
     *
     * @param callable(TValue, TKey):void $fn
     *
     * @return static<TKey, TValue>
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function each(callable $fn): static;

    /**
     * Returns TRUE if all the items in the array list match the
     * given predicate function.
     *
     * @param callable(TValue, TKey):bool $predicate
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function every(callable $predicate): bool;

    /**
     * Filters the array list using the given predicate function.
     *
     * @param callable(TValue, TKey):bool $predicate
     *
     * @return static<TKey, TValue>
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function filter(callable $predicate): static;

    /**
     * Returns the first element of the array list that matches the
     * predicate function. If no predicate is given, the first item
     * of the array list is returned. When nothing is found, the
     * default value is returned.
     *
     * @param callable(TValue, TKey):bool|null $predicate
     * @param TValue|null $default
     *
     * @return TValue|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function first(?callable $predicate = null, mixed $default = null): mixed;

    /**
     * Groups the items of the array list using the result of the
     * given function.
     *
     * @template TGroup of array-key
     *
     * @param callable(TValue, TKey):TGroup $fn
     *
     * @return self<TGroup, self<TKey, TValue>>
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function groupBy(callable $fn): static;

    /**
     * Returns TRUE if the array list is empty.
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function isEmpty(): bool;

    /**
     * Returns TRUE if the array list is not empty.
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function isNotEmpty(): bool;

    /**
     * Returns the keys in the array list.
     *
     * @return static<TKey, int>
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     * @see self::values()
     */
    public function keys(): static;

    /**
     * Returns the last element of the array list that matches the
     * predicate function. If no predicate is given, the last item
     * of the array list is returned. When nothing is found, the
     * default value is returned.
     *
     * @param callable(TValue, TKey):bool|null $predicate
     * @param TValue|null $default
     *
     * @return TValue|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function last(?callable $predicate = null, mixed $default = null): mixed;

    /**
     * Maps all the items in the array list using the given function.
     *
     * @template TMappedValue
     *
     * @param callable(TValue, TKey):TMappedValue $fn
     *
     * @return static<TKey, TMappedValue>
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function map(callable $fn): static;

    /**
     * Merges the array list with another iterable.
     *
     * @param ArrayableInterface<TKey, TValue>|ArrayListInterface<TKey, TValue>|iterable<TKey, TValue> $items
     *
     * @return static<TKey, TValue>
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function merge(ArrayableInterface|self|iterable $items): static;

    /**
     * Returns only the given keys of each item in the array list. If
     * an item is not an associative array or a database model, it will
     * always return the full item.
     *
     * @param TKey[] $keys
     *
     * @return static<TKey, TValue>
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function only(array $keys): static;

    /**
     * Reduce the array list to a single value using the given function.
     *
     * @template TResult
     *
     * @param callable(TResult, TValue, TKey):TResult $fn
     * @param TResult|null $initial
     *
     * @return TResult
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function reduce(callable $fn, mixed $initial = null): mixed;

    /**
     * Reverses the array list.
     *
     * @return static<TKey, TValue>
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function reverse(): static;

    /**
     * Searches for the key of the given value.
     *
     * @param TValue $value
     *
     * @return (TKey&string)|(TKey&int)|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function search(mixed $value): string|int|null;

    /**
     * Shuffles the array list.
     *
     * @return static<TKey, TValue>
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function shuffle(): mixed;

    /**
     * Returns a slice of the array list. When a length is omitted,
     * the remainder of the array list will be returned from offset.
     *
     * @param int $offset
     * @param int|null $length
     *
     * @return static<TKey, TValue>
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function slice(int $offset, ?int $length = null): static;

    /**
     * Returns TRUE if some the items in the array list match the
     * given predicate function.
     *
     * @param callable(TValue, TKey):bool $predicate
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function some(callable $predicate): bool;

    /**
     * Sorts the array list using the given function.
     *
     * @param callable(TValue, TValue):int $compare
     *
     * @return static<TKey, TValue>
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function sort(callable $compare): static;

    /**
     * Splices the array list.
     *
     * @param int $offset
     * @param int $length
     * @param TValue ...$replacements
     *
     * @return static<TKey, TValue>
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function splice(int $offset = 0, int $length = 0, mixed ...$replacements): static;

    /**
     * Returns unique values within the array list.
     *
     * @return static<TKey, TValue>
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function unique(): static;

    /**
     * Returns the values of the array list.
     *
     * @return $this
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     * @see self::keys()
     */
    public function values(): static;

}
