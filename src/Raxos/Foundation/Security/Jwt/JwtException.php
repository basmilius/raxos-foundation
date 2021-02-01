<?php
declare(strict_types=1);

namespace Raxos\Foundation\Security\Jwt;

use Raxos\Foundation\Error\RaxosException;

/**
 * Class JwtException
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Security\Jwt
 * @since 1.0.0
 */
final class JwtException extends RaxosException
{

    public const ERR_JSON_ERROR = 1;
    public const ERR_NULL_RESULT = 2;
    public const ERR_UNSUPPORTED = 4;
    public const ERR_OPENSSL = 8;
    public const ERR_INVALID_ARGUMENT = 16;
    public const ERR_UNEXPECTED_ARGUMENT = 32;
    public const ERR_INVALID_SIGNATURE = 64;
    public const ERR_NOT_YET_VALID = 128;
    public const ERR_EXPIRED = 256;

}
