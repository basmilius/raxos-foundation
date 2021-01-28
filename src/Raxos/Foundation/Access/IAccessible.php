<?php
declare(strict_types=1);

namespace Raxos\Foundation\Access;

/**
 * Interface IAccessible
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Access
 * @since 1.0.0
 */
interface IAccessible
{

    /**
     * Gets the value of the given field.
     *
     * @param string|int $field
     *
     * @return mixed
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getValue(string|int $field): mixed;

    /**
     * Returns TRUE if a value exists for the given field.
     *
     * @param string|int $field
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function hasValue(string|int $field): bool;

    /**
     * Sets the value of the given key.
     *
     * @param string|int $field
     * @param mixed $value
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function setValue(string|int $field, mixed $value): void;

    /**
     * Removes the value of the given key.
     *
     * @param string|int $field
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function unsetValue(string|int $field): void;

}
