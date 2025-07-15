<?php
declare(strict_types=1);

namespace Raxos\Foundation\Util;

/**
 * Enum StopwatchState
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Util
 * @since 1.0.16
 */
enum StopwatchState
{
    case IDLE;
    case RUNNING;
    case STOPPED;
}
