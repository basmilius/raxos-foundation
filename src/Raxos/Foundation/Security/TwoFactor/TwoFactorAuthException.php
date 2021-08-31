<?php
declare(strict_types=1);

namespace Raxos\Foundation\Security\TwoFactor;

use Raxos\Foundation\Error\RaxosException;

/**
 * Class TwoFactorAuthException
 *
 * @author Bas Milius <bas@glybe.nl>
 * @package Raxos\Foundation\Security\TwoFactor
 * @since 2.0.0
 */
class TwoFactorAuthException extends RaxosException
{

    public const ERR_INVALID_ARGUMENT = 1;
    public const ERR_INVALID_BASE32 = 2;

}
