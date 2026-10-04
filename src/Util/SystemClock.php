<?php
declare(strict_types=1);

namespace Raxos\Foundation\Util;

use DateTimeImmutable;
use DateTimeZone;
use Psr\Clock\ClockInterface;

/**
 * Class SystemClock
 *
 * Provides a UTC wall clock through the PSR clock contract.
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Util
 * @since 3.3.0
 */
final readonly class SystemClock implements ClockInterface
{

    /**
     * Returns the current UTC instant without relying on the process default timezone.
     *
     * @return DateTimeImmutable
     * @author Bas Milius <bas@mili.us>
     * @since 3.3.0
     */
    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable('now', new DateTimeZone('UTC'));
    }

}
