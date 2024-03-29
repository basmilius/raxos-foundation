<?php
declare(strict_types=1);

namespace Raxos\Foundation\Collection;

use function array_sum;
use function is_float;
use function is_int;
use function sprintf;

/**
 * Class NumberArrayList
 *
 * @extends ArrayList<int, int|float>
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Collection
 * @since 1.0.0
 */
class NumberArrayList extends ArrayList
{

    /**
     * Sums the items of the ArrayList.
     *
     * @return float|int
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function sum(): float|int
    {
        return array_sum($this->items);
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    protected static function validateItem(mixed $item): void
    {
        if (!is_int($item) && !is_float($item)) {
            throw new CollectionException(sprintf('%s only accepts integers.', static::class), CollectionException::ERR_INVALID_TYPE);
        }
    }

}
