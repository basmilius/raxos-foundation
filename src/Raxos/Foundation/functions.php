<?php
declare(strict_types=1);

namespace Raxos\Foundation;

use Raxos\Foundation\Util\Singleton;
use function getenv;
use function is_bool;
use function is_int;
use const PHP_SAPI;

/**
 * Returns the value of an environment variable or the given default.
 *
 * @param string $variable
 * @param string|bool|int|null $defaultValue
 *
 * @return string|bool|int|null
 * @author Bas Milius <bas@mili.us>
 * @since 1.1.0
 */
function env(string $variable, string|bool|int|null $defaultValue = null): string|bool|int|null
{
    $result = getenv($variable);

    if ($result === false) {
        return $defaultValue;
    }

    if (is_int($defaultValue)) {
        return (int)$result;
    }

    if (is_bool($defaultValue)) {
        return $result === '1';
    }

    return $result;
}

/**
 * Returns TRUE if PHP is running with its built-in webserver.
 *
 * @return bool
 * @author Bas Milius <bas@mili.us>
 * @since 1.4.0
 */
function isBuiltInServer(): bool
{
    return PHP_SAPI === 'cli-server';
}

/**
 * Returns TRUE if PHP is running on the command line.
 *
 * @return bool
 * @author Bas Milius <bas@mili.us>
 * @since 1.4.0
 */
function isCommandLineInterface(): bool
{
    return PHP_SAPI === 'cli';
}

/**
 * Returns an instance of the given class and makes sure
 * that there is only one instance of it.
 *
 * @template TInstance
 *
 * @param class-string<TInstance> $className
 * @param callable():TInstance|null $setup
 *
 * @return TInstance
 * @author Bas Milius <bas@mili.us>
 * @since 1.1.0
 */
function singleton(string $className, ?callable $setup = null): object
{
    if (Singleton::has($className)) {
        return Singleton::get($className);
    }

    if ($setup === null) {
        return Singleton::make($className);
    }

    return Singleton::register($className, $setup);
}
