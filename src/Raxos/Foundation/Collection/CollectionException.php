<?php
declare(strict_types=1);

namespace Raxos\Foundation\Collection;

use Raxos\Foundation\Error\RaxosException;

/**
 * Class CollectionException
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Collection
 * @since 1.0.0
 */
class CollectionException extends RaxosException
{

    public const ERR_NON_COLLECTION = 1;
    public const ERR_INVALID_TYPE = 2;

}
