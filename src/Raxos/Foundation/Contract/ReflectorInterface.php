<?php
declare(strict_types=1);

namespace Raxos\Foundation\Contract;

/**
 * Interface ReflectorInterface
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Contract
 * @since 2.0.0
 */
interface ReflectorInterface
{

    /**
     * Returns a name for the reflected type.
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 2.0.0
     */
    public function getName(): string;

}
