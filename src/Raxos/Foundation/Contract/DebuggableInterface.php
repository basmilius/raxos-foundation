<?php
declare(strict_types=1);

namespace Raxos\Foundation\Contract;

/**
 * Interface DebuggableInterface
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Contract
 * @since 1.0.17
 */
interface DebuggableInterface
{

    /**
     * Returns debug information for the object.
     *
     * @return array|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.17
     */
    public function __debugInfo(): ?array;

}
