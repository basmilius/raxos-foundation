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
final readonly class IP implements JsonSerializable, Stringable
{

    /**
     * IP constructor.
     *
     * @param string $value
     * @param IPVersion $version
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct(
        public string $value,
        public IPVersion $version
    ) {}

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
     * @return self|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function parse(string $ip): ?self
    {
        static $cache = [];

        if (isset($cache[$ip])) {
            return $cache[$ip];
        }

        if (!self::isValid($ip)) {
            return $cache[$ip] = null;
        }

        if (self::isV4($ip)) {
            return $cache[$ip] = new self($ip, IPVersion::V4);
        }

        return $cache[$ip] = new self($ip, IPVersion::V6);
    }

}
