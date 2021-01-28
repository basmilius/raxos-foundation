<?php
declare(strict_types=1);

namespace Raxos\Foundation\Storage;

use RuntimeException;

/**
 * Class ReadonlyKeyValue
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Storage
 * @since 1.0.0
 */
class ReadonlyKeyValue extends SimpleKeyValue
{

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    protected function setValue(int|string $offset, mixed $value): void
    {
        throw new RuntimeException('The store is readonly.', 500);
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    protected function unsetValue(int|string $offset): void
    {
        throw new RuntimeException('The store is readonly.', 500);
    }

}
