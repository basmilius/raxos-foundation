<?php
declare(strict_types=1);

namespace Raxos\Foundation\Security;

use Random\RandomException;
use Raxos\Foundation\Util\Base64;
use RuntimeException;
use function random_bytes;

/**
 * Class TokenGenerator
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Security
 * @since 2.0.0
 */
final class TokenGenerator
{

    /**
     * Generates a cryptographically secure random token.
     *
     * @param int $length
     * @param bool $urlSafe
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 2.0.0
     * @see random_bytes()
     * @see Base64
     */
    public static function generateCryptographicallySecureToken(int $length, bool $urlSafe = false): string
    {
        try {
            $bytes = random_bytes($length);

            if ($urlSafe) {
                return Base64::encodeUrlSafe($bytes);
            }

            return Base64::encode($bytes);
        } catch (RandomException $err) {
            throw new RuntimeException($err->getMessage(), $err->getCode(), $err);
        }
    }

}
