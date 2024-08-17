<?php
declare(strict_types=1);

namespace Raxos\Foundation\Error;

use Exception;
use IntBackedEnum;
use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;
use Throwable;

/**
 * Class RaxosException
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Error
 * @since 1.0.17
 */
abstract class RaxosException extends Exception implements JsonSerializable
{

    /**
     * RaxosException constructor.
     *
     * @param ExceptionId|IntBackedEnum $id
     * @param string $error
     * @param string $errorDescription
     * @param Throwable|null $previous
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.17
     */
    public function __construct(
        public readonly ExceptionId|IntBackedEnum $id,
        public readonly string $error,
        public readonly string $errorDescription,
        public readonly ?Throwable $previous = null
    )
    {
        parent::__construct($this->errorDescription, $id->value, $previous);
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.17
     */
    #[ArrayShape([
        'code' => 'int',
        'error' => 'string',
        'error_description' => 'string'
    ])]
    public function jsonSerialize(): array
    {
        $result = [
            'code' => $this->id->value,
            'error' => $this->error,
            'error_description' => $this->errorDescription
        ];

        if ($this->previous instanceof JsonSerializable) {
            $result['previous'] = $this->previous;
        }

        return $result;
    }

}
