<?php
declare(strict_types=1);

namespace Raxos\Foundation\Security\TwoFactor;

use Random\RandomException;
use Raxos\Foundation\Error\{ExceptionId, RaxosException};

/**
 * Class TwoFactorAuthException
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Security\TwoFactor
 * @since 1.0.17
 */
final class TwoFactorAuthException extends RaxosException
{

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
            '2fa_invalid_argument',
            $message
        );
    }

    /**
     * Returns an invalid data exception.
     *
     * @param string $message
     *
     * @return self
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.17
     */
    public static function invalidData(string $message): self
    {
        return new self(
            ExceptionId::for(__METHOD__),
            '2fa_invalid_data',
            $message
        );
    }

    /**
     * Returns a randomizer error exception.
     *
     * @param RandomException $err
     *
     * @return self
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.17
     */
    public static function randomError(RandomException $err): self
    {
        return new self(
            ExceptionId::for(__METHOD__),
            '2fa_random_error',
            'Could not get a random value.',
            $err
        );
    }

}
