<?php
declare(strict_types=1);

namespace Raxos\Foundation\Util;

/**
 * Enum StopwatchUnit
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Util
 * @since 1.0.16
 */
enum StopwatchUnit
{
    case NANOSECONDS;
    case MICROSECONDS;
    case MILLISECONDS;
    case SECONDS;
}
