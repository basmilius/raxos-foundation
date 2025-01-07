<?php
declare(strict_types=1);

namespace Raxos\Foundation\Option;

use Raxos\Foundation\Error\{ExceptionId, RaxosException};
use function sprintf;

/**
 * Class OptionException
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Option
 * @since 1.1.0
 */
final class OptionException extends RaxosException
{

    /**
     * Returns the exception for when an option is expected.
     *
     * @return self
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public static function notAnOption(): self
    {
        return new self(
            ExceptionId::for(__METHOD__),
            'option_not_an_option',
            sprintf('Expected a value of type "%s" or "%s".', Some::class, None::class)
        );
    }

    /**
     * Returns the exception for when {@see None::get()} is called.
     *
     * @return self
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public static function noValue(): self
    {
        return new self(
            ExceptionId::for(__METHOD__),
            'option_no_value',
            sprintf('"%s" has no value.', None::class)
        );
    }

}
