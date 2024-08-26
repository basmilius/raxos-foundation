<?php
declare(strict_types=1);

namespace Raxos\Foundation\Collection;

use ArrayIterator;
use Traversable;
use function count;

/**
 * Trait ArrayListAccessible
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Collection
 * @since 1.1.0
 */
trait ArrayListAccessible
{

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function offsetExists($offset): bool
    {
        return isset($this->data[$offset]);
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function offsetGet($offset): mixed
    {
        return $this->data[$offset];
    }

    /**
     * {@inheritdoc}
     * @throws CollectionException
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function offsetSet($offset, $value): void
    {
        if ($this instanceof ReadonlyArrayList) {
            throw CollectionException::immutable();
        }

        $this->data[$offset] = $value;
    }

    /**
     * {@inheritdoc}
     * @throws CollectionException
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function offsetUnset($offset): void
    {
        if ($this instanceof ReadonlyArrayList) {
            throw CollectionException::immutable();
        }

        unset($this->data[$offset]);
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function count(): int
    {
        return count($this->data);
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function toArray(): array
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->data);
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public function jsonSerialize(): array
    {
        return $this->data;
    }

}
