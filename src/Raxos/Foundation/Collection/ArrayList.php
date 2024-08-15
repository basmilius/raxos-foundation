<?php
declare(strict_types=1);

namespace Raxos\Foundation\Collection;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use Raxos\Database\Orm\Model;
use Raxos\Foundation\PHP\MagicMethods\{DebugInfoInterface, SerializableInterface};
use Raxos\Foundation\Util\ArrayUtil;
use Traversable;
use function array_chunk;
use function array_column;
use function array_diff;
use function array_filter;
use function array_keys;
use function array_map;
use function array_merge;
use function array_pop;
use function array_reduce;
use function array_reverse;
use function array_search;
use function array_shift;
use function array_slice;
use function array_splice;
use function array_unique;
use function array_unshift;
use function array_values;
use function count;
use function in_array;
use function is_array;
use function is_callable;
use function is_null;
use function is_subclass_of;
use function iterator_to_array;
use function shuffle;
use function usort;

/**
 * Class ArrayList
 *
 * @template TKey of array-key
 * @template TValue
 * @extends array<TKey, TValue>
 * @implements iterable<TKey, TValue>
 * @implements Arrayable<TKey, TValue>
 * @implements ArrayAccess<TKey, TValue>
 * @implements IteratorAggregate<TKey, TValue>
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Collection
 * @since 1.0.0
 */
class ArrayList implements Arrayable, ArrayAccess, Countable, DebugInfoInterface, IteratorAggregate, JsonSerializable, SerializableInterface
{

    /**
     * ArrayList constructor.
     *
     * @param array<TKey, TValue> $items
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function __construct(
        protected array $items = []
    ) {}

    /**
     * Adds the given item to the ArrayList.
     *
     * @param TValue $item
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function add(mixed $item): void
    {
        $this->items[] = $item;
    }

    /**
     * Returns all items in the ArrayList.
     *
     * @return array<TKey, TValue>
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * Appends the given item to the ArrayList.
     *
     * @param TValue $item
     *
     * @return $this
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function append(mixed $item): static
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * If possible, converts the collection to another implementation.
     *
     * @template TList of ArrayList
     *
     * @param class-string<TList> $implementation
     *
     * @return TList<TKey, TValue>
     * @throws CollectionException
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function as(string $implementation): mixed
    {
        if (!is_subclass_of($implementation, self::class)) {
            throw new CollectionException('The given implementation is not an array list.', CollectionException::ERR_NON_COLLECTION);
        }

        return $implementation::of($this->items);
    }

    /**
     * Chunks the ArrayList.
     *
     * @param int $size
     *
     * @return static<int, static<TKey, TValue>>
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function chunk(int $size): static
    {
        $chunked = new static;
        $chunks = array_chunk($this->items, $size);

        foreach ($chunks as $chunk) {
            $chunked->add(new static($chunk));
        }

        return $chunked;
    }

    /**
     * Collapses the ArrayList.
     *
     * @return $this
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function collapse(): static
    {
        $result = [];

        foreach ($this->items as $item) {
            if ($item instanceof self) {
                $item = $item->items;
            }

            if (is_array($item)) {
                foreach ($item as $subItem) {
                    $result[] = $subItem;
                }
            } else {
                $result[] = $item;
            }
        }

        return new static($result);
    }

    /**
     * Returns the given column(s) of each item in the ArrayList.
     *
     * @param string ...$columns
     *
     * @return static<TKey, mixed>
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function column(string ...$columns): static
    {
        $result = $this->items;

        foreach ($columns as $column) {
            $result = array_column($result, $column);
        }

        return new static($result);
    }

    /**
     * Returns TRUE if the given value exists in the ArrayList.
     *
     * @param callable(TValue, TKey):bool|TValue $value
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function contains(mixed $value): bool
    {
        if (is_callable($value)) {
            return !is_null($this->first($value));
        }

        return in_array($value, $this->items, true);
    }

    /**
     * Copies the ArrayList with its items.
     *
     * @return static<TKey, TValue>
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function copy(): static
    {
        return new static($this->items);
    }

    /**
     * Diffs the ArrayList.
     *
     * @param ArrayList|Arrayable|Traversable|array $items
     *
     * @return static<TKey, TValue>
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function diff(iterable $items): static
    {
        return new static(array_diff($this->items, ArrayUtil::ensureArray($items)));
    }

    /**
     * Runs the given predicate on all items in the ArrayList.
     *
     * @param callable(TValue):void $predicate
     *
     * @return $this
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function each(callable $predicate): static
    {
        foreach ($this->items as $item) {
            $predicate($item);
        }

        return $this;
    }

    /**
     * Filters the ArrayList with the given predicate.
     *
     * @param callable(TValue):bool $predicate
     *
     * @return $this
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function filter(callable $predicate): static
    {
        return new static(array_values(array_filter($this->items, $predicate)));
    }

    /**
     * Returns the first element of the ArrayList that matches the given predicate, if
     * given. When nothing is found, this method returns the given default value.
     *
     * @param callable(TValue, TKey):bool|null $predicate
     * @param TValue|null $default
     *
     * @return TValue|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function first(?callable $predicate = null, mixed $default = null): mixed
    {
        if ($predicate === null) {
            return count($this->items) > 0 ? ArrayUtil::first($this->items) : $default;
        }

        return ArrayUtil::first($this->items, $predicate, $default);
    }

    /**
     * Groups all items of the ArrayList using the given predicate.
     *
     * @template TGroup of array-key
     *
     * @param callable(TValue):TGroup $predicate
     *
     * @return static<TGroup, static<TKey, TValue>>
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function groupBy(callable $predicate): static
    {
        $result = [];

        foreach ($this->items as $item) {
            $key = $predicate($item);
            $result[$key] ??= new static;
            $result[$key]->add($item);
        }

        return new static($result);
    }

    /**
     * Returns TRUE if the ArrayList is empty.
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function isEmpty(): bool
    {
        return count($this->items) === 0;
    }

    /**
     * Returns TRUE if the ArrayList is not empty.
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function isNotEmpty(): bool
    {
        return count($this->items) > 0;
    }

    /**
     * Returns all the keys of the ArrayList.
     *
     * @return static<int, TKey>
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function keys(): static
    {
        $keys = array_keys($this->items);

        return new self($keys);
    }

    /**
     * Returns the last element of the ArrayList that matches the given predicate, if
     * given. When nothing is found, this method returns the given default value.
     *
     * @param callable(TValue, TKey):bool|null $predicate
     * @param TValue|null $default
     *
     * @return TValue|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function last(?callable $predicate = null, mixed $default = null)
    {
        if ($predicate === null) {
            return count($this->items) > 0 ? ArrayUtil::last($this->items) : $default;
        }

        return ArrayUtil::last($this->items, $predicate, $default);
    }

    /**
     * Maps all the items in the ArrayList to the returned value of the given predicate.
     *
     * @template TNewValue
     *
     * @param callable(TValue):TNewValue $predicate
     *
     * @return static<TKey, TNewValue>
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function map(callable $predicate): static
    {
        return new static(array_map($predicate, $this->items));
    }

    /**
     * Maps all the items in the ArrayList to the returned value of the given predicate, but
     * updates the current instance instead of creating a new one.
     *
     * @template TNewValue
     *
     * @param callable(TValue):TNewValue $predicate
     *
     * @return static<TKey, TNewValue>
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function mapTransform(callable $predicate): static
    {
        $this->items = array_map($predicate, $this->items);

        return $this;
    }

    /**
     * Merges the ArrayList with the given iterable.
     *
     * @param self|Arrayable|iterable $items
     *
     * @return $this
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function merge(self|Arrayable|iterable $items): static
    {
        return new static(array_merge($this->items, ArrayUtil::ensureArray($items)));
    }

    /**
     * Returns only the given keys of each item in the ArrayList. If an item
     * is not an associative array, the item itself will be returned.
     *
     * @param array<array-key, TKey> $keys
     *
     * @return static<TKey, TValue>
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function only(array $keys): static
    {
        return $this->map(static function (mixed $item) use ($keys) {
            if (is_array($item)) {
                return ArrayUtil::only($item, $keys);
            }

            if ($item instanceof Model) {
                return $item->only($keys);
            }

            return $item;
        });
    }

    /**
     * Returns and removes the last item in the ArrayList.
     *
     * @return TValue|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function pop(): mixed
    {
        return array_pop($this->items);
    }

    /**
     * Prepends the given item to the ArrayList.
     *
     * @param TValue $item
     *
     * @return $this
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function prepend(mixed $item): static
    {
        array_unshift($this->items, $item);

        return $this;
    }

    /**
     * Reduce the array list to a single value using the given predicate.
     *
     * @template TResult
     *
     * @param callable(TResult, TValue):TResult $predicate
     * @param TResult|null $initial
     *
     * @return mixed
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.16
     */
    public function reduce(callable $predicate, mixed $initial = null): mixed
    {
        return array_reduce($this->items, $predicate, $initial);
    }

    /**
     * Reverses the ArrayList.
     *
     * @return static<TKey, TValue>
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function reverse(): static
    {
        return new static(array_reverse($this->items));
    }

    /**
     * Searches for the key of the given value.
     *
     * @param TValue $value
     *
     * @return (TKey&string)|(TKey&int)|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function search(mixed $value): string|int|null
    {
        return ($result = array_search($value, $this->items, true)) !== false ? $result : null;
    }

    /**
     * Returns and removes the first element of the ArrayList.
     *
     * @return TValue|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function shift(): mixed
    {
        return array_shift($this->items);
    }

    /**
     * Shuffles the ArrayList.
     *
     * @return static<TKey, TValue>
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function shuffle(): static
    {
        $items = [...$this->items];

        shuffle($items);

        return new static($items);
    }

    /**
     * Returns a slice of the Arraylist.
     *
     * @param int $offset
     * @param int|null $length
     *
     * @return static<TKey, TValue>
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function slice(int $offset, ?int $length = null): static
    {
        return new static(array_slice($this->items, $offset, $length));
    }

    /**
     * Sorts the ArrayList.
     *
     * @param callable(TValue, TValue):int $comparator
     *
     * @return $this
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function sort(callable $comparator): static
    {
        usort($this->items, $comparator);

        return $this;
    }

    /**
     * Splices the collection.
     *
     * @param int $offset
     * @param int $length
     * @param TValue ...$replacement
     *
     * @return static<TKey, TValue>
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function splice(int $offset = 0, int $length = 0, ...$replacement): static
    {
        return new static(array_splice($this->items, $offset, $length, $replacement));
    }

    /**
     * Returns all unique values in the ArrayList.
     *
     * @return static<TKey, TValue>
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function unique(): static
    {
        return new static(array_values(array_unique($this->items)));
    }

    /**
     * Returns the values of the ArrayList.
     *
     * @return static<int, TValue>
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function values(): static
    {
        return new static(array_values($this->items));
    }

    /**
     * Returns TRUE if every item of the given $iterable match
     * the given predicate.
     *
     * @param callable(TValue, TKey):bool $predicate
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.16
     */
    public function every(callable $predicate): bool
    {
        return ArrayUtil::every($this->items, $predicate);
    }

    /**
     * Returns TRUE if some of the items in the given $iterable match
     * the given predicate.
     *
     * @param callable(TValue, TKey):bool $predicate
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.16
     */
    public function some(callable $predicate): bool
    {
        return ArrayUtil::some($this->items, $predicate);
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function offsetExists($offset): bool
    {
        return isset($this->items[$offset]);
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function offsetGet($offset): mixed
    {
        return $this->items[$offset];
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function offsetSet($offset, $value): void
    {
        $this->items[$offset] = $value;
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function offsetUnset($offset): void
    {
        unset($this->items[$offset]);
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function toArray(): array
    {
        return $this->items;
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function jsonSerialize(): array
    {
        return $this->items;
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@glybe.nl>
     * @since 1.0.1
     */
    public function __serialize(): array
    {
        return $this->items;
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@glybe.nl>
     * @since 1.0.1
     */
    public function __unserialize(array $data): void
    {
        $this->items = $data;
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __debugInfo(): ?array
    {
        return $this->items;
    }

    /**
     * Creates a new ArrayList instance with the given items.
     *
     * @template TOfKey
     * @template TOfValue
     *
     * @param iterable<TOfKey, TOfValue> $items
     *
     * @return static<TOfKey, TOfValue>
     * @throws CollectionException
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function of(iterable $items): static
    {
        if ($items instanceof self) {
            $items = $items->items;
        } elseif ($items instanceof Traversable) {
            $items = iterator_to_array($items, false);
        }

        foreach ($items as $item) {
            static::validateItem($item);
        }

        return new static($items);
    }

    /**
     * Validates the given item.
     *
     * @param mixed $item
     *
     * @throws CollectionException
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @see ArrayList::of()
     *
     * @noinspection PhpDocRedundantThrowsInspection
     */
    protected static function validateItem(mixed $item): void {}

}
