<?php
declare(strict_types=1);

namespace Raxos\Foundation\Collection;

/**
 * Interface Arrayable
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
     * @return array
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function toArray(): array;

}
