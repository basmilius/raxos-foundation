<?php
declare(strict_types=1);

namespace Raxos\Foundation\Contract;

/**
 * Interface SerializableInterface
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Contract
 * @since 1.0.17
 */
interface SerializableInterface
{

    /**
     * Serializes the object.
     *
     * @return array
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.17
     */
    public function __serialize(): array;

    /**
     * Unserializes the given array of data.
     *
     * @param array $data
     *
     * @return void
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.17
     */
    public function __unserialize(array $data): void;

}
