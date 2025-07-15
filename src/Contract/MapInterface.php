<?php
declare(strict_types=1);

namespace Raxos\Foundation\Contract;

use Countable;
use IteratorAggregate;

/**
 * Interface MapInterface
 *
 * @template TValue of mixed
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Contract
 * @since 1.1.0
 */
interface MapInterface extends ArrayableInterface, Countable, IteratorAggregate
{

    /**
     * Returns the value at the given key.
     *
     * @param string $key
     *
     * @return TValue
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function get(string $key): mixed;

    /**
     * Returns TRUE if a value exists at the given key.
     *
     * @param string $key
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function has(string $key): bool;

    /**
     * Merges the other map into this one.
     *
     * @param MapInterface|array<string, TValue> $other
     *
     * @return static
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function merge(self|array $other): static;

}
