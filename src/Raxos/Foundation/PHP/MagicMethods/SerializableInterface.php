<?php
declare(strict_types=1);

namespace Raxos\Foundation\PHP\MagicMethods;

/**
 * Interface SerializableInterface
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\PHP\MagicMethods
 * @since 1.0.0
 */
interface SerializableInterface
{

    /**
     * Returns the data of the current instance that should
     * be serialized.
     *
     * @return array
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __serialize(): array;

    /**
     * Constructs the current instance based on the given
     * data that was serialized.
     *
     * @param array $data
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __unserialize(array $data): void;

}
