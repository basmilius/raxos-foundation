<?php
declare(strict_types=1);

namespace Raxos\Foundation\Contract;

/**
 * Interface ArrayableInterface
 *
 * @template TKey of array-key
 * @template TValue
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Contract
 * @since 1.0.17
 */
interface ArrayableInterface
{

    /**
     * Returns an array representation of the object.
     *
     * @return array<TKey, TValue>
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.17
     */
    public function toArray(): array;

}
