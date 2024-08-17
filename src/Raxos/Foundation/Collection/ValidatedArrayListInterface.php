<?php
declare(strict_types=1);

namespace Raxos\Foundation\Collection;

/**
 * Interface ValidatedArrayListInterface
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Collection
 * @since 1.0.17
 */
interface ValidatedArrayListInterface
{

    /**
     * Validates the given item.
     *
     * @param mixed $item
     *
     * @throws CollectionException
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.17
     * @see ArrayList::of()
     */
    public static function validateItem(mixed $item): void;

}
