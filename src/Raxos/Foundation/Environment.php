<?php
declare(strict_types=1);

namespace Raxos\Foundation;

use JetBrains\PhpStorm\Pure;

/**
 * Class Environment
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation
 * @since 1.0.0
 */
final class Environment
{

    /**
     * Returns TRUE if PHP is running on the built-in webserver.
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    #[Pure]
    public static function isBuiltInServer(): bool
    {
        return PHP_SAPI === 'cli-server';
    }

    /**
     * Returns TRUE if PHP is running on the command line.
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    #[Pure]
    public static function isCommandLineInterface(): bool
    {
        return PHP_SAPI === 'cli';
    }

}
