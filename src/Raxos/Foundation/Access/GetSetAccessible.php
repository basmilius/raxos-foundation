<?php
declare(strict_types=1);

namespace Raxos\Foundation\Access;

/**
 * Trait GetSetAccessible
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Access
 * @since 1.0.0
 */
trait GetSetAccessible
{

    /**
     * Gets the value at the given key.
     *
     * @param string|int $key
     * @param mixed $defaultValue
     *
     * @return mixed
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function get(string|int $key, mixed $defaultValue = null): mixed
    {
        return $this->getValue($key) ?? $defaultValue;
    }

    /**
     * Returns TRUE if the given key exists.
     *
     * @param string|int $key
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function has(string|int $key): bool
    {
        return $this->hasValue($key);
    }

    /**
     * Sets a value at the given key.
     *
     * @param string|int $key
     * @param mixed $value
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function set(string|int $key, mixed $value): void
    {
        $this->setValue($key, $value);
    }

    /**
     * Removes a value at the given key.
     *
     * @param string|int $key
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function unset(string|int $key): void
    {
        $this->unsetValue($key);
    }

}
