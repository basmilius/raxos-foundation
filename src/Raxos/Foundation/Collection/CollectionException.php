<?php
declare(strict_types=1);

namespace Raxos\Foundation\Collection;

use Raxos\Foundation\Error\{ExceptionId, RaxosException};
use function sprintf;

/**
 * Class CollectionException
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Collection
 * @since 1.0.17
 */
final class CollectionException extends RaxosException
{

    /**
     * Returns an invalid type exception.
     *
     * @param class-string<ArrayList> $class
     * @param string $expected
     *
     * @return self
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.17
     */
    public static function invalidType(string $class, string $expected): self
    {
        return new self(
            ExceptionId::for(__METHOD__),
            'collection_invalid_type',
            sprintf('%s only accepts items of type %s.', $class, $expected)
        );
    }

}
