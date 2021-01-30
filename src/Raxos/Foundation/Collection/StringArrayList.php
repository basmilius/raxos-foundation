<?php
declare(strict_types=1);

namespace Raxos\Foundation\Collection;

use function implode;
use function is_string;
use function preg_replace;
use function sprintf;

/**
 * Class StringArrayList
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Collection
 * @since 1.0.0
 */
class StringArrayList extends ArrayList
{

    /**
     * Glues the strings together with commas and replaces the last one with
     * an amperstand.
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function commaCommaAnd(): string
    {
        return preg_replace('/(.*),/', '$1 &', implode(', ', $this->items));
    }

    /**
     * Glues the strings together using the given glue.
     *
     * @param string $glue
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function join(string $glue = ', '): string
    {
        return implode($glue, $this->items);
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    protected static function validateItem(mixed $item): void
    {
        if (!is_string($item)) {
            throw new CollectionException(sprintf('%s only accepts strings.', static::class), CollectionException::ERR_INVALID_TYPE);
        }
    }

}
