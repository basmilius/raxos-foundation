<?php
declare(strict_types=1);

namespace Raxos\Foundation\Access;

/**
 * Trait ObjectAccessible
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Access
 * @since 1.0.0
 */
trait ObjectAccessible
{

    /**
     * Gets the value at the given offset.
     *
     * @param mixed $offset
     *
     * @return mixed
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public function __get(mixed $offset): mixed
    {
        return $this->getValue($offset);
    }

    /**
     * Returns TRUE if the given field exists.
     *
     * @param mixed $offset
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public function __isset(mixed $offset): bool
    {
        return $this->hasValue($offset);
    }

    /**
     * Sets the value at the given offset.
     *
     * @param mixed $offset
     * @param mixed $value
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public function __set(mixed $offset, mixed $value): void
    {
        $this->setValue($offset, $value);
    }

    /**
     * Unsets the value at the given offset.
     *
     * @param mixed $offset
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public function __unset(mixed $offset): void
    {
        $this->unsetValue($offset);
    }

}
