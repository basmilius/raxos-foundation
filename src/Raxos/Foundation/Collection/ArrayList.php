<?php
declare(strict_types=1);

namespace Raxos\Foundation\Collection;

use JsonSerializable;
use Raxos\Foundation\Contract\{ArrayListInterface, DebuggableInterface, MutableArrayListInterface, SerializableInterface, ValidatedArrayListInterface};
use Traversable;
use function array_is_list;
use function array_pop;
use function array_shift;
use function array_values;
use function is_subclass_of;
use function iterator_to_array;

/**
 * Class ArrayList
 *
 * @template TKey of array-key
 * @template TValue
 * @implements ArrayListInterface<TKey, TValue>
 * @implements MutableArrayListInterface<TKey, TValue>
 * @mixin ArrayListable<TKey, TValue>
 * @mixin ArrayListAccessible<TKey, TValue>
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Collection
 * @since 1.1.0
 */
class ArrayList implements ArrayListInterface, MutableArrayListInterface, DebuggableInterface, JsonSerializable, SerializableInterface
{

    use ArrayListable;
    use ArrayListAccessible;

    /**
     * ArrayList constructor.
     *
     * @param array<TKey, TValue> $data
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public final function __construct(
        protected array $data = []
    ) {}

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function append(mixed $item): void
    {
        $this->data[] = $item;
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function pop(): mixed
    {
        return array_pop($this->data);
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function prepend(mixed $item): void
    {
        $this->data = [$item, ...$this->data];
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function shift(): mixed
    {
        return array_shift($this->data);
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function __debugInfo(): ?array
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@glybe.nl>
     * @since 1.1.0
     */
    public function __serialize(): array
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@glybe.nl>
     * @since 1.1.0
     */
    public function __unserialize(array $data): void
    {
        $this->data = $data;
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
     * @since 1.1.0
     */
    public static function of(iterable $items): static
    {
        $implementation = static::class;

        if ($items instanceof self) {
            $items = $items->data;
        } elseif ($items instanceof Traversable) {
            $items = iterator_to_array($items);
        }

        if (array_is_list($items)) {
            $items = array_values($items);
        }

        if (is_subclass_of($implementation, ValidatedArrayListInterface::class)) {
            foreach ($items as $item) {
                $implementation::validateItem($item);
            }
        }

        return new static($items);
    }

}
