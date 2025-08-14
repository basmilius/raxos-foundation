<?php
declare(strict_types=1);

namespace Raxos\Foundation\Contract;

use Stringable;

/**
 * Interface StringParsableInterface
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Contract
 * @since 2.0.0
 */
interface StringParsableInterface extends Stringable
{

    /**
     * Creates an instance from the given string.
     *
     * @param string $input
     *
     * @return self
     * @author Bas Milius <bas@mili.us>
     * @since 2.0.0
     */
    public static function fromString(string $input): self;

    /**
     * Returns the regex representation of the object.
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 2.0.0
     */
    public static function pattern(): string;

}
