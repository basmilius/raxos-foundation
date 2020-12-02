<?php
declare(strict_types=1);

namespace Raxos\Foundation\PHP\MagicMethods;

/**
 * Interface DebugInfoInterface
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\PHP\MagicMethods
 * @since 1.0.0
 */
interface DebugInfoInterface
{

    /**
     * Returns debug information for the current instance.
     *
     * @return array|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __debugInfo(): ?array;

}
