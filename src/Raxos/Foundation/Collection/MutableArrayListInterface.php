<?php
declare(strict_types=1);

namespace Raxos\Foundation\Collection;

use ArrayAccess;
use IteratorAggregate;
use Raxos\Foundation\Contract\ArrayableInterface;

/**
 * Interface MutableArrayListInterface
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
interface MutableArrayListInterface
{

    /**
     * Appends the given item.
     *
     * @param TValue $item
     *
     * @return static<TKey, TValue>
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function append(mixed $item): static;

    /**
     * Pops an item of the array list.
     *
     * @return TValue|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function pop(): mixed;

    /**
     * Prepends the given item.
     *
     * @param TValue $item
     *
     * @return static<TKey, TValue>
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function prepend(mixed $item): static;

    /**
     * Returns and removes the first item of the array list.
     *
     * @return TValue|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function shift(): mixed;

}
