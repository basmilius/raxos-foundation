<?php
declare(strict_types=1);

namespace Raxos\Foundation\Id;

use Raxos\Foundation\Error\{ExceptionId, RaxosException};
use function sprintf;

/**
 * Class UlidException
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Id
 * @since 1.3.1
 */
final class UlidException extends RaxosException
{

    /**
     * Invalid length.
     *
     * @param string $value
     *
     * @return self
     * @author Bas Milius <bas@mili.us>
     * @since 1.3.1
     */
    public static function invalidLength(string $value): self
    {
        return new self(
            ExceptionId::guess(),
            'ulid_invalid_length',
            sprintf('Invalid length for ULID string "%s".', $value)
        );
    }

    /**
     * Timestamp too large.
     *
     * @return self
     * @author Bas Milius <bas@mili.us>
     * @since 1.3.1
     */
    public static function timestampTooLarge(): self
    {
        return new self(
            ExceptionId::guess(),
            'ulid_timestamp_too_large',
            'Timestamp too large for ULID.'
        );
    }

    /**
     * Wrong characters.
     *
     * @param string $value
     *
     * @return self
     * @author Bas Milius <bas@mili.us>
     * @since 1.3.1
     */
    public static function wrongCharacters(string $value): self
    {
        return new self(
            ExceptionId::guess(),
            'ulid_wrong_characters',
            sprintf('Wrong characters in ULID string "%s".', $value)
        );
    }

}
