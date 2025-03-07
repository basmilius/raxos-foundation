<?php
declare(strict_types=1);

namespace Raxos\Foundation\Util;

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
     * @template TInstance
     *
     * @param class-string<TInstance> $class
     *
     * @return TInstance
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function get(string $class): object
    {
        return self::$instances[$class] ??= self::make($class);
    }

    /**
     * Gets or returns null a singleton.
     *
     * @template TInstance
     *
     * @param class-string<TInstance> $class
     *
     * @return TInstance|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.5.0
     */
    public static function getOrNull(string $class): ?object
    {
        return self::$instances[$class] ?? null;
    }

    /**
     * Returns TRUE if the given class is instantiated by the {@see Singleton} class.
     *
     * @param class-string $class
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function has(string $class): bool
    {
        return isset(self::$instances[$class]);
    }

    /**
     * Instantiates a new singleton.
     *
     * @template TInstance
     *
     * @param class-string<TInstance> $class
     *
     * @return TInstance
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function make(string $class): object
    {
        return self::$instances[$class] = new $class();
    }

    /**
     * Registers the given class.
     *
     * @template TInstance
     *
     * @param class-string<TInstance> $class
     * @param callable():TInstance $setup
     *
     * @return TInstance
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public static function register(string $class, callable $setup): object
    {
        return self::$instances[$class] ??= $setup();
    }

}
