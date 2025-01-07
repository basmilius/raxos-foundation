<?php
declare(strict_types=1);

namespace Raxos\Foundation\Network;

/**
 * Enum IPVersion
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Network
 * @since 1.4.0
 */
enum IPVersion: string
{
    case V4 = 'IPv4';
    case V6 = 'IPv6';
}
