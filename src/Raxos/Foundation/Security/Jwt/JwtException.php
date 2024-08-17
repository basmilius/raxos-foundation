<?php
declare(strict_types=1);

namespace Raxos\Foundation\Security\Jwt;

use JsonException;
use Raxos\Foundation\Error\{ExceptionId, RaxosException};
use function date;
use function sprintf;

/**
 * Class JwtException
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Security\Jwt
 * @since 1.0.17
 */
final class JwtException extends RaxosException
{

    /**
     * Returns an expired exception.
     *
     * @return self
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.17
     */
    public static function expired(): self
    {
        return new self(
            ExceptionId::for(__METHOD__),
            'jwt_expired',
            'Expired token.'
        );
    }

    /**
     * Returns an invalid argument exception.
     *
     * @param string $message
     *
     * @return self
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.17
     */
    public static function invalidArgument(string $message): self
    {
        return new self(
            ExceptionId::for(__METHOD__),
            'jwt_invalid_argument',
            $message
        );
    }

    /**
     * Returns an invalid signature exception.
     *
     * @return self
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.17
     */
    public static function invalidSignature(): self
    {
        return new self(
            ExceptionId::for(__METHOD__),
            'jwt_invalid_signature',
            'Invalid signature.'
        );
    }

    /**
     * Returns a json error exception.
     *
     * @param JsonException $err
     *
     * @return self
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.17
     */
    public static function jsonError(JsonException $err): self
    {
        return new self(
            ExceptionId::for(__METHOD__),
            'jwt_json_error',
            'Json (de)serialization error.',
            $err
        );
    }

    /**
     * Returns a not-yet-valid exception.
     *
     * @param int $date
     *
     * @return self
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.17
     */
    public static function notYetValid(int $date): self
    {
        $date = date('Y-m-d\TH:i:sO', $date);

        return new self(
            ExceptionId::for(__METHOD__),
            'jwt_not_yet_valid',
            sprintf('The token is not yet valid. It will be valid from %s.', $date)
        );
    }

    /**
     * Returns a null error exception.
     *
     * @return self
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.17
     */
    public static function null(): self
    {
        return new self(
            ExceptionId::for(__METHOD__),
            'jwt_null',
            'NULL result with non-NULL data.'
        );
    }

    /**
     * Returns an openssl error exception.
     *
     * @param string $message
     *
     * @return self
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.17
     */
    public static function opensslError(string $message): self
    {
        return new self(
            ExceptionId::for(__METHOD__),
            'jwt_openssl_error',
            $message
        );
    }

    /**
     * Returns a unsupported exception.
     *
     * @param string $message
     *
     * @return self
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.17
     */
    public static function unsupported(string $message): self
    {
        return new self(
            ExceptionId::for(__METHOD__),
            'jwt_unsupported',
            $message
        );
    }

}
