<?php
declare(strict_types=1);

namespace Raxos\Foundation\Storage;

use ArrayAccess;
use Countable;
use Iterator;
use Raxos\Foundation\Access\ArrayAccessible;
use Raxos\Foundation\Access\GetSetAccessible;
use function array_key_exists;
use function array_keys;
use function array_values;
use function count;

/**
 * Class SimpleKeyValue
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Storage
 * @since 1.0.0
 */
class SimpleKeyValue implements ArrayAccess, Countable, Iterator
{

    use ArrayAccessible;
    use GetSetAccessible;

    private array $data;
    private int $position = 0;

    /**
     * SimpleKeyValue constructor.
     *
     * @param array $data
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * Gets all the data as an array.
     *
     * @return array
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function array(): array
    {
        return $this->data;
    }

    /**
     * Gets all the keys.
     *
     * @return array
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function keys(): array
    {
        return array_keys($this->data);
    }

    /**
     * Gets all the values.
     *
     * @return array
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function values(): array
    {
        return array_values($this->data);
    }

    /**
     * Gets the value at the given offset.
     *
     * @param int|string $offset
     *
     * @return mixed
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    protected function getValue(int|string $offset): mixed
    {
        return $this->data[$offset] ?? null;
    }

    /**
     * Returns TRUE if a value exists at the given offset.
     *
     * @param int|string $offset
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    protected function hasValue(int|string $offset): bool
    {
        return array_key_exists($offset, $this->data);
    }

    /**
     * Sets the value at the given offset.
     *
     * @param int|string $offset
     * @param mixed $value
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    protected function setValue(int|string $offset, mixed $value): void
    {
        $this->data[$offset] = $value;
    }

    /**
     * Unsets the value at the given offset.
     *
     * @param int|string $offset
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    protected function unsetValue(int|string $offset): void
    {
        if (!array_key_exists($offset, $this->data)) {
            return;
        }

        unset($this->data[$offset]);
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function count(): int
    {
        return count($this->data);
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public function current(): mixed
    {
        return $this->data[$this->key()] ?? null;
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public function next(): void
    {
        ++$this->position;
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public function key(): mixed
    {
        return $this->keys()[$this->position] ?? null;
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public function valid(): bool
    {
        return array_key_exists($this->position, $this->keys());
    }

    /**
     * Returns debug info.
     *
     * @return array
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __debugInfo(): array
    {
        return $this->data;
    }

}
