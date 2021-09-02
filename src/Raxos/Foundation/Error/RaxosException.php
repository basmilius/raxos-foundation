<?php
declare(strict_types=1);

namespace Raxos\Foundation\Error;

use Exception;
use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;
use Throwable;

/**
 * Class RaxosException
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Error
 * @since 1.0.0
 */
abstract class RaxosException extends Exception implements JsonSerializable
{

    /**
     * RaxosException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct(string $message, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    #[ArrayShape([
        'error' => 'int',
        'error_description' => 'string'
    ])]
    public function jsonSerialize(): array
    {
        return [
            'error' => $this->getCode(),
            'error_description' => $this->getMessage()
        ];
    }

}
