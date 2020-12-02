<?php
declare(strict_types=1);

namespace Raxos\Foundation\Util;

use function array_filter;
use function array_keys;
use function count;

/**
 * Class ArrayUtil
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Util
 * @since 1.0.0
 */
final class ArrayUtil
{

    /**
     * Returns TRUE if the given array is associative.
     *
     * @param array $arr
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function isAssociative(array $arr): bool
    {
        return count(array_filter(array_keys($arr), 'is_string')) === count($arr);
    }

    /**
     * Returns TRUE if the given array is sequential.
     *
     * @param array $arr
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function isSequential(array $arr): bool
    {
        return count(array_filter(array_keys($arr), 'is_int')) === count($arr);
    }

}
