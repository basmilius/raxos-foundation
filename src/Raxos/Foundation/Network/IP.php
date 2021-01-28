<?php
declare(strict_types=1);

namespace Raxos\Foundation\Network;

use JsonSerializable;
use Stringable;
use function filter_var;
use const FILTER_FLAG_IPV4;
use const FILTER_FLAG_IPV6;
use const FILTER_VALIDATE_IP;

/**
 * Class IP
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Network
 * @since 1.0.0
 */
abstract class IP implements JsonSerializable, Stringable
{

    /**
     * IP constructor.
     *
     * @param string $value
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct(protected string $value)
    {
    }

    /**
     * Gets the IP value.
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function getValue(): string
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function jsonSerialize(): string
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * Returns TRUE if the given IP is an IPv4 one.
     *
     * @param string $ip
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function isV4(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false;
    }

    /**
     * Returns TRUE if the given IP is an IPv6 one.
     *
     * @param string $ip
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function isV6(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false;
    }

    /**
     * Returns TRUE if the given IP is valid.
     *
     * @param string $ip
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function isValid(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP) !== false;
    }

    /**
     * Parses the given IP.
     *
     * @param string $ip
     *
     * @return IPv4|IPv6|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function parse(string $ip): IPv4|IPv6|null
    {
        if (!self::isValid($ip)) {
            return null;
        }

        if (self::isV4($ip)) {
            return new IPv4($ip);
        }

        return new IPv6($ip);
    }

}
