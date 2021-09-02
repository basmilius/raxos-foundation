<?php
declare(strict_types=1);

namespace Raxos\Foundation\Collection;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use Raxos\Database\Orm\Model;
use Raxos\Foundation\Util\ArrayUtil;
use Serializable;
use Traversable;
use function array_chunk;
use function array_column;
use function array_diff;
use function array_filter;
use function array_keys;
use function array_map;
use function array_merge;
use function array_pop;
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
use function serialize;
use function shuffle;
use function unserialize;
use function usort;

/**
 * Class ArrayList
 *
 * @template T
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Collection
 * @since 1.0.0
 */
class ArrayList implements Arrayable, ArrayAccess, Countable, IteratorAggregate, JsonSerializable, Serializable
{

    protected array $items;

    /**
     * ArrayList constructor.
     *
     * @param array<int, T> $items
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * Adds the given item to the ArrayList.
     *
     * @param T $item
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
     * @return array<int, T>
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * Returns TRUE if any of the items matches the given predicate.
     *
     * @param callable(T):bool $predicate
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function any(callable $predicate): bool
    {
        return count(array_filter($this->items, $predicate)) > 0;
    }

    /**
     * Appends the given item to the ArrayList.
     *
     * @param T $item
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
     * @template Y of ArrayList
     *
     * @param class-string<Y> $implementation
     *
     * @return Y
     * @throws CollectionException
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function as(string $implementation): static
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
     * @return static<static<T>>
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function chunk(int $size): static
    {
        /** @psalm-var static<static<T>> $chunked */
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
                $result = array_merge($result, $item);
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
     * @return static<mixed>
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
     * @param T $value
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

        return in_array($value, $this->items);
    }

    /**
     * Copies the ArrayList with its items.
     *
     * @return static<T>
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
     * @return $this
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
     * @param callable(T):void $predicate
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
     * @param callable(T):bool $predicate
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
     * @param callable|null $predicate
     * @param T|null $default
     *
     * @return T
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
     * @param callable(T):(string|int|bool) $predicate
     *
     * @return static<static<T>>
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function groupBy(callable $predicate): static
    {
        $result = [];

        foreach ($this->items as $item) {
            $result[$predicate($item)][] = $item;
        }

        return new static(array_values($result));
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
     * @return static<int>
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
     * @param callable|null $predicate
     * @param T|null $default
     *
     * @return T|null
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
     * @template Y
     *
     * @param callable(T):Y $predicate
     *
     * @return static<Y>
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
     * @template Y
     *
     * @param callable(T):Y $predicate
     *
     * @return $this
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
     * @param ArrayList|Arrayable|Traversable|array $items
     *
     * @return $this
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function merge(self|Arrayable|Traversable|array $items): static
    {
        return new static(array_merge($this->items, ArrayUtil::ensureArray($items)));
    }

    /**
     * Returns only the given keys of each items in the ArrayList. If an item
     * is not an accociative array, the item itself will be returned.
     *
     * @param array<array-key, string> $keys
     *
     * @return static<T>
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function only(array $keys): static
    {
        /**
         * @param T $item
         *
         * @return mixed
         *
         * @psalm-suppress MissingClosureParamType
         * @psalm-suppress MissingClosureReturnType
         */
        $predicate = static function (mixed $item) use ($keys) {
            if (is_array($item)) {
                return ArrayUtil::only($item, $keys);
            }

            if ($item instanceof Model) {
                return $item->only($keys);
            }

            return $item;
        };

        return $this->map($predicate);
    }

    /**
     * Returns and removes the last item in the ArrayList.
     *
     * @return T|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function pop()
    {
        return array_pop($this->items);
    }

    /**
     * Prepends the given item to the ArrayList.
     *
     * @param T $item
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
     * Reverses the ArrayList.
     *
     * @return $this
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
     * @param T $value
     *
     * @return int|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function search(mixed $value): ?int
    {
        return ($result = array_search($value, $this->items)) !== false ? $result : null;
    }

    /**
     * Returns and removes the first element of the ArrayList.
     *
     * @return T|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function shift()
    {
        return array_shift($this->items);
    }

    /**
     * Shuffles the ArrayList.
     *
     * @return static<T>
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
     * @return static<T>
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
     * @param callable $comparator
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
     * @param T ...$replacement
     *
     * @return static<T>
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
     * @return static<T>
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
     * @return static<T>
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function values(): static
    {
        return new static(array_values($this->items));
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
    public function offsetGet($offset)
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
     * @return array<T>
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
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function serialize(): ?string
    {
        return serialize($this->items);
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function unserialize(mixed $data): void
    {
        $this->items = unserialize($data);
    }

    /**
     * Returns debug info.
     *
     * @return array|null
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
     * @template Y
     *
     * @param iterable<Y> $items
     *
     * @return static<Y>
     * @throws CollectionException
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function of(iterable $items): static
    {
        if ($items instanceof self) {
            $items = $items->items;
        } else if ($items instanceof Traversable) {
            $items = iterator_to_array($items);
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
    protected static function validateItem(mixed $item): void
    {
    }

}
