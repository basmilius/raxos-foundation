<?php
declare(strict_types=1);

namespace Raxos\Foundation\Access;

use Exception;

/**
 * Trait ArrayAccessible
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Access
 * @since 1.0.0
 */
trait ArrayAccessible
{

    /**
     * Returns TRUE if the given field exists.
     *
     * @param mixed $offset
     *
     * @return bool
     * @throws Exception
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public function offsetExists(mixed $offset): bool
    {
        return $this->hasValue($offset);
    }

    /**
     * Gets the value at the given offset.
     *
     * @param mixed $offset
     *
     * @return mixed
     * @throws Exception
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->getValue($offset);
    }

    /**
     * Sets the value at the given offset.
     *
     * @param mixed $offset
     * @param mixed $value
     *
     * @throws Exception
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->setValue($offset, $value);
    }

    /**
     * Unsets the value at the given offset.
     *
     * @param mixed $offset
     *
     * @throws Exception
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public function offsetUnset(mixed $offset): void
    {
        $this->unsetValue($offset);
    }

}
