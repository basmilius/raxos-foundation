<?php
declare(strict_types=1);

namespace Raxos\Foundation\Contract;

use ReflectionException;

/**
 * Interface ReflectionFailedExceptionInterface
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Contract
 * @since 2.0.0
 */
interface ReflectionFailedExceptionInterface
{

    public ReflectionException $err {
        get;
    }

}
