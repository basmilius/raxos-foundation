<?php
declare(strict_types=1);

namespace Raxos\Foundation\Collection;

use Raxos\Foundation\Contract\ValidatedArrayListInterface;
use function array_sum;
use function is_int;

/**
 * Class IntArrayList
 *
 * @extends ArrayList<int, int>
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Collection
 * @since 1.0.0
 */
class IntArrayList extends ArrayList implements ValidatedArrayListInterface
{

    /**
     * Sums the items of the array list.
     *
     * @return int
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function sum(): int
    {
        return (int)array_sum($this->data);
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function validateItem(mixed $item): void
    {
        if (!is_int($item)) {
            throw CollectionException::invalidType(static::class, 'int');
        }
    }

}
