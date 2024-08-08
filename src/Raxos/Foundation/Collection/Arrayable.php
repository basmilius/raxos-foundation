<?php
declare(strict_types=1);

namespace Raxos\Foundation\Collection;

/**
 * Interface Arrayable
 *
 * @template TKey of array-key
 * @template TValue
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Collection
 * @since 1.0.0
 */
interface Arrayable
{

    /**
     * Returns an array representation of the object.
     *
     * @return array<TKey, TValue>
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function toArray(): array;

}
