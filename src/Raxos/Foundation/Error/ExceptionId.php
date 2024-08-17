<?php
declare(strict_types=1);

namespace Raxos\Foundation\Error;

use JsonSerializable;
use function base_convert;
use function hash;

/**
 * Class ExceptionId
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Error
 * @since 1.0.17
 */
final readonly class ExceptionId implements JsonSerializable
{

    /**
     * ExceptionId constructor.
     *
     * @param int $value
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.17
     */
    public function __construct(
        public int $value
    ) {}

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.17
     */
    public function jsonSerialize(): int
    {
        return $this->value;
    }

    /**
     * Returns a unique exception if for the given method.
     *
     * @param string $methodName
     *
     * @return self
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.17
     */
    public static function for(string $methodName): self
    {
        $id = (int)base_convert(hash('crc32', $methodName), 16, 10);

        return new self($id);
    }

}
