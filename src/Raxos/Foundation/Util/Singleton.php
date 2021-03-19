<?php
declare(strict_types=1);

namespace Raxos\Foundation\Util;

use function array_key_exists;

/**
 * Class Singleton
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Util
 * @since 1.0.0
 */
final class Singleton
{

    private static array $instances = [];

    /**
     * Gets or instantiates a singleton.
     *
     * @template T
     *
     * @param class-string<T> $class
     *
     * @return T
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function get(string $class): object
    {
        return self::$instances[$class] ??= self::make($class);
    }

    /**
     * Returns TRUE if the given class is instantiated by the {@see Singleton} class.
     *
     * @param string $class
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function has(string $class): bool
    {
        return array_key_exists($class, self::$instances);
    }

    /**
     * Instantiates a new singleton.
     *
     * @template T
     *
     * @param class-string<T> $class
     * @param array $parameters
     *
     * @return T
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function make(string $class, array $parameters = []): object
    {
        return self::$instances[$class] = new $class(...$parameters);
    }

}
