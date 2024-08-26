<?php
declare(strict_types=1);

namespace Raxos\Foundation\Collection;

/**
 * Interface MutableMapInterface
 *
 * @template TValue
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Collection
 * @since 1.1.0
 */
interface MutableMapInterface
{

    /**
     * Sets the value at the given key.
     *
     * @param string $key
     * @param TValue $value
     *
     * @return void
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function set(string $key, mixed $value): void;

    /**
     * Unsets the value at the given key.
     *
     * @param string $key
     *
     * @return void
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function unset(string $key): void;

}
