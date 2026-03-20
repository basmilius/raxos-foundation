<?php
declare(strict_types=1);

namespace Raxos\Foundation\Error;

use Raxos\Error\Exception;

/**
 * Class TemporaryFileFailedException
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Error
 * @since 2.0.0
 */
final class TemporaryFileFailedException extends Exception
{

    /**
     * TemporaryFileFailedException constructor.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 2.0.0
     */
    public function __construct()
    {
        parent::__construct(
            'file_system_temporary_file_failed',
            'Failed to create a temporary file.'
        );
    }

}
